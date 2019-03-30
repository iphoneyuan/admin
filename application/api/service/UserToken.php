<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/25
 * Time: 15:21
 */

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Db;
use think\Exception;
use app\api\model\User as UserModel;



class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {

        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(
            config('wx.login_url'),
            $this->wxAppID, $this->wxAppSecret, $this->code);

    }



    //跟微信服务器交换token
  public function getToken(){
      $result = curl_get($this->wxLoginUrl);
      $wxResult = json_decode($result, true);
      if (empty($wxResult))
      {
          throw new Exception('获取session_key及openID时异常，微信内部错误');
      }
      else
      {
          $loginFail=array_key_exists('errcode',$wxResult);
          if ($loginFail) {
              $this->processLoginError($wxResult);
          } else {
              return $this->grantToken($wxResult);
          }
      }
  }
  public function grantToken($wxResult){
        //拿到openid
        //数据库看一下，这个openid是否存在
        //如果存在，不处理，如果不存在则新增一条user记录
        //把令牌返回到客户端去
        //key:令牌
        //value:wxResult,uid,scope
        //scope作为接口访问权限
      $openid=$wxResult['openid'];
      $user =UserModel::getByOpenID($openid);
      if($user){
          $uid=$user->userId;
      }else{
        $uid=$this->newUser($openid);
      }
      $cachedValue=$this->prepareCachedValue($wxResult,$uid);
      $token=$this->saveToCache($cachedValue);
      return $token;



  }
  private function saveToCache($cachedValue){
      $key=self::generateToken();    //生成token
      $value=json_encode($cachedValue);     //保存有openid,uid和scope
      $expire_in=config('setting.token_expire_in');
      $request=cache($key,$value,$expire_in);
      if(!$request){
          throw new TokenException([
              'msg'=>'服务器缓存异常',
              'errorCode'=>0
          ]);
      }
      return $key;
  }

//准备写入缓存,把需要缓存的数据组装好
  private function prepareCachedValue($wxResult,$uid){
        $cachedValue=$wxResult;
        $cachedValue['uid']=$uid;
        $cachedValue['scope']=ScopeEnum::User;
        return $cachedValue;

  }

//新增一条openid记录
  private function newUser($openid){
      $user=Db::table('user')->insertGetId(['openId'=>$openid]);
        return $user;

  }
//如果code码错误，我们则需要抛出一个错误异常
  private function processLoginError($wxResult){
        throw new WeChatException([
            'msg'=>$wxResult['errmsg'],
            'errorCode'=>$wxResult['errcode']
        ]);
  }
}