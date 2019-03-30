<?php


namespace app\wechat\controller;

use controller\BasicAdmin;
use service\LogService;
use service\ToolsService;
use service\WechatService;
use think\Db;
use think\Request;

/**
 * 微信粉丝管理
 * Class Fans
 * @package app\wechat\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Fans extends BasicAdmin
{

    /**
     * 定义当前默认数据表
     * @var string
     */
    public $table = 'WechatFans';

    /**
     * 显示粉丝列表
     * @return array|string
     */
    public function index()
    {
        $this->title = '社团任务管理';
        $result=Db::table('corporation')
            ->alias('a')
            ->join('leading b','a.leading_id=b.id')
            ->select();
        $this->assign('list',$result);
        return $this->fetch('fans/index');
    }

   //删除该条信息
    public function del(){
        $id= $this->request->post('id');
        $result=Db::table('corporation')->where('corporationId',$id)->delete();
        if($result){
            $this->success("成功删除该条主题信息！", '');
        }else{
            $this->error('删除失败，请稍后再试！','');
        }
    }
  //查看详情信息
    public function edit()
    {
        $id= input('id');
        $db=Db::table('corporation')->where('corporationId','=',$id)->find();
        $this->assign('db',$db);
        return $this->_form('corporation', 'revice');
    }

    //人员审核
    public function Auditing(){
        $result=Db::table('leading')->select();
        $this->assign('db',$result);
      return $this->fetch('fans/Auditing');
    }


   //审核通过
    public function fangxing(){
        $id=input('id');
        $result=Db::table('leading')->where('id',$id)->update(['admin_sure'=>1]);
        if($result){
            $this->success('审核状态修改成功','');
        }else{
            $this->error('审核状态修改失败，请稍后再试！','');
        }
    }

    //活动须知
    public function Notice(){
        $result=Db::table('notice')->find();
        $this->assign('db',$result);
       return $this->fetch('fans/Notice');
    }

    //修改活动须知
    public function revice(){
        $data=$this->request->post();
        $result=Db::table('notice')->where('noticeId',1)->update($data);
        if($result){
            $this->success('活动须知修改成功','');
        }else{
            $this->error('活动须知修改失败','');
        }

    }

}
