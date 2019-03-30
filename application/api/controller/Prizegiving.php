<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/14
 * Time: 17:08
 */

namespace app\api\controller;


use think\Controller;
use app\lib\exception\TokenException;
use app\api\service\Token as TokenService;
use app\lib\exception\ForbiddenException;

class Prizegiving extends Controller
{
    //定义前置方法
    protected $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'prizegivingById']
//        'first'=>['only'=>'second,third']    我们也可以这样子写的
    ];

    protected function checkPrimaryScope(){
        $scope=TokenService::getCurrentTokenVar('scope');
        $openid=TokenService::getCurrentTokenVar('openid');
        $checkstudent=TokenService::checkStudent($openid);
        if($scope>15&&$checkstudent){
            if($scope>15){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    public function prizegivingAll()
    {
        $prizegivingAll = model('Prizegiving')->prizegivingAll();
        return $prizegivingAll;
    }

    public function prizegivingById($id){
        $prizegivingById=model('Prizegiving')->prizegivingById($id);
        return $prizegivingById;
    }

}