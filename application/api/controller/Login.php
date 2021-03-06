<?php

namespace app\api\controller;

use app\common\controller\Api;
use \think\Db;
/**
 * 自定义接口
 */
class Login extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * WxLogin
     *
     * @ApiTitle    (小程序登录)
     * @ApiSummary  (使用用户的openid,直接登录获取token,后续调用接口的时候直接用token请求)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/login/Wxlogin)
     * @ApiParams   (name="openid", type="integer", required=true, description="openid")
     * @ApiParams   (name="parentid", type="integer", required=true, description="推广用户id")
     * @ApiParams   (name="sign", type="string", required=true, description="数字签名")
     * @ApiParams   (name="nickname", type="string", required=true, description="用户昵称")
     * @ApiParams   (name="avatar", type="string", required=true, description="用户头像地址")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="0")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="返回成功")
     * @ApiReturnParams   (name="data", type="object", sample="{'user_id':'int','user_name':'string','profile':{'email':'string','age':'integer'}}", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
         'data':[]
        })
     */
    public function WxLogin(){
        // 签名验证
        $openid = $this->request->param('openid');
        $nickname = $this->request->param('nickname');
        $avatar = $this->request->param('avatar');
        $parentid = $this->request->param('parentid');
        
        // 升序排序
        $req = $this->request->param();
        $req = sort($req);
        // 排序好的参数做base64拼接秘钥再md5加密
        $cur_sign = md5(base64_encode($req."uWvjKUsRGjy38v7P"));

        // 比较传入的签名
        $sign = $this->request->only('sign');
        /*if($cur_sign != $sign){
            $this->error(__('Invalid sign'));
        }*/
        
        // 通过微信唯一ID检查用户是否存在，如果存在则直接登录
        $user_id = Db::table('fa_user')->where('openid',$openid)->value('id');
        if(!$user_id){
            // 没有账号注册一个
            $data = $this->WxRegister($openid,$nickname,$avatar,$parentid);
            if($data){
                $this->success(__('Sign up successful'), $data);
            }else{
                $this->error(__('注册失败'));
            }   
        }

        // 重新登录前清除所有token
        \app\common\library\Token::clear($user_id);
        // 直接进行登录操作返回token值
        if(!$this->auth->direct($user_id)){
            $this->error(__('login error'));
        }

        $data = ['userinfo' => $this->auth->getUserinfo()];
        $this->success(__('Sign up successful'), $data);
    }
    
    private function WxRegister($openid,$nickname,$avatar,$parentid)
    {
        $password = '';
        $email = '';
        $mobile = '';
        if (!$openid) {
            $this->error(__('Invalid parameters'));
        }
        
        $ret = $this->auth->register($openid, $password, $email, $mobile, []);
        
        if ($ret) {
            // 注册会员成功后更新数据，首次注册的默认是初级会员
            $data = ['userinfo' => $this->auth->getUserinfo()];
            Db::table('fa_user')->where('id',$data['userinfo']['id'])->update(['nickname' => $nickname,'openid'=>$openid,'avatar'=>$avatar,'parentid'=>$parentid,'member_id'=>1]);
            return $data;
        } else {
            return null;
        }
    }
}
