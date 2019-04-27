<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//获取所有的banne信息
Route::get('api/Banner/:id', 'api/Banner/getBanner');
//获取一个用户的详细任务信息
Route::get('api/UserAssignment','api/User/getUserAssignmentById');
//单个用户上传单个任务信息
Route::post('api/GetAssignment','api/User/UpAssignment');
//获取一个用户的问题信息
Route::get('api/GetPrizegivingById','api/User/getPrizegivingById');
//单个用户上传单个问题信息
Route::post('api/UpPrizegiving','api/User/UpPrizegivingById');
//获取一个用户的所有的评论
Route::get('api/GetReplyQuestionById','api/User/getReplyQuestionById');
//获取一个用户发布的所有商品信息
Route::get('api/UserCommodityById','api/User/GetCommodityById');
//获取所有的商品信息
Route::get('api/GetCommodity','api/commodity/getCommoditysById');
//获取一个商品的详情信息
Route::get('api/getCommodityDetail/:id','api/commodity/getCommodityDetail');
//获取一个任务信息的详细信息
Route::get('api/AssignmentById/:id','api/Assignment/getAssignmentById');
//获取所有任务信息
Route::get('api/AssignmentAll','api/Assignment/getAssignmentAll');
//获取所有主题的详细信息
Route::get('api/ThemeAll','api/Theme/themeAll');
//获取单个主题的详细信息
Route::get('api/GetThemeById/:id','api/Theme/getThemeById');
//获取主题下问题的详情信息
Route::get('api/GetThemeQuestionById/:id','api/Theme/getThemeQuestionById');
//获取所有问题的信息
Route::get('api/PrizegivingAll','api/Prizegiving/PrizegivingAll');
//获取单个问题的信息
Route::get('api/PrizegivingById/:id','api/Prizegiving/prizegivingById');
//获取一个用户回答的问题
Route::post('api/UpReplyQuestionById','api/User/UpReplyQuestionById');
//社团注册
Route::post('api/UpCommodityTask','api/User/UpCommodityTask');
//获取社团信息
Route::get('api/news/:id','api/News/news');
//获取所有的社团活动信息
Route::get('api/GetCorporationAll','api/Corporation/getCorporationAll');
//获取社团活动详情信息
Route::get('api/getCorporationById/:id','api/Corporation/getCorporationById');
//通过Token在后台换取token
Route::post('api/Token','api/Token/getToken');
//进行身份认证
Route::post('api/Identity','api/Token/Identity');
//检查身份验证
Route::get('api/CheckLogin','api/Token/CheckLogin');
//检查社团身份验证
Route::get('api/CheckCorporationLogin','api/Token/CheckCorporationLogin');
//用户接受
Route::post('api/SureAssignment','api/DrawAssignment/sureAssignment');
//上传任务投诉信息
Route::post('api/UpComplainById','api/User/UpComplainById');
//上传问题投诉信息
Route::post('api/UpQuestionComplain','api/User/UpQuestionComplain');
//领取社团任务
Route::post('api/setCorporation','api/Corporation/setCorporation');
//查询一个任务的领取人物
Route::get('api/receiptor/:id','api/DrawAssignment/receiptor');
//确认任务是否已经被领取
Route::post('api/changepublicsure','api/DrawAssignment/changepublicsure');
//获取任务发布者已经发布的任务
Route::get('api/getCorporation','api/Corporation/getCorporation');
//获取任务发布者已经发布的信息的详细信息
Route::post('api/getCorporationDetail','api/Corporation/getCorporationDetail');
//同意某学生的请求
Route::post('api/comfireCorporation','api/Corporation/comfireCorporation');
//获取一个用户所有领取的任务信息
Route::get('api/getUserDrawAssignment','api/User/getUserDrawAssignment');
//获取一个用户领取任务的详细信息
Route::get('api/getUserDrawAssignmentDetail/:id','api/User/getUserDrawAssignmentDetail');
//获取一个任务的详情信息
Route::get('api/getResponse/:id','api/Replyqusetion/getResponse');
//问题积分交换
Route::get('api/thanksquestion/:id','api/Replyqusetion/thanksquestion');
//商品上传
Route::post('api/UpShopping','api/User/UpShopping');
//获取一个用户预订的商品
Route::get('api/reviceCommodityById','api/User/reviceCommodityById');
//用户点赞
Route::post('api/thumb/:id','api/commodity/thumb');
//用户线下预订
Route::post('api/SubscribeShopping','api/User/SubscribeShopping');
//社团主管发布社团任务
Route::post('api/UpCommunityMission','api/User/UpCommunityMission');
//用户确认完成相应的任务完成的接口
Route::post('api/UserSurefinish','api/User/UserSurefinish');
//发布者确认完成相应的接口
Route::post('api/PublicSurefinish','api/User/PublicSurefinish');
//获取任务评论信息
Route::post('api/getCommentById','api/Assignment/getCommentById');
//任务留言功能
Route::post('api/UpDrawCommodity','api/User/UpDrawCommodity');
//商品信息留言列表
Route::post('api/getComment','api/Commodity/getComment');
//商品信息留言功能
Route::post('api/UpGoodCommodity','api/User/UpGoodCommodity');
//检查登录按钮
Route::get('api/CheckLoginButton','api/Token/CheckLoginButton');
//获取用户积分
Route::get('api/GetIntegration','api/User/GetIntegration');
//用户投诉进行中任务的积分
Route::post('api/toushu','api/User/toushu');
//获取商品预订人
Route::post('api/commodityperson/:id','api/User/commodityperson');
//商品人确定预订
Route::post('api/commoditypersonsure','api/User/commoditypersonsure');
//获取商品预订信息状态
Route::post('api/getCommodityStatus/:id','api/Commodity/getCommodityStatus');
//商品领取者确定已经完成交易
Route::get('api/sureGetCommodity/:id','api/User/sureGetCommodity');
//商品交易确认完成
Route::post('api/surefinishCommodity','api/User/surefinishCommodity');
//商品交易确认完成
Route::get('api/getCommodityByOrder/:id','api/Commodity/getCommodityByOrder');
//获取个人领取的社团任务
Route::post('api/getUserTeamWork','api/User/getUserTeamWork');
//获取活动信息
Route::get('api/notice','api/Notice/index');
//数据校验
Route::post('api/checkMessage','api/CheckMessage/CheckMessage');

