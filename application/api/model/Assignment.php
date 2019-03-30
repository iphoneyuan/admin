<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/12
 * Time: 13:04
 */

namespace app\api\model;
use think\Model;

class Assignment extends Model
{
    protected $hidden=['create_time','delete_time'];

    //当一个表的外键和主键位于同一个表的时候，我们就需要用到belongsto
    public function itemUser(){
       return $this->belongsTo('User','user_userId','userId');  //本模型外键  关联模型主键
    }


    //获取所有的任务信息
    public function getAssignmentAll(){
         $assignmentAll=self::with(['itemUser','itemUser.UserAddress'])->where('assign_sure','0')->where('complain_result','not in','2')
                              ->where('enddata','>=',time())->order('createtime','desc')->select();
        foreach ( $assignmentAll as $value){
            $value['createtime']=date("Y-m-d H:i:s",$value['createtime']);
        }

         return json_encode($assignmentAll);
    }

    //获取单个任务的详细信息
    public function getAssignmentById($id){
        $assignmentById=self::with(['itemUser','itemUser.UserAddress'])->find($id);
        $assignmentById['countdata']=date('d',$assignmentById['countdata']);
        $assignmentById['enddata']=date('Y-m-d',$assignmentById['enddata']);
        return $assignmentById;
    }

}