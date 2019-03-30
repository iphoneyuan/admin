<?php
namespace app\lib\exception;

class CommodityException extends BaseException{
	 public $code=401;
	 public $msg='禁止重复预订商品';
	 public $errorCode=0;
}