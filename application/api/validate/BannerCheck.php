<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/27
 * Time: 17:07
 */

namespace app\api\validate;


class BannerCheck extends BaseValidate
{
    protected $rule=[
        'id'=>'require|isPositiveInteger'
    ];
    protected $message=[
        'msg'=>'缺少相应的BannerID'
    ];


}