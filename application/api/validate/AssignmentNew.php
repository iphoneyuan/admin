<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class AssignmentNew extends BaseValidate
{
    protected $rule=[
        'address'=>'require|isNotEmpty',
        'intergrationRequire'=>'require|isNotEmpty|number',
        'enddata'=>'require|isNotEmpty',
        'word'=>'require',
        'countdata'=>'require|isNotEmpty',
        'time'=>'require|isNotEmpty'

    ],
    $msg=[
       'address|isNotEnpty'=>'地址'
    ];

}