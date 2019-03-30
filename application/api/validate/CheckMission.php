<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class CheckMission extends BaseValidate
{
    protected $rule=[
        'title'=>'require|isNotEmpty',
        'word'=>'require|isNotEmpty',
        'begintime'=>'require|isNotEmpty',
        'endtime'=>'require|isNotEmpty',
        'intergrationRequire'=>'require|isNotEmpty|number'

    ];

}