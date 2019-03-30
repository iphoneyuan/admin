<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class IsPersonException extends BaseException
{
    public $code=400;
    public $msg='禁止领取本人任务';
    public $errorCode=0;
}