<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/14
 * Time: 12:32
 */

namespace app\api\model;


use think\Model;

class Themedetail extends Model
{
        //获取主题下问题的详情信息
        public function getThemeQuestionById($id){
            $getThemeQuestionById=self::find($id);
            return $getThemeQuestionById;

        }
}