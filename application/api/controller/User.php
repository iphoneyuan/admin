<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/12
 * Time: 12:57
 */

namespace app\api\controller;

use app\api\service\Token as TokenService;
use app\api\validate\AskComplain;
use app\api\validate\AskQuestion;
use app\api\validate\AssignmentNew;
use app\api\model\User as UserModel;
use app\api\validate\CheckCommodityTask;
use app\api\validate\CheckGetAssignmentComplain;
use app\api\validate\CheckMission;
use app\api\validate\CheckShoping;
use app\api\validate\QuestionNew;
use app\lib\enum\ScopeEnum;
use app\lib\exception\CheckphotoException;
use app\lib\exception\CheckTimeException;
use app\lib\exception\ForbiddenException;
use app\lib\exception\IsMemberException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Controller;
use think\Db;
use think\Request;
use think\Validate;

class User extends Controller
{

    //定义前置方法
    protected $beforeActionList=[
      'checkPrimaryScope'=>['only'=>'UpAssignment,getUserTeamWork,UpPrizegivingById,UpReplyQuestionById,getUserDrawAssignment,getUserDrawAssignmentDetail,UpCommodityTask,UpShopping']
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

    //获取一个用户的所有发布任务信息
    public function getUserAssignmentById(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $assignment=model('User')->getUserAssignmentById($uid);
        return $assignment;
    }
    //获取一个用户所有领取的任务信息
    public function getUserDrawAssignment(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $userassignment=model('User')->getUserDrawAssignment($uid);
        return $userassignment;
    }
    //获取一个用户领取任务的详情
    public function  getUserDrawAssignmentDetail($id){
        $result=model('User')->getUserDrawAssignmentDetail($id);
        return $result;
    }
    //获取一个用户领取的社团信息
    public function getUserTeamWork(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $getuserteamwork=model('User')->getUserTeamWork($uid);
        return $getuserteamwork;
    }


    //上传任务详细信息
    public function UpAssignment(){
        $validate=(new AssignmentNew());
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $dataArray=$validate->getDataByRule(input('post.'));
        $dataArray['user_userId']=$uid;
        $dataArray['createtime']=time();
        $dataArray['enddata']= $dataArray['enddata'].$dataArray['time'];
        $dataArray['enddata'] =strtotime($dataArray['enddata']);
        $dataArray['countdata']=$dataArray['countdata']*86400;
        if($dataArray['enddata']<time()){
             throw new CheckTimeException();
        }
         unset($dataArray['time']);
        $upassignmentById=model('User')->UpAssignment($dataArray);
        return $upassignmentById;
    }
    //获取一个用户的所有提出的问题
    public function getPrizegivingById(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $prizegiving=model('User')->getPrizegivingById($uid);
        return $prizegiving;
    }
    //提交用户上传的问题信息
    public function UpPrizegivingById(){
        $validate=(new QuestionNew());
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }

        $question=input('question');
        $intergrationRequire=input('intergrationRequire');
        $all=['question'=>$question,'intergrationRequire'=>$intergrationRequire,'user_userId'=>$uid,'createtime'=>time()];
        $upassignment=model('User')->UpPrizegivingById($all);

        return $upassignment;
    }
    //获取用户回答的问题
    public function getReplyQuestionById(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $replyquestion=model('User')->getReplyQuestionById($uid);
        return $replyquestion;
    }

    //提交用户回答的问题
    public function UpReplyQuestionById(){
        $validate=(new AskQuestion());
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $dataArray=$validate->getDataByRule(input('post.'));
        $dataArray['user_userId']=$uid;
        $upreplyQuestion=model('User')->UpReplyQuestionById($dataArray);
        return $upreplyQuestion;
    }
    //提交用户提出的投诉信息
    public function UpComplainById(){
       $validate=(new AskComplain());
       $validate->goCheck();
       $uid=TokenService::getCurrentUid();
       $user=UserModel::getUser($uid);
       if(!$user){
           throw new UserException();
       }
        $dataArray=$validate->getDataByRule(input('post.'));
        $dataArray['user_userId']=$uid;
        $upComplain=model('User')->UpComplain($dataArray);
        return $upComplain;
    }
    //提交用户回答的问题信息
    public function UpQuestionComplain(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $dataArray=input('post.');
        $dataArray['user_userId']=$uid;
        $upComplain=model('User')->UpQuestionComplain($dataArray);
        return $upComplain;
    }

    //获取一个用户所有的发布的商品信息
    public function getCommodityById(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $commodity=model('User')->getCommodityById($uid);
        return $commodity;
    }

    //获取一个用户所领取的商品信息
    public function reviceCommodityById(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $commodity=model('User')->reviceCommodityById($uid);
        return $commodity;
    }


    //社团注册
    public function UpCommodityTask(){
        $validate=(new CheckCommodityTask());
        $validate->goCheck();
        $file=request()->file("imgfile");
        if($file){
          $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'static/image/upcommodity');
          if($info) {
              // 成功上传后 获取上传信息
              $a = $info->getSaveName();
              $imgp = str_replace("\\", "/", $a);
              $imgpath = '/image/upcommodity/' . $imgp;
              $all = Request::instance()->post();
              $all['imageUrl'] = $imgpath;
              $upcommoditytask = model('User')->UpCommodityTask($all);
              return $upcommoditytask;
          }else{
              throw new CheckphotoException();
          }
      }
    }


    //商品上传
    public function UpShopping(){
       $validate=(new CheckShoping());
       $validate->goCheck();
       $all=Request::instance()->post();
       $files=request()->file("imgfile");
           $info=$files->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH.'static/image/upshopping');
           if($info){
               $a=$info->getSaveName();
               $imgp= str_replace("\\","/",$a);
               $imgpath='/image/upshopping/'.$imgp;
               $count=$all['count'];
               $all['image'.$count]=$imgpath;
               $result=model('User')->UpShopping($all);
               if(is_numeric($result)){
                Cache::set('primarykey',$result);
               }
               return $result;
           }else{
               throw new CheckphotoException();
           }
    }

    //用户线下预订
    public function  SubscribeShopping(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $all=Request::instance()->post();

        $all['user_userId']=$uid;
        $result=model('User')->SubscribeShopping($all);
        return $result;

    }
    //上传社团任务信息
    public function UpCommunityMission(){
        $validate=(new CheckMission());
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $checkmember=Db::table('leading')->where('admin_sure',1)->where('user_userId',$uid)->find();
        if(!$checkmember){
            throw new IsMemberException();
        }
        $all=Request::instance()->post();
        $all['leading_id']=$checkmember['id'];
        $imageUrls=Db::table('leading')->where('user_userId',$uid)->field('imageUrl')->find();
        $all['imageUrls']=$imageUrls['imageUrl'];
        $all['createtime']=time();
        $result=model('User')->UpCommunityMission($all);
        return $result;

    }

    //用户确定已完成任务
    public function UserSurefinish(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $assignmentId=Request::instance()->post();
        $assignmentId['user_userId']=$uid;
        $result=model('User')->UserSurefinish($assignmentId);
        return $result;
    }
    //发布者确认任务已经完成
    public function PublicSurefinish(){
        $all=Request::instance()->post();
        $result=model('User')->PublicSurefinish($all);
        return $result;
    }

    //任务留言功能
    public function UpDrawCommodity(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $db=Request::instance()->post();
        $db['user_userId']=$uid;
        $result=Db::table('assign_comment')->insert($db);
            if ($result) {
                $data = array('status' => 1, 'code' => '评论成功');
                return json_encode($data);
            } else {
                $data = array('status' => 0, 'code' => '评论失败，内部问题');
                return json_encode($data);
            }
    }
     //商品留言功能
    public function UpGoodCommodity(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $db=Request::instance()->post();
        $db['user_userId']=$uid;
        $result=Db::table('good_comment')->insert($db);
        if($result){
           $data=array('status'=>1,'code'=>'评论成功');
           return json_encode($data);
        }else{
           $data=array('status'=>0,'code'=>'评论失败，内部问题');
           return json_encode($data);
        }
    }
    //读取用户剩余积分
    public function GetIntegration(){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $result=Db::table('user_address')->where('user_userId',$uid)->field('intergrationAll')->find();
        return $result['intergrationAll'];
    }
    //投诉进行中的任务
    public function toushu(){
        $validate=(new CheckGetAssignmentComplain());
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $db=Request::instance()->post();
        $db['user_userId']=$uid;

        $result=Db::table('Draw_Assignment')
            ->where('drawId',$db['drawId'])
            ->update(['isTop'=>1,'TopWord'=>$db['word']]);

        if ( $result){
            $data=array('error_code'=>1,'msg'=>'投诉成功');
            return json_encode($data);
        }else{
            $data=array('error_code'=>0,'msg'=>'投诉失败');
            return json_encode($data);
        }
    }
    //获取商品预订人
    public function commodityperson($id){
        $result=Db::table('draw_commodity')->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId')
            ->where('a.commodity_commodityId',$id)
            ->select();
        return json_encode($result);
    }
    //发布者确认商品预订人
    public function commoditypersonsure(){
        $all=Request::instance()->post();
        $result=model('User')->commoditypersonsure($all);
        return $result;
    }
    //商品领取者确定已经完成交易
    public function sureGetCommodity($id){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $public_sure=Db::table('draw_commodity')->where('commodity_commodityId',$id)->where('user_userId',$uid)->where('public_sure',2)->find();
        if($public_sure){
           $result=Db::table('draw_commodity')->where('commodity_commodityId',$id)->where('user_userId',$uid)->update(['draw_finish'=>1]);
           if($result){
               $data=array('error_code'=>1,'msg'=>'确认成功');
               return json_encode($data);
           }else{
               $data=array('error_code'=>0,'msg'=>'确认失败');
               return json_encode($data);
           }
        }else{
            $data=array('error_code'=>0,'msg'=>'发布者尚未同意确认');
            return json_encode($data);
        }
    }

    //商品发布者确定交易已经结束
    public function surefinishCommodity(){
        $all=Request::instance()->post();
        $result=model('User')->surefinishCommodity($all);
        return $result;
    }



}