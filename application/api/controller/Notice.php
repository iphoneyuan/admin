<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2019/4/16
 * Time: 21:40
 */

namespace app\api\controller;


use think\Controller;
use think\Db;

class Notice extends Controller
{

 public function index(){
     $result=Db::table('notice')->where('noticeId',1)->select();
     return json_encode($result);
 }
}