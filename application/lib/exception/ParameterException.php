<?php
namespace app\lib\exception;

class ParameterException extends BaseException{
	public $code=500;
	public $msg='参数错误';
	public $errorCode=0;
}