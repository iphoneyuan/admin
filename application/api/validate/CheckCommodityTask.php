<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/24
 * Time: 8:48
 */

namespace app\api\validate;


class CheckCommodityTask extends BaseValidate
{
    protected $rule=[
        'realname'=>'require|isNotEmpty',
        'number'=>'require|isNotEmpty|number|length:10',
        'department'=>'require|isNotEmpty',
        'job'=>'require|isNotEmpty',
        'corporationName'=>'require|isNotEmpty'
    ];


}