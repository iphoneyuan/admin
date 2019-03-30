<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class CheckShoping extends BaseValidate
{
    protected $rule=[
        'description'=>'require|isNotEmpty',
        'price'=>'require|isNotEmpty|number',
        'goodName'=>'require|isNotEmpty'
    ];

}