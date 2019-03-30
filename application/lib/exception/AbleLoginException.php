<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/1
 * Time: 10:10
 */

namespace app\lib\exception;


use think\Exception;

class AbleLoginException extends BaseException
{
    public $code=200;
    public $msg='您的信息已验证成功，请勿重新验证';
    public $errorCode=0;
}