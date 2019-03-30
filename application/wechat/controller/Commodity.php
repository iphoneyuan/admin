<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/12/6
 * Time: 11:26
 */

namespace app\wechat\controller;


use controller\BasicAdmin;
use think\Db;

class Commodity extends BasicAdmin
{
    public $title = '商品管理';

    public $table = '';


    protected $menuType = [
        'view' => '跳转URL',
        'click' => '点击推事件',
        'scancode_push' => '扫码推事件',
        'scancode_waitmsg' => '扫码推事件且弹出“消息接收中”提示框',
        'pic_sysphoto' => '弹出系统拍照发图',
        'pic_photo_or_album' => '弹出拍照或者相册发图',
        'pic_weixin' => '弹出微信相册发图器',
        'location_select' => '弹出地理位置选择器',
    ];

    /**
     * 显示列表操作
     */
    public function index()
    {
        $result=Db::table('commodity')
            ->where('delete',0)
            ->paginate(7);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('page',$page);
        $this->assign('db',$result);
        return $this->fetch('commodity/index');
    }
    //回收站页面
    public function recycle(){
        $result=Db::table('commodity')
            ->where('delete',1)
            ->paginate(7);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('page',$page);
        $this->assign('db',$result);
        return $this->fetch('commodity/recycle');
    }

    //软删除
    public function ruandel(){
        $id=$this->request->post('id');
        $result=Db::table('commodity')->where('goodId',$id)->update(['delete'=>1]);
        if($result){
            $this->success("成功回收该条商品信息！", '');
        }else{
            $this->error("删除失败！", '');
        }
    }

    public function revice(){
        $id=$this->request->post('id');
        $result=Db::table('commodity')->where('goodId',$id)->update(['delete'=>0]);
        if($result){
            $this->success("成功还原该条商品信息！", '');
        }else{
            $this->error("删除失败！", '');
        }
    }

    //删除商品信息
    public function del(){
        $id= $this->request->post('id');
        $assign_comment=Db::table('good_comment')->where('comm_commodity',$id)->find();
        $draw_commodity=Db::table('draw_commodity')->where('commodity_commodityId',$id)->find();
        if($assign_comment||$draw_commodity){
            $this->error('该条商品已被留言或订购，无法删除','');
        }else{
            $result=Db::table('commodity')->where('goodId',$id)->delete();
            if($result){
                $this->success("成功删除该条商品信息！", '');
            }else{
                $this->error("删除失败！", '');
            }
        }
    }
    //删除订单信息
    public function delorder(){
        $id=$this->request->post('id');
        $result=Db::table('draw_commodity')->where('id',$id)->delete();
        if($result){
            $this->success('成功删除该条订单信息','');
        }else{
            $this->error('删除失败！','');
        }
    }

  //商品详情
    public function edit()
    {
        $id= input('id');
        $db=Db::table('commodity')
            ->alias('a')
            ->join('user_address b','b.user_userId=a.user_userId')
            ->where('goodId',$id)
            ->find();
        $this->assign('db',$db);
        return $this->_form('banner_item', 'revice');
    }
    //订单信息
    public function order(){
        $result=Db::table('draw_commodity')
            ->alias('a')
            ->join('commodity b','a.commodity_commodityId=b.goodId','LEFT')
            ->join('user_address c','c.user_userId=a.user_userId','LEFT')
            ->field('a.id,name,goodName,public_sure,public_finish,draw_finish,price')
            ->paginate(20);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('page',$page);
        $this->assign('db',$result);
        return $this->fetch('commodity/order');
    }
}