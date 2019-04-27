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
use app\api\model\User as UserModel;
use think\Db;
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
    //获取一个社团负责人所发布的信息
    public function getCorporation(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $corporation=Db::name('draw_corporation')->alias('a')
            ->join('corporation c','a.corporation_id=c.corporationId')
            ->join('leading b','c.leading_id=b.id')
            ->where('b.user_userId',$uid)->select();

        return json_encode($corporation);
    }
    //确认任务
    public function comfireCorporation(){
        $corporation_id=input('corporation_id');
        $user_id=input('user_id');
        $query=Db::table('draw_corporation')->where('user_userId', $user_id)->where('corporation_id', $corporation_id);
        $chant['intergrationRequire']=Db::table('corporation')->where('corporationId',$corporation_id)->find()['intergrationRequire'];
        $chant['user_userId']=$user_id;
        // 启动事务
        Db::startTrans();
        try{
            if(Db::table('leading')->where('user_userId', $user_id)->find()) {
                $result = $query->update(["public_sure" => 1]);
                getint($chant);
                if (!$result) {
                    $data = array('status' => 0, 'code' => '同意失败，内部问题');
                    return json_encode($data);
                } else {
                    // 提交事务
                    Db::commit();
                    $data = array('status' => 1, 'code' => '同意成功');

                    return json_encode($data);
                }
            }else{
                $data = array('status' => 0, 'code' => '您尚不是社团管理员');
                return json_encode($data);
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }

    //详情信息
    public function getCorporationDetail(){
        $id=input('id');
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }

        $draw_corporation=Db::name('draw_corporation')->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId')
            ->where('a.id',$id)
            ->select();

        return json_encode($draw_corporation);
    }

    //记录用户领取相关任务
    public function setCorporation(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $dataArray=input('post.');
        $dataArray['user_userId']=$uid;
        $dataArray['corporation_id']=(int)$dataArray['corporation_id'];
        //检查是否存在相同的数据
        $check=Db::table('draw_corporation')->find();
        if($check){
            $data = array('status' => 0, 'code' => '请勿重复领取');
            return json_encode($data);
        }

        // 启动事务
        Db::startTrans();
        try {
            $result=Db::table('draw_corporation')->insert($dataArray);
            if(!$result){
                // 回滚事务
                $data = array('status' => 0, 'code' => '领取失败，内部问题');
                return json_encode($data);
            }else{
                // 提交事务
                Db::commit();
                $data = array('status' => 1, 'code' => '领取成功');
                return json_encode($data);
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
    }

}