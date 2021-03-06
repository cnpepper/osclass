<?php

namespace app\api\controller;

use app\common\controller\Api;
use \think\Db;
/**
 * 自定义接口
 */
class Setting extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部 token放body里一起传过来就行了，放header里也行
    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 系统设置查询
     *
     * @ApiTitle    (系统设置查询)
     * @ApiSummary  (查询后台设置的全部配置信息,错误码1为成功,0为失败)
     * @ApiMethod   (POST)
     * @ApiRoute    (/api/setting/query)
     * @ApiHeaders  (name=token, type=string, required=true, description="请求的Token")
     * @ApiReturnParams   (name="code", type="integer", required=true, sample="1")
     * @ApiReturnParams   (name="msg", type="string", required=true, sample="ok")
     * @ApiReturnParams   (name="data", type="object", sample="{'id':'int','name':'string','title':'string','type':'string','value':'string','rule':'string','extend':'string','setting':'string'", description="扩展数据返回")
     * @ApiReturn   ({
         'code':'1',
         'msg':'返回成功'
         'data':[]
        })
     */

     public function Query(){
        $res = Db::query("SELECT id,`name`,title,`type`,`value`,rule,extend,setting FROM `fa_config` WHERE `group` = 'home'");
        if($res){
            $this->success(__('Sign up successful'), $res);
        }else{
            $this->error(__('注册失败'));
        }
     }

}
