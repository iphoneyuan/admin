<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/12/11
 * Time: 15:09
 */

namespace app\wechat\controller;


use controller\BasicAdmin;
use think\Db;


class User extends BasicAdmin
{
   public function index(){
         $result=Db::table('user')
             ->alias('a')
             ->join('user_address b','a.userId=b.user_userId')
             ->order('id desc')
             ->where('delete',0)
             ->select();
         $this->assign('db',$result);
         return $this->fetch('user/index');
   }

   public function dels(){
       $id= $this->request->post('id');
       $result=Db::table('user_address')->where('user_userId',$id)->update(['delete'=>1]);
       if($result){
        $this->success('成功注销该学生的信息','');
       }else{
           $this->error('还原学生信息失败','');
       }
   }

   public function recycle(){
       $result=Db::table('user')
           ->alias('a')
           ->join('user_address b','a.userId=b.user_userId')
           ->where('delete',1)
           ->select();
       $this->assign('db',$result);
       return $this->fetch('user/recycle');
   }

   public function revice(){
       $id= $this->request->post('id');
       $result=Db::table('user_address')
           ->where('user_userId',$id)
           ->update(['delete'=>0]);
       if($result){
           $this->success('成功还原该学生的信息','');
       }else{
           $this->error('还原学生信息失败','');
       }
   }

   public function del(){
       $id= $this->request->post('id');
       $assignment=Db::table('assignment')->where('user_userId',$id)->find();
       $assign_comment=Db::table('assign_comment')->where('user_userId',$id)->find();
       $commodity=Db::table('commodity')->where('user_userId',$id)->find();
       $complain=Db::table('complain')->where('user_userId',$id)->find();
       $draw_assignment=Db::table('Draw_Assignment')->where('user_userId',$id)->find();
       $draw_commodity=Db::table('draw_commodity')->where('user_userId',$id)->find();
       $good_comment=Db::table('good_comment')->where('user_userId',$id)->find();
       $leading=Db::table('leading')->where('user_userId',$id)->find();
       $prizegiving=Db::table('prizegiving')->where('user_userId',$id)->find();
       $replyqusetion=Db::table('replyqusetion')->where('user_userId',$id)->find();

       if( $assignment||$assign_comment||$commodity||$complain||$draw_assignment||$draw_commodity||$good_comment||$leading||$prizegiving||$replyqusetion) {
           $this->error('该用户绑定有其他任务，尚无法删除','');
       }else{
           $result = Db::table('user_address')->where('user_userId', $id)->delete();
           $resultt = Db::table('user')->where('userId', $id)->delete();
           if ($result && $resultt) {
               $this->success("成功删除该条人员信息！", '');
           } else {
               $this->error('删除失败，请稍后再试！', '');
           }
       }

   }


}