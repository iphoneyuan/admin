<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/18
 * Time: 11:16
 */

namespace app\api\controller;
use think\model;

class News
{
   public function news($id){
       $news=model('News')->getNewAll($id);
       return $news;
   }
}