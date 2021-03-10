<?php

namespace app\api\model;

use think\Model;

class ClassInfo extends Model
{
    // 表名
    protected $name = 'class_info';

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

    public function ClassList()
    {
        return $this->hasMany('ClassList','classinfo_id','id');
    }

    public function TeacherInfo()
    {
        return $this->belongsTo('TeacherInfo','classteacher_id','id');
    }
}
