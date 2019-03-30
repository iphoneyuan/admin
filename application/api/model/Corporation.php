<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/18
 * Time: 0:06
 */

namespace app\api\model;


use think\Model;

class Corporation extends Model
{
      protected  $hidden=['delete_time','update_time'];
      public function getCorporationAll(){
          $corporationAll=new Corporation();
          $result=$corporationAll->order('createtime','desc')->select();
          return json_encode($result);
      }
      public function getCorporationById($id){
          $corporationById=new Corporation();
          $result=$corporationById->find($id);
          $result['createtime']=date('Y-m-d h:i:s',$result['createtime']);
          return $result;
      }
}