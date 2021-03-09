<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Coupon extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'coupon';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];
    

    







}
