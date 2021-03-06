<?php

namespace app\api\controller;

use app\common\controller\Api;
use \think\Db;
/**
 * 自定义接口
 */
class Trade extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 订单创建
     *
     * @ApiTitle    (订单创建)
     * @ApiSummary  (用户付款后进行订单创建)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/demo/test/id/{id}/name/{name})
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiParams   (name="user_id", type="integer", required=true, description="用户ID")
     * @ApiParams   (name="member_id", type="integer", required=true, description="购买的会员ID")
     * @ApiParams   (name="pay_amount", type="number", required=true, description="用户实际支付金额")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
        })
     */
    public function Create()
    {
        // 创建订单
        // 用户ID、购买等级ID、支付金额
        $req = $request->param();

        $user_id = $req['user_id'];
        $member_id = $req['member_id'];
        $pay_amount = $req['pay_amount'];

        $res = Db::query("CALL TradeOrderCreate(?,?,?)",[$user_id,$member_id,$pay_amount]); 
        if($res){
            return [ 'code'=>1, 'msg'=>'创建订单成功', 'data'=>$res ];
        }else{
            return [ 'code'=>0, 'msg'=>'创建订单失败', 'data'=>[] ];
        }
    }
}
