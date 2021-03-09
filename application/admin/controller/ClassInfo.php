<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;
use think\Log;

/**
 * 课程管理
 *
 * @icon fa fa-circle-o
 */
class ClassInfo extends Backend
{

    /**
     * ClassInfo模型对象
     * @var \app\admin\model\ClassInfo
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ClassInfo;
        $this->view->assign("classtypeList", $this->model->getClasstypeList());
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
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);
            Log::write('list' . print_r($list, true), 'debug');
            $ids = '';
            foreach ($list as $v) {
                $ids .= $v->id . ',';
            }
            rtrim($ids, ',');
            Log::write('ids' . print_r($ids, true), 'debug');
            $alias = ['fa_class_info' => 'fci', 'fa_category' => 'fc', 'fa_member_info' => 'fmi', 'fa_teacher_info' => 'fti'];
            $join = [
                ['fa_category', 'fc.id = fci.classgroup_id', 'LEFT'],
                ['fa_member_info', 'find_in_set(fmi.id,fci.classlevel_id)', 'LEFT'],
                ['fa_teacher_info', 'fti.id = fci.classteacher_id', 'LEFT'],
            ];
            $field = ['fci.id', 'group_concat(fmi.member_name) as member_name', 'fti.teachername', 'fc.name as category_name'];

            $where = [];
            $where['fci.id'] = ['in', $ids];
            $row = Db::table('fa_class_info')
                ->alias($alias)
                ->join($join)
                ->field($field)
                ->where($where)->group('fci.id')->select();
            Log::write('row' . print_r($row, true), 'debug');
            // 转换成hash
            $tmp = [];
            foreach ($row as $v) {
                $tmp[$v['id']] = $v;
            }

            foreach ($list as $v) {
                $v['attr'] = $tmp[$v['id']];
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }
}
