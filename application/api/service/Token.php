<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/6
 * Time: 9:24
 */

namespace app\api\service;
use think\Db;
use think\Request;
use think\Cache;
use app\lib\exception\TokenException;
class Token
{
    //生成令牌
    public static function generateToken()
    {
        //32 个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt 盐
        $salt = config('secure.token_salt');
        return md5($randChars . $timestamp . $salt);
    }
//获取当前token信息
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true); // 字符串变数组
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的token变量并不存在');
            }
        }
    }

    //获取当前用户信息
    public static function getCurrentUid()
    {
        // token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
    //判断数据库中是否存在相应学生信息
    public static function checkStudent($openid){
        $result=Db::table('user')
             ->alias('a')
             ->join('user_address b','a.userId=b.user_userId')
             ->where('openId','=',$openid)
             ->find();

        if($result){
            return true;
        }else{
            return false;
        }

    }

}