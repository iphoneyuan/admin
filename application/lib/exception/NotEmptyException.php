<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/31
 * Time: 9:21
 */

namespace app\lib\exception;


class NotEmptyException extends BaseException
{
    public $code=200;
    public $msg='输入信息不能为空';
    public $errorCode=0;
}