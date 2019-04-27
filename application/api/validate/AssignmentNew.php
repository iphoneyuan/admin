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
        'address'=>'require',
        'intergrationRequire'=>'require|isNotEmpty|number',
        'enddata'=>'require|isNotEmpty',
        'word'=>'require|checkWord',
        'countdata'=>'require|isNotEmpty',
        'time'=>'require|isNotEmpty'

    ];
    public  $field = [
        'address'  => '地址',
        'intergrationRequire'   => '积分',
        'word'=>'任务内容',
        'enddata'=>'结束日期',
        'email' => '电子邮件',
        'countdata'=>'任务天数'

    ];

    // 自定义验证规则
    protected function checkWord($value,$rule,$data)
    {       return strstr($value,'代课') ? '含有非法字符' : true;


    }


}