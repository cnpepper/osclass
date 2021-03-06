<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use \think\Db;

/**
 * 分类动态下拉查询接口
 *
 * @icon fa fa-circle-o
 */
class ClassQuery extends Backend
{
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index(){
        /**
         * {
    "list":[{"id":4,"username":"FastAdmin","nickname":"极速后台","avatar":"","pid":0},{"id":6,"username":"CRUD","nickname":"一键CRUD","avatar":"","pid":0}],
    "total":30
}
         */
        $res = Db::query("SELECT id,name FROM `fa_category` WHERE TYPE = 'class'");
        $list = [];
        $count = 0;
        foreach($res as $v){
            $count++;
            $list[] = ['id'=>$v['id'],
                        'name'=>$v['name']
            ];
        }
        return [
            'list'=>$list,'total'=>$count
        ];
    }
}
