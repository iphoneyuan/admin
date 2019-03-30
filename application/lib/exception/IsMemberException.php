<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class IsMemberException extends BaseException
{
    public $code=401;
    public $msg='您暂无权限发布这类任务';
    public $errorCode=0;
}