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
        if($guard=='admin'){
            $directory .= auth($guard)->user()['id'].DIRECTORY_SEPARATOR;
        }
        if($guard=='tenant'){
            $directory .= auth($guard)->user()['tenant_id'].DIRECTORY_SEPARATOR;
        }
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
