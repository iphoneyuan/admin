<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/15
 * Time: 18:16
 */

namespace app\api\model;


use think\Db;
use think\Model;

class Commodity extends Model
{
    protected $hidden=['delete_time','update_time','create_time'];
    //一对多 ，关联user表
    public function Useritem(){
        return $this->belongsTo('User','user_userId','userId');
    }
    //获取所有的表
    public function getCommodityById(){
        $commodityById=self::with(['Useritem','Useritem.UserAddress'])->where('comm_finish',0)->where('delete',0)->where('comm_sure',0)->select();
        return json_encode($commodityById);
    }

    //获取单个商品的详细信息
    public function getCommodityDetail($id){
        $commoditydetail=self::with(['Useritem','Useritem.UserAddress'])->find($id);
        return json_encode($commoditydetail);
    }
    //获取某类的商品信息
    public function  getCommodityByOrder($type_id){
        $commodityById=self::with(['Useritem','Useritem.UserAddress'])->where('type',$type_id)->where('comm_finish',0)->where('delete',0)->where('comm_sure',0)->select();
        return json_encode($commodityById);
    }

    //点赞功能
    public function thumb($id){
        $like_number=Db::table('commodity')
            ->field('like_number')
            ->find($id);
        $like_number['like_number']=$like_number['like_number']+1;
        $result=Db::table('commodity')
            ->where('goodId',$id)
            ->update(['like_number'=>$like_number['like_number'],'like'=>1]);

        if($result){
            $data=array('status' => 1, 'code'=>'点赞成功');
            return json_encode($data);
        }else{
            $data=array('status' => 0, 'code'=>'点赞失败，内部问题');
            return json_encode($data);
        }
    }

}