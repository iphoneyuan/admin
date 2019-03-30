<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class QuestionNew extends BaseValidate
{
    protected $rule=[
        'intergrationRequire'=>'require|isNotEmpty|number',
        'question'=>'require|isNotEmpty'
    ];

}