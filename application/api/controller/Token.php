<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/25
 * Time: 15:08
 */

namespace app\api\controller;
use app\api\service\Identity;
use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    //获取令牌
    public function getToken(){
        (new TokenGet())->goCheck();
        $code=input('code');
        $ut=new UserToken($code);
        $token=$ut->getToken();
        return json_encode($token);
    }
   //身份认证
    public function Identity(){
        $students=input('post.');
        $ut=new Identity();
        $identity=$ut->Identity($students);
        return json_encode($identity);
    }
    //确认登录
    public function CheckLogin(){
        $ut=new Identity();
        $result=$ut->CheckLogin();
        return json_encode($result);
    }

    public function CheckCorporationLogin(){
        $ut=new Identity();
        $result=$ut->CheckCorporationLogin();
        return json_encode($result);
    }

    //ȷ�ϵ�¼��ť
    public function CheckLoginButton(){
        $ut=new Identity();
        $result=$ut->CheckLoginButton();
        return json_encode($result);
    }

}