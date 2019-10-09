<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/10/9
 * Time: 19:04
 */

namespace Wxpay;


class  SDKRuntimeException extends \Exception {
    public function errorMessage()
    {
        return $this->getMessage();
    }

}
