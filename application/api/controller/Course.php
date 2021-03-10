<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\ClassInfo;
use app\api\model\ClassList;

use \think\Db;
/**
 * 自定义接口
 */
class Course extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    private $page_no = 1;
    private $page_size = 10;

    // 查询课程专题列表
    public function getCourseSpecialList(){

        $param = $this->request->param();
        $this->page_no = isset($param['page_no'])?$param['page_no']:1;

        $class_info = new ClassInfo();

        // 定义查询条件
        $case = [
            'id',
            'classgroup_id',
            'classteacher_id',
            'isrecommend',
            'classlevel_id',
            'classtype'
        ];

        // 生成查询条件
        $where = make_where($param,$case);

        $total = $class_info->count();
        $res = $class_info->where($where)->page($this->page_no,$this->page_size)->select();
        
        // 转换成数组
        $res = collection($res)->toArray();
        
        // 返回前处理
        
        $this->success('ok', ['total'=>$total,'info'=>$res]);
    }

    

    // 查询课程章节列表
    public function getCourseChapterList(){
        $param = $this->request->param();
        $this->page_no = isset($param['page_no'])?$param['page_no']:1;

        $class_list = new ClassList();

        // 定义查询条件
        $case = [
            'id',
            'classinfo_id'
        ];

        // 生成查询条件
        $where = make_where($param,$case);

        $total = $class_list->count();
        $res = $class_list->where($where)->page($this->page_no,$this->page_size)->select();
        $res = collection($res)->toArray();
        
        // 返回前处理
        $this->success('ok', ['total'=>$total,'info'=>$res]);
    }


    // 查询课程专题章节列表
    public function getCourseDetails(){

        
        $param = $this->request->param();
        $this->page_no = isset($param['page_no'])?$param['page_no']:1;

        $class_info = new ClassInfo();

        // 定义查询条件
        $case = [
            'id',

        ];

        // 生成查询条件
        $where = make_where($param,$case);
        
        $total = $class_info->count();
        
        $res = $class_info->with('ClassList,TeacherInfo')->where($where)->page($this->page_no,$this->page_size)->select();
        $res = collection($res)->toArray();
        
        // 返回前处理
        $this->success('ok', ['total'=>$total,'info'=>$res]);
        
    }

    // 添加课程评论
    public function addCourseComment(){

    }

    // 查询课程评论
    public function getCourseComment(){

    }
}
