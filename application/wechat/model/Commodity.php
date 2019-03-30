<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/12/6
 * Time: 11:26
 */

namespace app\wechat\model;

use think\Db;
use think\Model;

class Commodity extends Model
{

    /**
     * 显示列表操作
     */
    public function index()
    {
        $result=Db::table('commodity')->select();
        return $result;
    }
}