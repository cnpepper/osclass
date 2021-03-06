<?php

namespace app\admin\model;

use think\Model;


class TradeInfo extends Model
{

    

    

    // 表名
    protected $name = 'trade_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function memberInfo()
    {
        return $this->belongsTo('MemberInfo', 'member_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function firstUser()
    {
        return $this->belongsTo('User', 'first_user', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function secondUser()
    {
        return $this->belongsTo('User', 'second_user', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
