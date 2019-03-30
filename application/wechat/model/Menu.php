<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/14
 * Time: 10:54
 */

namespace app\wechat\model;


use think\Db;
use think\Model;

class Menu extends Model
{
    public function theme(){
     $result=Db::table('theme')
         ->field('themeId,imageurl,title,begintime,endtime,address')
         ->select();
     return $result;
    }
}