<?php

namespace app\wechat\controller;

use controller\BasicAdmin;
use service\LogService;
use service\ToolsService;
use think\Controller;
use think\Db;
use think\File;
use think\Model;
use think\Request;
class Menu extends BasicAdmin
{

    public $title = '主题管理';

    public $table = 'theme';


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
        $get = $this->request->get();
        $result1=Db::table('theme')
            ->field('themeId,imageurl,title,begintime,endtime,address');

            	if (!empty($get["begindate"]) && !empty($get["enddate"])&&!empty($get["address"])) {
            	   $result1->where('begintime','>=',strtotime($get["begindate"]));
            	   $result1->where('endtime','<=',strtotime($get["enddate"])+86400);
            	   $result1->where('address','=',$get["address"]);

                }elseif(!empty($get["begindate"]) && !empty($get["enddate"])){
                    $result1->where('begintime','>=',strtotime($get["begindate"]));
                    $result1->where('endtime','<=',strtotime($get["enddate"])+86400);

                }elseif(!empty($get["begindate"]) && !empty($get["address"])){
                    $result1->where('begintime','>=',strtotime($get["begindate"]));
                    $result1->where('address','=',$get["address"]);

                }elseif(!empty($get["enddate"]) && !empty($get["address"])){
                    $result1->where('endtime','<=',strtotime($get["enddate"])+86400);
                    $result1->where('address','=',$get["address"]);

                }elseif (!empty($get["begindate"])){
                    $result1->where('begintime','>=',strtotime($get["begindate"]));

                }elseif (!empty($get["enddate"])){
                    $result1->where('endtime','<=',strtotime($get["enddate"])+86400);

                }elseif (!empty($get["address"])){
                    $result1->where('address','=',$get["address"]);

                }
                $result=$result1->paginate(9);
                $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
                $list=$result->items();
               foreach ($list as $kk=>$value){
                    $list[$kk]['begintime']=date('Y-m-d',$list[$kk]['begintime']);
                    $list[$kk]['endtime']=date('Y-m-d',$list[$kk]['endtime']);
                }

        $this->assign('page',$page);
        $this->assign('theme',$list);
        return $this->fetch('menu/index');
    }

    //添加主题文章信息页面
    public function createfile(){
        $result=Model('Menu')->theme();
        $this->assign('theme',$result);
        return $this->fetch('createfile');
    }

    //添加主题页面
    public function themeadd(){
        return  $this->fetch('add');
    }

    //主题文章信息列表
    public function filelist(){
        $result=Db::table('themedetail')
            ->paginate(15);
        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $result->render());
        $this->assign('page',$page);
        $this->assign('filelist',$result);
        return $this->fetch('filelist');
    }
//批量删除主题
   public function delthemeall(){
     $themeId=input('theme_id');
     $check=Db::table('themedetail')->where('theme_themeId',$themeId)->find();
     if(!$check){
         return ['error_code'=>0,'msg'=>'请先删除该主题下的文章'];
     }else{
     $result=Db::table('theme')->where('themeId','in',$themeId)->delete();
     if($result){
         return ['error_code'=>1,'msg'=>'删除成功'];
     }else{
         return ['error_code'=>0,'msg'=>'删除失败'];
     }
     }
   }

    public function image()
    {
        $_GET['rows'] = 18;
        $this->assign('field', $this->request->get('field', 'local_url'));
        return $this->_list(Db::name('WechatNewsMedia')->where('type', 'image'));
    }


    /**
     * 图文选择器
     * @return string
     */
    public function select()
    {
        return $this->index();
    }

//主题表单修改
    public function addg(){
        $result=$this->request->post();
        $file = request()->file('image');
        if(empty($file)=='1'){
            $end=Db::table('theme')->update($result);
            if($end){
                $this->success("信息修改成功");
            }else{
                $this->error("修改失败");
            }
        }else{
            $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'static/image/priving');
            if($info) {
                // 成功上传后 获取上传信息
                $a = $info->getSaveName();
                $imgp = str_replace("\\", "/", $a);
                $imgpath = '/image/priving/' . $imgp;
                $result['imageurl'] = $imgpath;
                $end = Db::table('theme')->update($result);
                if ($end) {
                    $this->success("信息修改成功");
                } else {
                    $this->error("上传失败");
                }
            }else{
                $this->error('图片上传失败','');
            }
        }
    }
//主题表单新增
    public function create(){
        $result=$this->request->post();
        $result['begintime']=strtotime($result['begintime']);
        $result['endtime']=strtotime($result['endtime']);
        $file = request()->file('image');
        if(empty($file)=='1'){
            $end=Db::table('theme')->insert($result);
            if($end){
                $this->success("信息修改成功");
            }else{
                $this->error("修改失败");
            }
        }else{
            $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'static/image/priving');
            if($info) {
                // 成功上传后 获取上传信息
                $a = $info->getSaveName();
                $imgp = str_replace("\\", "/", $a);
                $imgpath = '/image/priving/' . $imgp;
                $result['imageurl'] = $imgpath;
                $end = Db::table('theme')->insert($result);
                if ($end) {
                    $this->success("信息修改成功");
                } else {
                    $this->error("上传失败");
                }
            }else{
                $this->error('图片上传失败','');
            }
        }
    }
//添加主题文章信息
public function themefileadd(){
   $themefileadd=$this->request->post();
   $themefile=preg_replace('|[0-9a-zA-Z&;:/=-]|','',$themefileadd['themedetailWord']);
   $all=['themedetailTitle'=>$themefileadd['themedetailTitle'],'theme_themeId'=>$themefileadd['theme_themeId'],'themedetailWord'=>$themefile];
   $result=Db::table('themedetail')
       ->insert($all);
   if($result){
       $this->success("信息添加成功");
   }else{
       $this->error("信息添加失败");
   }
}
//修改主题文章信息
    public function themefilerevice(){
        $themefileadd=$this->request->post();
        $themefile=preg_replace('|[0-9a-zA-Z&;:/=-]|','',$themefileadd['themedetailWord']);
        $all=['themedetailTitle'=>$themefileadd['themedetailTitle'],'themedetailId'=>$themefileadd['themedetailId'],'theme_themeId'=>$themefileadd['theme_themeId'],'themedetailWord'=>$themefile];

        $result=Db::table('themedetail')
            ->update($all);
        if($result){
            $this->success("信息修改成功");
        }else{
            $this->error("信息修改失败");
        }
    }
//删除文章信息
public function delfile(){
    $id= $this->request->post('id');
    $result=Db::table('themedetail')->where('themedetailId',$id)->delete();
    if($result){
        $this->success("成功删除该条主题信息！", '');
    }else{
        $this->error("删除失败！", '');
    }
}


//删除主题信息
    public function del(){
        $id= $this->request->post('id');
        $check=Db::table('themedetail')->where('theme_themeId',$id)->find();
        if(!$check){
            $result=Db::table('theme')->where('themeId',$id)->delete();
            if($result){
                $this->success("成功删除该条主题信息！", '');
            }else{
                $this->error("删除失败！", '');
            }
        }else{
                $this->error('请先删除该主题下的文章','');
        }

    }
    //修改详情信息
    public function editfile(){
        $id= input('id');
        $result=Model('Menu')->theme();
        $this->assign('theme',$result);
        $db=Db::table('themedetail')->where('themedetailId','=',$id)->find();
        $this->assign('db',$db);
        return $this->_form('themedetail','revicefile');
    }

    //修改主题信息
    public function edit()
    {
        $id= input('id');
        $db=Db::table('theme')->where('themeId','=',$id)->find();
        $this->assign('db',$db);
        return $this->_form('theme', 'revice');
    }
    //
    public function add()
    {
        if ($this->request->isGet()) {
            return view('form', ['title' => '添加主题信息']);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result=Db::table('theme')->insert($data);
            if($result){
                $this->success('主题信息修改成功');
            }else{
                $this->error('添加失败，请稍候再试！');
            }
        }
    }
}