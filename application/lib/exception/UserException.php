<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class UserException
{
    public $code=400;
    public $msg='不存在该用户';
    public $errorCode=0;
}