<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use \think\Db;
/**
 * 订单管理
 *
 * @icon fa fa-circle-o
 */
class TradeInfo extends Backend
{
    
    /**
     * TradeInfo模型对象
     * @var \app\admin\model\TradeInfo
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\TradeInfo;

    }

    public function import()
    {
        parent::import();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['user','memberInfo','firstUser','secondUser'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);
            foreach ($list as $row) {
                
                $row->getRelation('user')->visible(['openid']);
                $row->getRelation('user')->visible(['nickname']);
				$row->getRelation('member_info')->visible(['member_name']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                //Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    // 调用存储过程创建订单
                    $result = Db::query("CALL TradeOrderCreate(?,?,?)",[$params['user_id'],$params['member_id'],$params['pay_amount']]); 
                    
                    //$result = $this->model->allowField(true)->save($params);
                    //Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }
    /**
     * 通过会员ID查询会员金额
     *
     * @return void
     */
    public function MemberAmountQuery(){
        $req = $this->request->param();

        if($req['member_id']){
            // 查询会员等级价格
            $admount = Db::table('fa_member_info')->where('id',$req['member_id'])->value('price');
            return [
                'code'=>1,
                'msg'=>'ok',
                'data'=>['amount'=>$admount]
            ];
        }else{
            return [
                'code'=>0,
                'msg'=>'fail',
                'data'=>[]
            ];
        }
    }

    /**
     * 通过用户ID查询一级分销和二级分销的信息
     *
     * @return void
     */
    public function ShareUserQuery(){
        $req = $this->request->only('user_id');
        $user_id = $req['user_id'];
        if($user_id){
            // 查询这个人一级和二级用户和比例
            $res = Db::query("SELECT fu2.id as first_id,fm1.`first_rate`,fu3.id as second_id,fm2.`second_rate` FROM `fa_user` fu1
            LEFT JOIN fa_user AS fu2 ON fu2.id = fu1.`parent_id`
            LEFT JOIN `fa_member_info` AS fm1 ON fm1.`id` = fu2.`member_id`
            LEFT JOIN fa_user AS fu3 ON fu3.id = fu2.`parent_id`
            LEFT JOIN `fa_member_info` AS fm2 ON fm2.`id` = fu3.`member_id`
            WHERE fu1.id = $user_id limit 1");

            return [ 'code'=>1, 'msg'=>'ok', 'data'=>[
                'first_user_id'=>$res[0]['first_id'],
                'first_rate'=>$res[0]['first_rate'],
                'second_user_id'=>$res[0]['second_id'],
                'second_rate'=>$res[0]['second_rate']
            ] ];
        }else{
            return [ 'code'=>0, 'msg'=>'fail', 'data'=>[] ];
        }
    }

}
