<?php
/**
 * Created by PhpStorm.
 * User: sinao
 * Date: 2018/11/30
 * Time: 19:43
 */

namespace app\api\model;


use think\Model;

class DrawCommodity extends Model
{
    public function retailCommodity(){
        return $this->belongsTo('commodity','commodity_commodityId','goodId');
    }

}