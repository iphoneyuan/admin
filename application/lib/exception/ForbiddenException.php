<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/31
 * Time: 14:53
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code=200;
    public $msg='请重新登录';
    public $errorCode=0;
}