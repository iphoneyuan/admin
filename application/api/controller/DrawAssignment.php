<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/6
 * Time: 11:02
 */

namespace app\api\controller;


use think\Controller;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\api\service\Token as TokenService;

class DrawAssignment extends Controller
{
    protected  $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'sureAssignment,receiptor,changepublicsure']
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

    //当用户确认领取时
    public function sureAssignment($id){
        $intergration=input('intergration');
        $all=['id'=>$id,'intergration'=>$intergration];
        $sureAssignment=model('DrawAssignment')->sureAssignment($all);
        return json_encode($sureAssignment);
    }

    //查询一条记录有哪些用户领取
    public function  receiptor($id){
        $receive=model('DrawAssignment')->receiptor($id);
        return json_encode($receive);
    }

    //更改领取表数据相关的字段信息 发布者确认信息
    public function  changepublicsure(){
        $assignment_assignmentId=input('assignment_assignmentId');
        $user_userId=input('user_userId');
        $data=['assignment_assignmentId'=>$assignment_assignmentId,'user_userId'=>$user_userId];
        $changepublicsure=model('DrawAssignment')->changepublicsure($data);
        return $changepublicsure;
    }

    //发布者确认完成
    public function changepublicfinish(){
        $assignment_assignmentId=input('assignment_assignmentId');
        $user_userId=input('user_userId');
        $data=['assignment_assignmentId'=>$assignment_assignmentId,'user_userId'=>$user_userId];
        $changepublicfinish=model('DrawAssignment')->changepublicfinish($data);
        return $changepublicfinish;
    }

}