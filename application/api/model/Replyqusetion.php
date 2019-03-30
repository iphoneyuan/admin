<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/14
 * Time: 17:26
 */

namespace app\api\model;


use think\Db;
use think\Model;

class Replyqusetion extends Model
{
    //查询一条问题的回复详情
    public function getResponse($id){
        $result=Db::table('replyqusetion')
            ->alias('a')
            ->join('prizegiving b','a.prizegiving_prizegivingId=b.prizegivingId')
            ->where('prizegiving_prizegivingId',$id)
            ->select();
        return json_encode($result);

    }
    //当该问题发布者按下该条信息的感谢按钮时候对该用户进行积分的相对应的操作
    public function thanksquestion($id){
     $all=Db::table('replyqusetion')
           ->alias('a')
           ->join('prizegiving b','a.prizegiving_prizegivingId=b.prizegivingId')
           ->where('replyQusetionId',$id)
           ->field('a.user_userId,intergrationRequire')
           ->find();
        //扣除该用户相应的积分
        payint($all['intergrationRequire']);
        //接受者接受
        getint($all);
        $data=array('status' => 1, 'code'=>'感谢成功');
        return json_encode($data);
    }

}