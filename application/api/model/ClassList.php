<?php

namespace app\api\model;

use think\Model;

class ClassList extends Model
{
    // 表名
    protected $name = 'class_list';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];
}
