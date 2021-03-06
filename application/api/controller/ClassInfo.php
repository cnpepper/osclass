<?php

namespace app\api\controller;

use app\common\controller\Api;
use \think\Db;
/**
 * 自定义接口
 */
class ClassInfo extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 课程查询
     *
     * @ApiTitle    (测试名称)
     * @ApiSummary  (测试描述信息)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/demo/test/id/{id}/name/{name})
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiParams   (name="name", type="string", required=true, description="用户名")
     * @ApiParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
        })
     */
    public function query(){
        $req = $this->request->param();  
        
        $alias = ['fa_class_list'=>'fal','fa_class_info'=>'fai'];
        $join = [
            ['fa_class_info','fal.classinfo_id = fai.id','LEFT']
        ];
        $field = ['fai.id','fai.classtitle','fai.classdesc','fai.classremark',
        'fai.classtype','fai.classimage','fai.classplaynum','fai.classsavenum',
        'fai.classgroup_id','fai.classlevel_id','fai.classteacher_id','fai.isrecommend',

        'fal.id as list_id','fal.classtitle','fal.classremark','fal.classfile','fal.classlength',
        'fal.tryswitch','fal.classlearnnum'
        ];

        // 拼接where条件
        $where = [];

        // 按照专题查询
        if(!empty($req['id'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        // 按照分组查询
        if(!empty($req['classgroup_id'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        // 按照老师查询
        if(!empty($req['classteacher_id'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        // 按照推荐查询
        if(!empty($req['isrecommend'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        // 按照等级查询
        if(!empty($req['classlevel_id'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        // 按照类型查询
        if(!empty($req['classtype'])){
            $where['fai.id'] = ['=',$req['id']];
        }

        $total = Db::table('fa_class_list')
        ->alias($alias)
        ->join($join)
        ->where($where)->count();

        // 分页查询课程列表
        $res = Db::table('fa_class_list')
        ->alias($alias)
        ->join($join)
        ->field($field)
        ->where($where)->page(1,10)->select();
        
        $data = [
            'total'=>$total,
            'info'=>$res
        ];

        $this->success(__('Sign up successful'), $data);
    }

    /**
     * 返回单个课程的信息，包含用户评论
     *
     * @return void
     */
    public function queryById(){
        $req = $this->request->param();  
        
        $alias = ['fa_class_list'=>'fal','fa_user_talk'=>'fat','fa_user'=>'fu'];
        $join = [
            ['fa_user_talk','fal.id = fat.order_id and fat.type = 1','LEFT'],
            ['fa_user','fu.id = fat.user_id','LEFT'],
        ];
        $field = [
        'fal.id as list_id','fal.classtitle','fal.classremark','fal.classfile','fal.classlength',
        'fal.tryswitch','fal.classlearnnum',
        'fat.message','fat.createtime',
        'fu.nickname','fu.avatar'
        ];

        // 拼接where条件
        $where = [];

        // 按照专题查询
        if(!empty($req['id'])){
            $where['fal.id'] = ['=',$req['id']];
        }

        $total = Db::table('fa_class_list')
        ->alias($alias)
        ->join($join)
        ->where($where)->count();

        // 分页查询课程列表
        $res = Db::table('fa_class_list')
        ->alias($alias)
        ->join($join)
        ->field($field)
        ->where($where)->page(1,10)->select();
        
        $data = [
            'total'=>$total,
            'info'=>$res
        ];

        $this->success(__('Sign up successful'), $data);
    }
}
