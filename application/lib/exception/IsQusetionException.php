<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class IsQusetionException extends BaseException
{
    public $code=400;
    public $msg='禁止评论本人问题';
    public $errorCode=0;
}