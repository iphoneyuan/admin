 <?php
/**
 * Created by PhpStorm.
 * User: iphone
 * Date: 2018/6/14
 * Time: 14:08
 */

return[
    'app_id'=>'wxc0d6572eb2c56f8f',
    'app_secret'=>'786b0d745fffe4655caf774c71efc52a',
    'login_url'=>"https://api.weixin.qq.com/sns/jscode2session?"."appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    'access_url'=>"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
    'seccheck_url'=>"https://api.weixin.qq.com/wxa/msg_sec_check?access_token=%s"

];