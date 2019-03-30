<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/25
 * Time: 22:37
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule=[
        'code'=>'require|isNotEmpty'
    ];
    protected $message=[
        'msg'=>'缺少code，还想获取token，做梦吧'
    ];

}