<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/20
 * Time: 11:12
 */

namespace app\wechat\controller;


use controller\BasicAdmin;
use think\Controller;
use think\Db;

class Banner extends BasicAdmin
{
    public function index(){
        $result=Db::table('banner_item')
            ->alias('a')
            ->join('banner b','b.bannerId=a.banner_bannerId')
            ->select();
        $this->assign('help',$result);
        return $this->fetch('index');
    }

    public function edit()
    {
        $id= input('id');
        $db=Db::table('banner_item')
            ->alias('a')
            ->join('banner b','b.bannerId=a.banner_bannerId')
            ->where('id',$id)
            ->find();
        $this->assign('db',$db);
        return $this->_form('banner_item', 'revice');
    }

    //主题表单修改
    public function addg(){
        $result=$this->request->post();
        $file = request()->file('image');
        if(empty($file)=='1'){
            $end=Db::table('banner_item')->update($result);
            if($end){
                $this->success("信息修改成功");
            }else{
                $this->error("修改失败");
            }
        }else{
            $info = $file->move(ROOT_PATH . 'static/image/banner');
            // 成功上传后 获取上传信息
            $a=$info->getSaveName();
            $imgp= str_replace("\\","/",$a);
            $imgpath='/image/banner/'.$imgp;
            $result['img_url']= $imgpath;
            $end=Db::table('banner_item')->update($result);
            if($end){
                $this->success("信息修改成功");
            }else{
                $this->error("上传失败");
            }
        }
    }

}