<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/15
 * Time: 18:14
 */

namespace app\api\controller;

use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use think\Controller;
use think\Db;

class Commodity extends Controller
{
    protected  $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'getCommodityDetail']
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
    //获取所有商品信息
     public function getCommoditysById(){
        $commodityById=model('commodity')->getCommodityById();
        return $commodityById;
     }
   //获取一个商品的详情信息
    public function getCommodityDetail($id){
         $commodityDetail=model('commodity')->getCommodityDetail($id);
             return $commodityDetail;
    }

    //点赞功能
    public function thumb($id){
        $result=model('commodity')->thumb($id);
            return $result;
    }
    //获取所有的商品评论信息
    public function getComment(){
        $goodId=$this->request->post('id');
        $result=Db::table('good_comment')->alias('a')
                ->join('user_address b','a.user_userId=b.user_userId')
                ->where('comm_commodity',$goodId)
                ->select();
        return json_encode($result);
    }
    //获取该用户的状态信息
    public function getCommodityStatus($id){
        $uid=TokenService::getCurrentUid();
        $user=UserModel::getUser($uid);
        if(!$user){
            throw new UserException();
        }
        $result=Db::table('draw_commodity')->where('commodity_commodityId',$id)->where('user_userId',$uid)->find();
        return json_encode($result);
    }
}