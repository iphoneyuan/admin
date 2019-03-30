<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/14
 * Time: 12:31
 */

namespace app\api\model;


use think\Model;

class Theme extends Model
{
   public function theme(){
       return $this->hasMany('Themedetail','theme_themeId','themeId');
   }

   //获取所有的主题
  public function themeAll(){
       $themeAll=self::with('theme')->select();
       foreach ($themeAll as $kk=>$value){
           $value['begintime']=date('Y-m-d',$value['begintime']);
           $value['endtime']=date('Y-m-d',$value['endtime']);
       }
       return json_encode($themeAll);
  }
  //获取单一主题
  public function getThemeById($id){
       $getThemeById=self::with('theme')->find($id);
       return $getThemeById;
}

}