<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class CheckDrawException extends BaseException
{
    public $code=401;
    public $msg='领取人尚未确定完成';
    public $errorCode=0;
}