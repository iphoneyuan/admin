<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/27
 * Time: 9:41
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code=400;
    public $msg='微信服务器调用失败';
    public $errorCode=0;
}