<?php

namespace app\api\model;

use think\Model;

class TeacherInfo extends Model
{
    // 表名
    protected $name = 'teacher_info';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
    ];
}
