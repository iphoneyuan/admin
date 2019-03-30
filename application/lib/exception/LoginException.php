<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/30
 * Time: 17:20
 */

namespace app\lib\exception;


class LoginException extends BaseException
{
    public $code=200;
    public $msg='请检查您个人信息是否有误，系统没发现您的个人信息';
    public $errorCode=0;
}