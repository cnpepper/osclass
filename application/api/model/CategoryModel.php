<?php

namespace app\api\model;

use think\Model;

class CategoryModel extends Model
{
    // 表名
    protected $name = 'category';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];

    // 无用字段隐藏
    // protected $hidden = ['delete_time','update_time'];
}
