<?php
/**
 * Created by iphone元.
 * User: iphone
 * Date: 2018/10/14
 * Time: 12:31
 */

namespace app\api\controller;


class Theme
{
    //获取全部主题
     public function themeAll(){
         $themeAll=model('Theme')->themeAll();
         return $themeAll;
     }

     //获取单一主题的信息
    public function getThemeById($id){
         $getThemeById=model('Theme')->getThemeById($id);
         return $getThemeById;
    }
    //获取主题下问题的详情信息
    public function getThemeQuestionById($id){
            $getThemeQuestionById=model('Themedetail')->getThemeQuestionById($id);
            return $getThemeQuestionById;
    }

}