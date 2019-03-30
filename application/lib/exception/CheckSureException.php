<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/1
 * Time: 10:10
 */

namespace app\lib\exception;


use think\Exception;

class CheckSureException extends BaseException
{
    public $code=200;
    public $msg='请先同意该交易';
    public $errorCode=0;
}