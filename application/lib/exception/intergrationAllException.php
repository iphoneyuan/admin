<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/1
 * Time: 10:10
 */

namespace app\lib\exception;


use think\Exception;

class intergrationAllException extends BaseException
{
    public $code=200;
    public $msg='您的积分已不足';
    public $errorCode=0;
}