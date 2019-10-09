<?php
namespace Wxpay;

/**
 * 统一支付接口类
 */
class UnifiedOrder_pub extends Wxpay_client_pub
{
    function __construct()
    {
        //设置接口链接

        $this->url = "https://api.tnbpay.com/pay/gateway";
        //设置curl超时时间
        $this->curl_timeout = WxPayConf_pub::CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        //检测必填参数
        if ($this->parameters["out_trade_no"] == null) {
            throw new SDKRuntimeException("缺少统一支付接口必填参数out_trade_no！");
        } elseif ($this->parameters["body"] == null) {
            throw new SDKRuntimeException("缺少统一支付接口必填参数body！");
        } elseif ($this->parameters["total_fee"] == null) {
            throw new SDKRuntimeException("缺少统一支付接口必填参数total_fee！");
        }

        $this->parameters["appid"] = WxPayConf_pub::DCAPPID;//公众账号ID
        $this->parameters["mch_id"] = WxPayConf_pub::DCMCHID;//商户号
        $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
		$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR']; //终端ip
        $this->parameters["sign"] = $this->getSign($this->parameters, WxPayConf_pub::DCKEY);//签名
        return $this->arrayToXml($this->parameters);
    }

    /**
     *    作用：设置jsapi的参数
     */
    public function getAppParameters()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);

//		$payInfo = $this->result["pay_info"];

//		$jsApiObj["appid"] = "wx8d46f83d49930740";
//	    $jsApiObj["partnerid"] = "11971742";
//	    $jsApiObj["timestamp"] = "1460816908";
//	    $jsApiObj["noncestr"] ="V8d6TIlsxxhbixAW";
//		$jsApiObj["prepayid"] ="wx20160416222828d565bd9e420366106469";
//	    $jsApiObj["package"] = "Sign=WXPay";
//	    $jsApiObj["sign"] ="D3BA90FD0CCB94E694BED4656B32457A";
        return $this->result;
    }
}
