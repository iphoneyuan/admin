<?php
namespace app\lib\exception;

class CorporationException extends BaseException{
	 public $code=401;
	 public $msg='你暂不属于社团负责人';
	 public $errorCode=0;
}