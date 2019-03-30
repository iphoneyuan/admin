<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/19
 * Time: 16:59
 */

namespace app\wechat\controller;


use think\Controller;
use think\Db;

class Question extends Controller
{
    //index页面
public function index(){
    $get = $this->request->get();
    $result1=Db::table('prizegiving')
        ->alias('a')
        ->where('a.delete',0)
        ->join('user_address b','a.user_userId=b.user_userId');

    if(!empty($get['question'])&&!empty($get['person'])){
         $result1->where('question',$get['question']);
         $result1->where('b.name',$get['person']);
    }elseif(!empty($get['question'])){
        $result1->where('question',$get['question']);
    }elseif (!empty($get['person'])){
        $result1->where('b.name',$get['person']);
    }

    $result=$result1->paginate(15);
    $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
    $this->assign('page',$page);
    $this->assign('question',$result);
    return $this->fetch('index');
}
//回收站
public function recycle(){
    $result=Db::table('prizegiving')
        ->alias('a')
        ->join('user_address b','a.user_userId=b.user_userId')
        ->where('a.delete',1)
        ->paginate(15);
    $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
    $this->assign('page',$page);
    $this->assign('question',$result);
    return $this->fetch('question/recycle');
}


    //修改页面
public function revice(){
    $result=Db::table('prizegiving')
        ->alias('a')
        ->join('replyqusetion b','a.prizegivingId=b.prizegiving_prizegivingId')
        ->join('user_address c','a.user_userId=c.user_userId')
        ->paginate(15);
    $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
    $this->assign('page',$page);
    $this->assign('reply',$result);
    return $this->fetch('revice');
}

    //删除问题信息
    public function del(){
        $id= $this->request->post('id');
        $check=Db::table('replyqusetion')->where('prizegiving_prizegivingId',$id)->find();
        if($check){
            $this->error("该问题已有回复，无法删除");
        }
        $result=Db::table('prizegiving')->where('prizegivingId',$id)->delete();
        if($result){
            $this->success("成功删除该条问题信息！", '');
        }else{
            $this->error("删除失败，请稍后再试",'');
        }
    }

    //软删除
    public function ruandel(){
    $id=$this->request->post('id');
    $result=Db::table('prizegiving')->where('prizegivingId',$id)->update(['delete'=>1]);
        if($result){
            $this->success("成功删除该条问题信息！", '');
        }else{
            $this->error("删除失败，请稍后再试",'');
        }
    }

    //删除评论信息
    public function delrevice(){
        $id= $this->request->post('id');
        $result=Db::table('replyQusetion')->where('replyQusetionId',$id)->delete();
        if($result){
            $this->success("成功删除该条评论信息！", '');
        }else{
            $this->error("删除失败，请稍后再试",'');
        }
    }
//批量软删除
   public function  delruanquestionall(){
       $question_id=input('question_id');
       $result=Db::table('prizegiving')->where('prizegivingId','in',$question_id)->update(['delete'=>1]);
       if($result){
           return ['error_code'=>1,'msg'=>'删除成功'];
       }else{
           return ['error_code'=>0,'msg'=>'删除失败'];
       }
   }
   //还原操作
    public function restore(){
        $id= $this->request->post('id');
        $result=Db::table('prizegiving')->where('prizegivingId',$id)->update(['delete'=>0]);
        if($result){
            $this->success("成功还原该条问题信息！", '');
        }else{
            $this->error("还原失败，请稍后再试",'');
        }
    }

    //批量删除
    public function delquestionall(){
        $theme_id=input('theme_id');
        $check=Db::table('replyqusetion')->where('prizegiving_prizegivingId','in',$theme_id)->find();
        if($check){
            return ['error_code'=>0,'msg'=>'尚有问题已有回复，无法删除'];
        }
        $result=Db::table('prizegiving')->where('prizegivingId','in',$theme_id)->delete();
        if($result){
            return ['error_code'=>1,'msg'=>'删除成功'];
        }else{
            return ['error_code'=>0,'msg'=>'删除失败'];
        }

    }

}