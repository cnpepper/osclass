<?php

namespace app\admin\model;

use think\Model;

class UserCoupon extends Model
{

    // 表名
    protected $name = 'user_coupon';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function getStatusAttr($value)
    {
        if (empty($value)) {
            $value = 0;
        }
        $status = [0 => '未使用', 1 => '已使用'];
        return $status[$value];
    }

}
