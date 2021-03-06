<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\api\model\CategoryModel;
use \think\Db;
/**
 * 自定义接口
 */
class Category extends Api
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

    /**
     * 测试方法
     *
     * @ApiTitle    (测试名称)
     * @ApiSummary  (测试描述信息)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/demo/test/id/{id}/name/{name})
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="id", type="integer", required=true, description="会员ID")
     * @ApiParams   (name="name", type="string", required=true, description="用户名")
     * @ApiParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
        })
     */
    public function getCategoryList()
    {
        $param = $this->request->param();
        $this->page_no = isset($param['page_no'])?$param['page_no']:1;

        $obj = new CategoryModel();

        // 定义查询条件
        $case = [
            'id'
        ];

        // 生成查询条件
        $where = make_where($param,$case);

        $total = $obj->count();
        $res = $obj->where($where)->page($this->page_no,$this->page_size)->select();
        
        // 转换成数组
        $res = collection($res)->toArray();
        
        // 返回前处理
        
        $this->success('ok', ['total'=>$total,'info'=>$res]);
    }
}
