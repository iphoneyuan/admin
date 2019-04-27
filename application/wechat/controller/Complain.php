<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/20
 * Time: 0:17
 */

namespace app\wechat\controller;


use think\Controller;
use think\Db;

class Complain extends Controller
{
    public function index(){
        $get = $this->request->get();
        $result1=Db::table('complain')
            ->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId')
            ->join('assignment c','a.assignment_assignmentId=c.assignmentId')
            ->order('result asc');

        if(!empty($get['word'])&&!empty($get['person'])){
            $result1->where('complainWord','like',"%".$get['word']."%");
            $result1->where('b.name',$get['person']);
        }elseif(!empty($get['word'])){
            $result1->where('complainWord','like',"%".$get['word']."%");
        }elseif (!empty($get['person'])){
            $result1->where('b.name',$get['person']);
        }

        $result=$result1->paginate(15);
        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('Complain',$result);
        $this->assign('page',$page);
         return $this->fetch('index');
   }
   public function question_index(){
       $get = $this->request->get();
           $result1=Db::table('user_address')
           ->alias('a')
           ->join('question_complain b','a.user_userId=b.user_userId')
           ->join('prizegiving c','b.question_id=c.prizegivingId')
           ->order('result asc');

       if(!empty($get['word'])&&!empty($get['person'])){
           $result1->where('complainWord','like',"%".$get['word']."%");
           $result1->where('b.name',$get['person']);
       }elseif(!empty($get['word'])){
           $result1->where('complainWord','like',"%".$get['word']."%");
       }elseif (!empty($get['person'])){
           $result1->where('b.name',$get['person']);
       }

       $result=$result1->paginate(15);
       $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
       $this->assign('question',$result);
       $this->assign('page',$page);
       return $this->fetch('question_index');
   }


    //对投诉信息进行放行，状态改为1
    public function fangxing(){
        $a=input("id");
        // 启动事务
            Db::startTrans();
            try{
        $result=Db::table('complain')->where('complainId',$a)->update(['result'=>'1']);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if($result){
            $this->success('成功放行','');
        }else{
            $this->error("请勿重复放行！", '');
        }
    }
//投诉信息状态改为2
    public function pauses(){
        $b=input("id");
      // 启动事务
        Db::startTrans();
        try{
        $result=Db::table('complain')->where('complainId',$b)->update(['result'=>'2']);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if($result){
            $this->success('成功禁用','');
        }else{
            $this->error("请勿重复禁用", '');
        }
    }

    //对投诉信息进行放行，状态改为1
    public function fangxing_question(){
        $a=input("id");
        // 启动事务
        Db::startTrans();
        try{
            $result=Db::table('question_complain')->where('id',$a)->update(['result'=>'1']);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if($result){
            $this->success('成功放行','');
        }else{
            $this->error("请勿重复放行！", '');
        }
    }
//投诉信息状态改为2
    public function pauses_question(){
        $b=input("id");
        // 启动事务
        Db::startTrans();
        try{
            $result=Db::table('question_complain')->where('id',$b)->update(['result'=>'2']);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        if($result){
            $this->success('成功禁用','');
        }else{
            $this->error("请勿重复禁用", '');
        }
    }

}