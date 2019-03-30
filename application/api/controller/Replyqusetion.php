<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/23
 * Time: 9:01
 */

namespace app\api\controller;


use think\Controller;
use app\lib\exception\TokenException;
use app\api\service\Token as TokenService;
use app\lib\exception\ForbiddenException;

class Replyqusetion extends Controller{
    //定义前置方法
    protected $beforeActionList=[
    'checkPrimaryScope'=>['only'=>'getResponse,thanksquestion']
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
//查询一条问题的回复详情
  public function getResponse($id){
        $result=model('Replyqusetion')->getResponse($id);
        return $result;
  }
  //当该问题发布者按下该条信息的感谢按钮时候对该用户进行积分的相对应的操作
    public function thanksquestion($id){
        $thank=model('Replyqusetion')->thanksquestion($id);
        return $thank;
    }



}