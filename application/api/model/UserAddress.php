<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/12
 * Time: 18:39
 */

namespace app\api\model;


use think\Model;

class UserAddress extends Model
{
  //获取学生数据表
    public static function getStudentId($studentId){
        $user=self::where('studentId','=',$studentId)
            ->find();
        return $user;
    }

}