<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/10/29
 * Time: 10:10
 */

namespace app\api\validate;


class AskComplain extends BaseValidate
{
    protected $rule=[
        'complainWord'=>'require|isNotEmpty',
        'assignment_assignmentId'=>'require|isNotEmpty'
    ];
//    protected   $field = [
//        'complainWord'  => '投诉信息',
//        'assignment_assignmentId'   => '任务编号',
//    ];
}