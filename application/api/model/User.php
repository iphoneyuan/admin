<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/12
 * Time: 16:01
 */

namespace app\api\model;


use app\lib\exception\CheckDrawException;
use app\lib\exception\CheckSureException;
use app\lib\exception\CommodityException;
use app\lib\exception\IsCommodityException;
use app\lib\exception\IsPersonException;
use app\lib\exception\IsQusetionException;
use think\Cache;
use think\Db;
use think\Model;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use think\Session;
use think\Request;

class User extends Model
{
    protected $hidden=['create_time','delete_time','update_time','openId'];
    //一对多，任务表
    public function Assignment() {
        return $this->hasMany ( 'Assignment', 'user_userId', 'userId' )->order('enddata','desc'); // 关联模型的模型名 关联模型的模型外键  本模型主键
    }
    //一对多，投诉表
    public function Complain(){
        return $this->hasMany('Complain','user_userId','userId');  //关联模型的模型名 外键 主键
    }

    //一对一，个人详情表
    public function UserAddress(){
        return $this->hasOne("UserAddress","user_userId","userId");
    }
    //一对多，问题表
    public function Prizegiving(){
        return $this->hasMany('Prizegiving','user_userId','userId');
    }
    //一对一，部门管理层人员表
    public function Leading(){
        return $this->hasOne('Leading','user_userId','userId');
    }
    //一对多，一个用户可以有多个评论
    public function Replyquestion(){
        return $this->hasMany('Replyqusetion','user_userId','userId');
    }
    //一对多，一个商家可以发布多个商品
    public function Commodity(){
        return $this->hasMany('commodity','user_userId','userId');
    }

    //获取一个人预订的商品信息
    public function revicecommodity(){
        return $this->hasMany('DrawCommodity','user_userId','userId');
    }

    //获取一个人的所有任务信息
    public function getUserAssignmentById($id){
        $assignment=self::with(['Assignment','UserAddress'])->find($id);
        $all=$assignment['assignment'];
        foreach($all as $kk=>$value){
            if($all[$kk]["enddata"]>time()){
                $all[$kk]["overtime"]=0;  //已失效
            }else{
                $all[$kk]["overtime"]=1;  //未失效
            }

        }
        return json_encode($all);
    }

    //上传个人信息
    public static function UpPersonal($students){
        $result=Db::table('user_address')->insert($students);
        if($result){
            $data=array('error_code' => 1, 'msg'=>'个人信息插入成功');
            return $data;
        }else{
            $data=array('erroe_code' => 0, 'msg'=>'数据插入失败，内部问题');
            return $data;
        }
    }

    //上传单个任务的详细信息
    public function UpAssignment($upassignment){
        $result=Db::table('assignment')->insert($upassignment);
       if($result){
           $data=array('error_code' => 1, 'msg'=>'数据插入成功');
           return json_encode($data);
       }else{
           $data=array('error_code' => 0, 'msg'=>'数据插入失败，内部问题');
           return json_encode($data);
       }
    }
    //获取单个人的所有问题
    public function getPrizegivingById($id){
        $prizegivingById=self::with(['UserAddress','Prizegiving'])->find($id);
        return $prizegivingById;
    }
    //上传用户的投诉信息
    public function UpComplain($upcomplain){
       $result=Db::table('complain')->insert($upcomplain);
         if($result){
            $data=array('error_code' => 1, 'msg'=>'投诉成功');
            return json_encode($data);
        }else{
            $data=array('error_code' => 0, 'msg'=>'投诉失败');
            return json_encode($data);
        }
    }

    public function UpQuestionComplain($upcomplain){
        $result=Db::table('question_complain')->insert($upcomplain);
        if($result){
            $data=array('error_code' => 1, 'msg'=>'投诉成功');
            return json_encode($data);
        }else{
            $data=array('error_code' => 0, 'msg'=>'投诉失败');
            return json_encode($data);
        }
    }

    //获取一个用户所有领取的任务信息
    public function  getUserDrawAssignment($uid){
     $result=Db::table("Draw_Assignment")->alias('a')
         ->join("assignment b",'a.assignment_assignmentId=b.assignmentId')
         ->where('a.user_userId',$uid)
         ->select();
     return json_encode($result);
    }
    //获取一个用户领取任务的详情
   public function getUserDrawAssignmentDetail($id){
       $result=Db::table("Draw_Assignment")->alias('a')
           ->join("assignment b",'a.assignment_assignmentId=b.assignmentId')
           ->find($id);
       $result['countdata']=date('d',$result['countdata']);
       $result['enddata']=date('Y-m-d',$result['enddata']);
       return json_encode($result);
   }

    //上传问题
    public function  UpPrizegivingById($all){
        $result=Db::table('prizegiving')->insert($all);
        if($result){
            $data=array('status' => 1, 'code'=>'数据插入成功');
            return json_encode($data);
        }else{
            $data=array('status' => 0, 'code'=>'数据插入失败，内部问题');
            return json_encode($data);
        }
    }

    //获取一个用户评论的所有用户
    public function getReplyQuestionById($id){
       $replyquestionById=self::with('Replyquestion')->find($id);
       return $replyquestionById;
    }


    //获取一个用户发布的所有商品信息
    public function  getCommodityById($id){
       $commodityById=self::with('commodity')->find($id);
       return $commodityById;
    }

    //上传一个用户回答的问题
    public function UpReplyQuestionById($all){
       $uid=Db::table('prizegiving')->where('prizegivingId',$all['prizegiving_prizegivingId'])->find();

        if($uid['user_userId']==$all['user_userId']){
            throw new IsQusetionException();
        }

        $result=Db::table('replyqusetion')->insert($all);
        if($result){
            $data=array('error_code' => 1, 'msg'=>'评论成功');
            return json_encode($data);
        }else{
            $data=array('error_code' => 0, 'msg'=>'评论失败，内部问题');
            return json_encode($data);
        }
    }



    //添加商品信息
    public function UpShopping($all){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $all['user_userId']=$uid;
        if($all['count']=='0') {
         $result = Db::table('commodity')->insertGetId($all);
         return $result;
        }else{
            $id=Cache::get('primarykey');
            $result=Db::table('commodity')->where('goodId',$id)->update($all);
            if ($result) {
                $data = array('error_code' => 1, 'msg' => '商品添加成功','count'=>$all['count']);
                return json_encode($data);
            } else {
                $data = array('error_code' => 0, 'msg' => '商品添加失败，内部问题');
                return json_encode($data);
            }
        }
    }

   //获取一个用户领取的任务
     public function reviceCommodityById($id){
        $result=self::with(['revicecommodity','revicecommodity.retailCommodity'])->find($id);
        return json_encode($result);
     }

   //用户预订商品
    public function SubscribeShopping($all){
        $checkuser=Db::table('commodity')->where("goodId",$all['commodity_commodityId'])->field('user_userId')->find();
        if($checkuser['user_userId']==$all['user_userId']){
           throw new IsCommodityException();
        }
        $checkshopping=Db::table('draw_commodity')->where('commodity_commodityId',$all['commodity_commodityId'])->where('user_userId',$all['user_userId'])->find();
        if($checkshopping){
            throw new CommodityException();
        }
    $result=Db::table('draw_commodity')->insert(['commodity_commodityId'=>$all['commodity_commodityId'],'user_userId'=>$all['user_userId']]);
        if ($result) {
            $data = array('error_code' => 1, 'msg' => '商品预订成功');
            return json_encode($data);
        } else {
            $data = array('error_code' => 0, 'msg' => '商品预订失败，内部问题');
            return json_encode($data);
        }
    }


   //社团注册
    public function  UpCommodityTask($all){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $all['user_userId']=$uid;
        $result=Db::table('leading')->insert($all);
        if($result){
            $data=array('status' => 1, 'code'=>'社团注册成功');
            return json_encode($data);
        }else{
            $data=array('status' => 0, 'code'=>'数据插入失败，内部问题');
            return json_encode($data);
        }
    }
    //获取数据表中的openid
    public static function getByOpenID($openid){
        $user=self::where('openId','=',$openid)
            ->find();
        return $user;
    }
    //获取数据表中的use
    public static function getUser($userId){
        $user=self::where('userId','=',$userId)
            ->find();
        return $user;
    }

   public function UpCommunityMission($all){
        $result=Db::table('corporation')->insert($all);
       if($result){
           $data=array('status' => 1, 'code'=>'社团任务发布成功');
           return json_encode($data);
       }else{
           $data=array('status' => 0, 'code'=>'数据插入失败，内部问题');
           return json_encode($data);
       }
   }

   //用户确认任务已经完成
   public function UserSurefinish($all){
        $result=Db::table('Draw_Assignment')
            ->where('drawId',$all['assignment_assignmentId'])
            ->where('user_userId',$all['user_userId'])
            ->update(['drawfinish'=>'1']);
       if($result){
           $data=array('error_code' => 1, 'msg'=>'确认成功');
           return json_encode($data);
       }else{
           $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
           return json_encode($data);
       }
   }

   //发布者确认用户完成任务
    public function PublicSurefinish($all){
       //检查领取者是否已确认完成
        $check=Db::table('Draw_Assignment')
            ->where('assignment_assignmentId',$all['assignment_assignmentId'])
            ->where('user_userId',$all['user_userId'])
            ->field('drawfinish')
           ->find();
       if($check['drawfinish']=='0'){
           throw new CheckDrawException();
       }

        $result=Db::table('Draw_Assignment')
            ->where('assignment_assignmentId',$all['assignment_assignmentId'])
            ->where('user_userId',$all['user_userId'])
            ->update(['publicfinish'=>1]);

        $finish=Db::table('assignment')
            ->where('assignmentId',$all['assignment_assignmentId'])
            ->update(['assign_finish'=>1]);

        if($result&&$finish){
            //进行积分的扣除
            //查询相关任务所需的积分
            $intergration=Db::table('assignment')
                ->where('assignmentId',$all['assignment_assignmentId'])
                ->field('intergrationRequire')
                ->find();

            //扣除相应的积分
            payint($intergration['intergrationRequire']);
            $intergration['user_userId']=$all['user_userId'];
            //把相应的积分给对方
            getint($intergration);


            $data=array('error_code' => 1, 'msg'=>'确认成功');
            return json_encode($data);
        }else{
            $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
            return json_encode($data);
        }
    }
//发布者确定预订人
     public function commoditypersonsure($all){
         $suregood=Db::table('commodity')->where('goodId',$all['commodityId'])->update(['comm_sure'=>1]);
         if($suregood){
             $sure=Db::table('draw_commodity')->where('commodity_commodityId',$all['commodityId'])->update(['public_sure'=>1]);
             if($sure){
                 $sureperson=Db::table('draw_commodity')->where('commodity_commodityId',$all['commodityId'])->where('user_userId',$all['user_userId'])->update(['public_sure'=>2]);
                 if($sureperson){
                     $data=array('error_code' => 1, 'msg'=>'确认成功');
                     return json_encode($data);
                 }else{
                     $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
                     return json_encode($data);
                 }
             }else{
                 $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
                 return json_encode($data);
             }
         }else{
             $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
             return json_encode($data);
         }

    }
    //商品发布者确定交易已经结束
    public function surefinishCommodity($all){
        $check=Db::table('draw_commodity')
            ->where('commodity_commodityId',$all['commodityId'])
            ->where('user_userId',$all['user_userId'])->find();

        if($check['draw_finish']!=1){
            throw new CheckDrawException();
        }
        if($check['public_sure']!=2){
            throw new CheckSureException();
        }
        $result=Db::table('draw_commodity')
            ->where('commodity_commodityId',$all['commodityId'])
            ->where('user_userId',$all['user_userId'])->update(['public_finish'=>1]);
        if($result){
            $finish=Db::table('commodity')->where('goodId',$all['commodityId'])->update(['comm_finish'=>1]);
            if($finish){
                $data=array('error_code' => 1, 'msg'=>'确认成功');
                return json_encode($data);
            }else{
                $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
                return json_encode($data);
            }
        }else{
            $data=array('error_code' => 0, 'msg'=>'确认失败，内部问题');
            return json_encode($data);
        }
    }


//获取个人社团任务信息
   public function getUserTeamWork($uid){
    $result=Db::table('draw_corporation')->alias('a')
            ->join('corporation b','b.corporationId=a.corporation_id')
            ->where('a.user_userId',$uid)
            ->select();

    return json_encode($result);
   }


}