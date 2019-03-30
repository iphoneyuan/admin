<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/30
 * Time: 15:16
 */

namespace app\api\model;


use think\Model;

class News extends Model
{
    public function getNewAll($id)
    {
        $user = self::find($id);
        return $user;
    }
}