<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/12
 * Time: 15:50
 */

namespace app\api\controller;

use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use think\Controller;
use app\lib\exception\ForbiddenException;
use think\Db;

class Assignment extends Controller
{
    //定义前置方法
    protected $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'getAssignmentById']
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

    //获取所有的任务信息
    public function getAssignmentAll(){
     $assignmentAll=model('Assignment')->getAssignmentAll();
     return $assignmentAll;
    }

    //获取单一的任务详细信息
    public function getAssignmentById($id){
        $assignmentById=model('Assignment')->getAssignmentById($id);
        return $assignmentById;
    }

    //获取评论信息
    public function getCommentById(){
        $assignmentId=$this->request->post('id');
        $result=Db::table('assign_comment')->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId','LEFT')
            ->where('assignment_assignmentId',$assignmentId)
            ->select();
        return  json_encode($result);
    }

}