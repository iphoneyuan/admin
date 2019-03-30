<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class CheckTimeException extends BaseException
{
    public $code=400;
    public $msg='结束日期不得低于当前日期';
    public $errorCode=0;
}