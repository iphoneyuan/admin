<?php

namespace app\api\validate;

use think\Validate;
use think\Request;
USE think\Error;
use think\Exception;
use app\lib\exception\ParameterException;

class BaseValidate extends Validate {
	public function goCheck() {
		// 对这些参数做检验
		$request = Request::instance (); // 获取实例对象
		$params = $request->param (); // 调用param方法，获取参数
		$result = $this->check ( $params ); // 因为是在Validate类的内部，所以就不用new一个validata方法
		if (! $result) {
			$e = new ParameterException ([  // 编写构造函数
					'msg' => $this->error
			]);

			throw $e;

		} else {
			return true;
		}
	}
	protected function isPositiveInteger($value, $rule = '', $data = '', $field = '') {
		if (is_numeric ( $value ) && is_int ( $value + 0 ) && ($value + 0) > 0) {
			return true;
		} else {
			return $field.'必须是正整数';
		}

}

protected function isNotEmpty($value, $rule = '', $data = '', $field = '',$msg='')
{
    if (empty($value)) {
       return $msg.'不能为空';
    } else {
        return true;
    }
}

public function getDataByRule($arrays){
	if(array_key_exists('user_id', $arrays)|array_key_exists('uid', $arrays)){
		throw new ParameterException([
				'msg'=>'参数中包含非法的参数名user_id或者uid'
		]);
	}
	$newArray=[];
	foreach ($this->rule as $key=>$value){
		$newArray[$key]=$arrays[$key];
	}
	return $newArray;
}
protected function isMobile($value){
	$rule='^1(3|4|5|6|7|8)[0-9]\d{8}$^';
	$result=preg_match($rule, $value);
	if($result){
		return true;
	}else{
		return false;
	}
}
}