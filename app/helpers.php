<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/2/28
 * Time: 22:47
 */

/**
 * 获取域名
 */
if(!function_exists('get_domain')){
    function get_domain($name){
        $url = config('app.url');
        $suffix = strstr($url, '.');
        return strtolower($name).$suffix;
    }
}

/**
 *  获取在线编辑器或图片上传目录
 */
if(!function_exists('get_upload_base_path')){
    function get_upload_base_path($guard = 'admin')
    {
        $directory = $guard.DIRECTORY_SEPARATOR;
        $directory .= date('Y').DIRECTORY_SEPARATOR.date('m').DIRECTORY_SEPARATOR.date('d').DIRECTORY_SEPARATOR;
        $realPath = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$directory);
        check_dir($realPath);
        return $realPath;
    }
}

/**
 * 检查目录,没有的话创建目录
 */
if(!function_exists('check_dir')){
    function check_dir($dir)
    {
        if(!file_exists($dir)){
            $oldumask = umask(0);
            mkdir($dir, 0777, true);
            umask($oldumask);
        }
    }
}

/**
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @return float
 * 根据经纬度计算两点之间距离
 */
if(!function_exists('get_real_distance')){
    function get_real_distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        $lat1 = ($lat1*pi())/180;
        $lng1 = ($lng1*pi())/180;

        $lat2 = ($lat2*pi())/180;
        $lng2 = ($lng2*pi())/180;

        $calcLongitude = $lng2-$lng1;
        $calcLatitude = $lat2-$lat1;
        $stepOne = pow(sin($calcLatitude/2), 2)+cos($lat1)*cos($lat2)*pow(sin($calcLongitude/2), 2);
        $stepTwo = 2*asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius*$stepTwo;

        return number_format(sprintf("%.2f", $calculatedDistance/1000), 1);
    }
}

/**
 * 上传图片url地址
 */
if(!function_exists('get_upload_url')){
    function get_upload_url($realpath)
    {
        $path = str_replace('\\', '/', str_replace(storage_path(), '', $realpath));
        if(preg_match('#app\/public#', $path)){
            return '/storage'.str_replace('/app/public', '', $path);
        }
        return $path;
    }
}
