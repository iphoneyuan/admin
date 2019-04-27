<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/1
 * Time: 10:38
 */

namespace app\api\service;
use app\api\model\Student;
use app\lib\exception\AbleLoginException;
use app\lib\exception\CorporationException;
use app\lib\exception\LoginException;
use app\lib\exception\NotEmptyException;
use app\api\model\UserAddress;
use app\api\model\Student as StudentModel;
use app\api\model\UserAddress as UserAddressModel;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenServer;
use app\lib\exception\TokenException;
use think\Db;
use think\Exception;
use think\Model;

class Identity
{
    //身份认证接口
    public function Identity($students){
        //如果输入的全部为0，那么抛出错误
        if($students['studentId']==""|| $students['name']==""|| $students['password']==""|| $students['department']==""){
            throw new NotEmptyException();
        }
        $student=UserAddressModel::getStudentId($students['studentId']);
        if($student){
            throw new AbleLoginException();
        }else{
            //进行md5加密
            $students['password']=md5($students['password']);
            //在我们的学生表里面取出对应的数据进行比对
            $student= StudentModel::getStudentAll($students['studentId']);
            if($student['studentId']==$students['studentId']&&$student['name']==$students['name']&&$student['password']==$students['password']&&$student['department']==$students['department']){
                $uid=$this->GetOpenid($students['code']);
                $students['user_userId']=$uid;
                unset($students['code']);
                $identity=UserModel::UpPersonal($students);
                return $identity;
            }else{
                throw new LoginException();
            }
        }
    }

    public function CheckLogin(){
        $uid=TokenServer::getCurrentUid();
        $useraddress=Db::table('user_address')->where('user_userId',$uid)->find();
        if($uid&&$useraddress){
            return ['errorCode'=>'1','msg'=>'已登录'];
        }else{
            throw new TokenException();
        }
    }

    public function CheckCorporationLogin(){
        $uid=TokenServer::getCurrentUid();
        $useraddress=Db::table('user_address')->where('user_userId',$uid)->find();
        $corporation=Db::table('leading')->where('user_userId',$uid)->find();
        if(!$corporation){
           throw new CorporationException();
        }elseif(!$uid&&!$useraddress){
            throw new TokenException();
        }else{
            return ['errorCode'=>'1','msg'=>'已登录'];
        }


    }

 //确认登录按钮
    public function CheckLoginButton(){
        $uid=TokenServer::getCurrentUid();
        if($uid){
            return ['errorCode'=>'1','msg'=>'已登录'];
        }else{
            throw new TokenException();
        }
    }
   //拿到openip
    public function GetOpenid($code){
        $wxAppID = config('wx.app_id');
        $wxAppSecret = config('wx.app_secret');
        $wxLoginUrl = sprintf(config('wx.login_url'),$wxAppID, $wxAppSecret, $code);
        $result = curl_get($wxLoginUrl);
        $wxResult = json_decode($result, true);
        $openid=$wxResult['openid'];
        $user =UserModel::getByOpenID($openid);
        if($user){
            $uid=$user->userId;
        }else{
            $uid=$this->newUser($openid);
        }
        return $uid;
    }


}