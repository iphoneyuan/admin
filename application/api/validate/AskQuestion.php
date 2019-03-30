<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class AskQuestion extends BaseValidate
{
    protected $rule=[
        'word'=>'require|isNotEmpty',
        'prizegiving_prizegivingId'=>'require|isNotEmpty'

    ];

}