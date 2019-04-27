<?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/10/11
 * Time: 10:30
 */

namespace app\api\controller;
use app\api\model\Banner as BannerModel;
use app\api\validate\BannerCheck;
use app\lib\exception\WeChatException;

//use think\controller;

class Banner extends BaseController
{
//    banner接口
  public function getBanner($id){
//   $user=new banner();
//   $user->index();
//   $banner=model('banner')->getBannerByID($id);
      (new BannerCheck())->goCheck();
      $banner=BannerModel::getBannerByID($id);
       return $banne

  }
}