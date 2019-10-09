<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/10/9
 * Time: 19:04
 */

namespace Wxpay;


class WxPayConf_pub
{
    //=======【基本信息设置】=====================================

    //点芯APPID，由点芯分配
    const DCAPPID = 'a20190927000275663';
    //点芯商户ID，由点芯分配
    const DCMCHID = 'm20190927000275663';
    //点芯商户支付密钥Key，由点芯分配
    const DCKEY = 'dcb5a9222fe695af49f403e7de2fd56c';

    //=======【curl超时设置】===================================
    //本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
    const CURL_TIMEOUT = 30;
}
