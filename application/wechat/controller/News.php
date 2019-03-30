<?php

namespace app\wechat\controller;

use controller\BasicAdmin;
use service\DataService;
use service\LogService;
use service\WechatService;
use think\Db;
use think\Log;
use think\response\View;

/**
 * 微信图文管理
 * Class News
 * @package app\wechat\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class News extends BasicAdmin
{

    /**
     * 设置默认操作表
     * @var string
     */
    public $table = 'student';

    /**
     * 图文列表
     */
    public function index()
    {
        $this->assign('title', '学生信息列表');
        $db = Db::name('student')
            ->paginate(18);

        $page = preg_replace(['|href="(.*?)"|', '|pagination|'], ['data-open="$1" href="javascript:void(0);"', 'pagination pull-right'], $db->render());

        $this->assign('page',$page);
        $this->assign('list',$db);
        return $this->fetch();
    }

    /**
     * 图文选择器
     * @return string
     */
    public function select()
    {
        return $this->index();
    }

    /**
     * 媒体资源显示
     * @return array
     */
    public function image()
    {
        $_GET['rows'] = 18;
        $this->assign('field', $this->request->get('field', 'local_url'));
        return $this->_list(Db::name('WechatNewsMedia')->where('type', 'image'));
    }

    /**
     * 图文列表数据处理
     * @param $data
     */
    protected function _index_data_filter(&$data)
    {
        foreach ($data as &$vo) {
            $vo = WechatService::getNewsById($vo['id']);
        }
    }

    /**
     * 图文列表数据处理
     * @param $data
     */
    protected function _select_data_filter(&$data)
    {
        foreach ($data as &$vo) {
            $vo = WechatService::getNewsById($vo['id']);
        }
    }

    /**
     * 添加图文
     * @return View
     */
    public function add()
    {
        if ($this->request->isGet()) {
            return view('form', ['title' => '添加学生信息']);
        }
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['password']=md5($data['password']);
          $result=Db::table('student')->insert($data);
           if($result){
               $this->success('学生信息添加成功','');
           }else{
               $this->error('添加失败，请稍候再试！','');
           }
        }
    }

    public function del(){
        $id= $this->request->post('id');
        $result=Db::table('student')->where('studentId',$id)->delete();
         if($result){
             $this->success("成功删除该条学生信息！", '');
         }else{
             $this->error('删除失败，请稍后再试！');
         }
    }

    public function edit()
    {
        $id= input('id');
        $db=Db::table('student')->where('studentId','=',$id)->find();
        $this->assign('db',$db);
        return $this->_form($this->table, 'image');
    }


}
