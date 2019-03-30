<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 15:00
 */

namespace app\lib\exception;


class IsCommodityException extends BaseException
{
    public $code=400;
    public $msg='禁止预订自身商品';
    public $errorCode=0;
}