<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class CheckDataException extends BaseException
{
    public $code=401;
    public $msg='任务期限将至，无法领取';
    public $errorCode=0;
}