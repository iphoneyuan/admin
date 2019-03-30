<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/12/26
 * Time: 11:11
 */

namespace app\wechat\controller;


use controller\BasicAdmin;
use think\Db;

class Press extends BasicAdmin
{
   //新闻资讯
    public function index(){
        $result=Db::table('news')->select();
        $this->assign('db',$result);
        return $this->fetch('index');

    }

    //修改新闻资讯
    public function edit(){
        $id= input('id');
        $result=Db::table('news')->where('newId',$id)->find();
        $this->assign('db',$result);
        return $this->fetch('edit');
    }

    //更改资讯
    public function replace(){
        $newId=input('newId');
        $word=input('word');
        $result=Db::table('news')->where('newId',$newId)->update(['word'=>$word]);
        if($result){
             $this->success('修改成功','');
        }else{
             $this->error('修改失败','');
        }
    }


}