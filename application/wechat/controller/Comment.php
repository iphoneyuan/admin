<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/12/20
 * Time: 9:56
 */

namespace app\wechat\controller;


use controller\BasicAdmin;
use think\Db;

class Comment extends BasicAdmin
{
    //任务留言列表
     public function index(){
         $result=Db::table('assign_comment')->alias('a')
             ->join('user_address b','a.user_userId=b.user_userId','left')
             ->field('a.id,a.word,b.name')
             ->paginate(20);

         $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
         $this->assign('page',$page);
         $this->assign('db',$result);
         return $this->fetch('comment/index');

     }
    //删除商品信息
    public function del(){
        $id= $this->request->post('id');
        $result=Db::table('assign_comment')->where('id',$id)->delete();
        if($result){
            $this->success("成功删除该条评论信息！", '');
        }else{
            $this->error("删除失败！", '');
        }
    }
    //商品留言信息
    public function goodindex(){
        $result=Db::table('good_comment')->alias('a')
            ->join('user_address b','a.user_userId=b.user_userId','left')
            ->field('a.id,a.word,b.name')
            ->paginate(20);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('page',$page);
        $this->assign('db',$result);
        return $this->fetch('comment/goodcomm');
    }
    //删除商品评论信息
    public function delgoodcomm(){
        $id= $this->request->post('id');
        $result=Db::table('good_comment')->where('id',$id)->delete();
        if($result){
            $this->success("成功删除该条评论信息！", '');
        }else{
            $this->error("删除失败！", '');
        }
    }



}