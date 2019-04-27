<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/14
 * Time: 17:09
 */

namespace app\api\model;


use think\Model;

class Prizegiving extends Model
{
    //一对多，关联user表
    public function user(){
        return $this->belongsTo('User','user_userId','userId');
    }
    //获取所有问题的信息
    public function prizegivingAll(){
        $timebegin=time()-1209600;
        $timeend=time();
        $prizegivingAll=self::with(['user','user.UserAddress'])->order('createtime','desc')->where('createtime','between',[$timebegin,$timeend])->where('delete',0)->select();
        foreach ( $prizegivingAll as $value){
            $value['createtime']=date("Y-m-d H:i:s",$value['createtime']);
        }
        return json_encode($prizegivingAll);
    }
    //获取单一问题的信息
    public function prizegivingById($id){
        $prizegivingById=self::with(['user','user.UserAddress'])->find($id);
        return $prizegivingById;
    }

}