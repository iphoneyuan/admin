<?php

namespace app\wechat\controller;

use service\DataService;

use think\db;
use think\Controller;
use controller\BasicAdmin;

class Keys extends BasicAdmin
{

    /**
     * 指定当前数据表
     * @var string
     */
    public $table = 'assignment';

    /**
     * 显示关键字列表
     */
    //渲染领取列表
    public function index()
    {
        $this->assign('title', '领取列表');
        $lists = Db::table("Draw_Assignment")
            ->alias('a')
            ->join('user_address b ','b.user_userId=a.user_userId','LEFT')
            ->join('assignment c','a.assignment_assignmentId=c.AssignmentId')
            ->order('isTop asc')
            ->paginate(15);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $lists->render());
        $list=$lists->items();
        foreach ($list as $kk=>$value){
            $list[$kk]["countdata"]=date('d',$list[$kk]["countdata"]);
            $list[$kk]["enddata"]=date('Y-m-d',$list[$kk]["enddata"]);
        }

        $this->assign('page',$page);
        $this->assign('list',$list);
        return $this->fetch('keys/index');
    }
    //任务列表
   public function assignment(){
        $get = $this->request->get();
        $this->assign('title','任务列表');
        $lists=Db::table('assignment')
            ->alias('a')
            ->join('user_address b','b.user_userId=a.user_userId','LEFT' );

       if(!empty($get['word'])&&!empty($get['person'])){
           $lists->where('word','like',"%".$get['word']."%");
           $lists->where('b.name',$get['person']);
       }elseif(!empty($get['word'])){
           $lists->where('word','like',"%".$get['word']."%");
       }elseif (!empty($get['person'])){
           $lists->where('b.name',$get['person']);
       }
       $lists=$lists->paginate(20);
       $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $lists->render());
       $list=$lists->items();
       foreach ($list as $kk=>$value){
            $list[$kk]["countdata"]=date('d',$list[$kk]["countdata"]);
            $list[$kk]["enddata"]=date('Y-m-d',$list[$kk]["enddata"]);
        }

       $this->assign('page',$page);
        $this->assign('list',$list);
       return $this->fetch('assignment');
   }
  //对某条记录进行删除操作
   public function delassignment(){
       $id= $this->request->post('id');
       $draw_assignment=Db::table('Draw_Assignment')->where('assignment_assignmentId',$id)->find();
       $assign_comment=Db::table('assign_comment')->where('assignment_assignmentId',$id)->find();
       if($draw_assignment||$assign_comment){
           $this->error("该条任务已被留言或者被领取，暂无法删除",'');
       }else {
           $result = Db::table('assignment')->where('assignmentId', $id)->delete();
           if ($result) {
               $this->success("成功删除该条任务信息！", '');
           } else {
               $this->error("删除失败，请稍后再试！", '');
           }
       }
   }
   //对领取记录进行删除操作
   public function delDrawAssignment(){
        $id=$this->request->post('id');
        $result=Db::table('Draw_Assignment')->where('drawId',$id)->delete();
        if($result){
            $this->success("成功对该条领取记录进行删除任务",'');
        }else{
            $this->error("删除失败，请稍后再试！", '');
        }
   }

//跳转到投诉详情，并对数据进行渲染
    public function add()
    {
        $this->title = '投诉详情';
       $db=Db::table('assignment')
           ->alias('a')
           ->join('Draw_Assignment b ','b.assignment_assignmentId= a.AssignmentId','LEFT')
           ->join('user_address c ','c.user_userId=a.user_userId','LEFT')
           ->where('isTop','=','1')
           ->paginate(20);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $db->render());

        $this->assign('page',$page);
        $this->assign('db',$db);
        return $this->_form($this->table, 'form');

    }
//对投诉信息进行放行，状态改为1
    public function fangxing(){
       $a=input("id");
       $result=Db::table('Draw_Assignment')
           ->where('drawId',$a)
           ->update([
               'result'=>'1'
           ]);
       if($result){
           $this->success('成功放行','');
       }else{
           $this->error("放行失败，请稍后再试！", '');
       }
    }
//投诉信息状态改为2
    public function pauses(){
       $b=input("id");
       $result=Db::table('Draw_Assignment')
           ->where('drawId',$b)
           ->update([
               'result'=>'2'
           ]);
        if($result){
            $this->success('成功禁用','');
        }else{
            $this->error("禁用失败，请稍后再试！", '');
        }
    }

}
