<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2019/4/17
 * Time: 17:30
 */

namespace app\api\controller;


use think\Controller;

class CheckMessage extends Controller
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

    public function CheckMessage()
    {
        $word = input('word');
        //组装数据
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(
            config('wx.access_url'),
            $this->wxAppID, $this->wxAppSecret);
        //调用access_token接口
        $result = curl_get($this->wxLoginUrl);
        $access_token_all=json_decode($result,true);
        $access_token=$access_token_all["access_token"];
     //调用敏感信息接口
        $this->wxSecUrl = sprintf(
            config('wx.seccheck_url'), $access_token
        );
        $data = json_encode(array('content'=>$word),JSON_UNESCAPED_UNICODE);
       $info= http_request_url($this->wxSecUrl,$data);
       return $info;
    }


}