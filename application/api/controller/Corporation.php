<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/17
 * Time: 21:46
 */

namespace app\api\controller;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\api\service\Token as TokenService;
use think\Model;

class Corporation
{

    protected  $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'getCorporationById']
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

    public function getCorporationAll()
    {
        $corporationAll=model('Corporation')->getCorporationAll();
        return $corporationAll;
    }
    public function getCorporationById($id){
        $corporationById=model('Corporation')->getCorporationById($id);
        return $corporationById;

    }
}