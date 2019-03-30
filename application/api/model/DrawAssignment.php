<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/6
 * Time: 11:00
 */

namespace app\api\model;


use app\lib\exception\CheckDataException;
use app\lib\exception\IsPersonException;
use think\Db;
use think\Model;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\CheckAssignmentException;

class DrawAssignment extends Model
{
    public function sureAssignment($assignmentId){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $all=['assignment_assignmentId'=>$assignmentId['id'],'user_userId'=>$uid,'publicsure'=>0];
        $this->checkDraw($all);
        $this->checkRepeat($assignmentId['id']);
        $this->checkDate($all);
        $result=Db::table('Draw_Assignment')->insert($all);
        if($result==1){
            $data=array('status' => 1, 'code'=>'任务领取成功');
            return $data;
        }else{
            $data=array('status' => 0, 'code'=>'任务领取失败');
            return $data;
        }


    }

    //检查领取期限是否超过额定期限
    public function checkDate($all){
        $result=Db::table('assignment')
            ->where('assignmentId',$all['assignment_assignmentId'])
            ->find();
        if(($result['enddata']-time())<$result['countdata']){
            throw new CheckDataException();
        }
    }


    //检查是否领取任务
    public function checkDraw($all){
       $result=Db::table('Draw_Assignment')
           ->where('assignment_assignmentId',$all['assignment_assignmentId'])
           ->where('user_userId',$all['user_userId'])
           ->find();
        if($result){
           throw new CheckAssignmentException();
        }
    }
    //判断发布者和领取者是否同为一人
    public function checkRepeat($id){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $userId=Db::table('assignment')
            ->where('assignmentId',$id)
            ->field('user_userId')
            ->find();
        if($userId['user_userId']==$uid){
            throw new IsPersonException();
        }

    }

    //查询有多少人领取该任务
    public function receiptor($id){
        $result=Db::table('Draw_Assignment')
            ->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId')
            ->field('name,avatarUrl,studentId,department,b.user_userId,publicsure,publicfinish')
            ->where('assignment_assignmentId',$id)
            ->select();
        return $result;
    }
    //更改领取表数据相关的字段信息
    public function changepublicsure($data){
        $check=Db::table('Draw_Assignment')
            ->where('assignment_assignmentId',$data['assignment_assignmentId'])
            ->where('user_userId',$data['user_userId'])
            ->where('publicsure',2)
            ->find();
        if($check){
            throw new CheckAssignmentException();
        }
        //当发布者选取了相应的领取者以后，该任务在前台就不可见的了
        $asssure=Db::table('assignment')
            ->where('assignmentId',$data['assignment_assignmentId'])
            ->update(['assign_sure'=>1]);
        if($asssure!=1){
            throw new CheckAssignmentException();
        }

        $refuse=Db::table('Draw_Assignment')
               ->where('assignment_assignmentId',$data['assignment_assignmentId'])
               ->update(['publicsure'=>1]);

        $result=Db::table('Draw_Assignment')
              ->where('assignment_assignmentId',$data['assignment_assignmentId'])
              ->where('user_userId',$data['user_userId'])
              ->update(['publicsure'=>2]);

        if( $refuse&&$result){
            $data=array('error_code' => 1, 'msg'=>'任务已确认成功');
            return json_encode($data);
        }else{
            $data=array('error_code' => 0, 'msg'=>'任务确认失败，内部问题');
            return json_encode($data);
        }
    }

    //发布者确认任务结束
    public function changepublicfinish(){

    }

}
