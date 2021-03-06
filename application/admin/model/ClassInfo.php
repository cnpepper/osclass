<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class ClassInfo extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'class_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'classtype_text'
    ];
    

    
    public function getClasstypeList()
    {
        return ['视频' => __('视频'), '音频' => __('音频')];
    }


    public function getClasstypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['classtype']) ? $data['classtype'] : '');
        $list = $this->getClasstypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
