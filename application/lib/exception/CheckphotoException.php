<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class CheckphotoException extends BaseException
{
    public $code=401;
    public $msg='图片上传失败';
    public $errorCode=0;
}