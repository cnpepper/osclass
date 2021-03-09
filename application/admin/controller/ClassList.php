<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;

/**
 * 章节管理
 *
 * @icon fa fa-circle-o
 */
class ClassList extends Backend
{

    /**
     * ClassList模型对象
     * @var \app\admin\model\ClassList
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ClassList;

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

            $list = $this->addAttr($list);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    private function addAttr($list)
    {
        $ids = '';
        foreach ($list as $v) {
            $ids .= $v->id . ',';
        }
        rtrim($ids, ',');

        $alias = ['fa_class_list' => 'fcl', 'fa_class_info' => 'fci'];
        $join = [
            ['fa_class_info', 'fci.id = fcl.classinfo_id', 'LEFT'],
        ];
        $field = ['fcl.id', 'fci.classtitle'];

        $where = [];
        $where['fcl.id'] = ['in', $ids];
        $row = Db::table('fa_class_list')
            ->alias($alias)
            ->join($join)
            ->field($field)
            ->where($where)->select();

        // 转换成hash
        $tmp = [];
        foreach ($row as $v) {
            $tmp[$v['id']] = $v;
        }

        foreach ($list as $v) {
            $v['attr'] = $tmp[$v['id']];
        }
        return $list;
    }
}
