<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/31
 * Time: 14:53
 */

namespace app\lib\exception;


class CheckAssignmentException extends BaseException
{
    public $code=401;
    public $msg='请勿重复领取或确认任务';
    public $errorCode=0;
}