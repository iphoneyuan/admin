<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/30
 * Time: 10:40
 */

namespace app\api\model;


use think\Model;
use think\db;
class Student extends Model
{
public static function getStudentAll($studentId){
//    $news=new News();
//     $user=self::where('studentId','=',$studentId)->find();
    $user=Db::table('student')->where('studentId'.'='.$studentId)->find();
     return $user;
}
}