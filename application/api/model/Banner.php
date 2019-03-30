<?php

namespace app\api\model;

use think\Exception;
use think\Db;
use think\Model;
use app\api\model\BannerItem;

class Banner extends Model // 变成了模型
{
    protected $hidden=['delete_time','update_time'];

//一对多的关联
//一个banner对应多个banner_item
    public function items() {
        return $this->hasMany ( 'BannerItem', 'banner_bannerId', 'bannerId' ); // 关联模型的模型名 外键 主键
    }

//用于关联预载入
//从表的载入
    public static function getBannerByID($id) {
        $banner = self::with ( 'items' )->find ( $id );
        return $banner;
    }
}