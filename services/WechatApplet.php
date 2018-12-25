<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2018/11/13
 * Time: 17:33
 */

namespace backend\modules\mini\services;


use backend\modules\mini\exceptions\MiniException;
use EasyWeChat\Foundation\Application;
use yii\helpers\FileHelper;

class WechatApplet
{
      const GET = 'get';
      const POST = 'post';
     // 小程序模版消息链接
     const URL_TEMPLATE_SEND = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=';
     //统一消息接口
    const URL_UNIFORM_SEND = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send?access_token=';
    //二维码接口
    const URL_UNLIMIT = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=';

    //获取小程序的 access_token
    public static function getMiniAccessToken()
    {
        $app =  new Application(\Yii::$app->params['WECHAT']);
        $mini = $app->mini_program;
        $access_token = $mini->sns->getAccessToken()->getToken();
        if(!$access_token){
            throw new MiniException('无法获取到access_token');
        }
        return $access_token;
    }

    /** 发送模版消息
     * @param array $arr
     * @return bool
     * @throws MiniException
     */
    public static function sendMessage($arr = [])
    {
        $access_token = self::getMiniAccessToken();
        $url = self::URL_TEMPLATE_SEND.$access_token;
        $json = self::httpCurl($url,$arr,self::POST);
        $arr = json_decode($json,true);
        if($arr['errcode'] != 0) {
            throw new MiniException($arr['errmsg'],$arr['errcode']);
        }
        return true;
    }



    /*
     *  获取小程序二维码
     */
    public static function sendCodePic($arr)
    {
        $access_token =  self::getMiniAccessToken();
        $url = self::URL_UNLIMIT.$access_token;
        $stream = self::httpCurl($url,$arr,self::POST);
        if(self::isJson($stream)){
            $arr = json_decode($stream,true);
            throw new MiniException($arr['errmsg'],$arr['errcode']);
        }
        $path = self::getDir().self::buildRandNo().'.png';;
        file_put_contents($path,$stream);
        return $path;
    }

    /*
     * 路由请求
     */
    public static function httpCurl($url,$arr = [] ,$method = self::GET)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);      //设置超时时间 30s
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置参数 成功返回内容 失败返回false
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if ($method == self::POST) {
            $json = json_encode($arr);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);//json格式 post过去
        }
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new MiniException('发起路由请求错误!');
        }
        curl_close($ch);
        return $output;
    }

    /*
     * 创建文件夹
     */
    public static  function getDir()
    {
        $dir = \Yii::getAlias('@webroot').'/mini/';
        if(!file_exists($dir)) {
            FileHelper::createDirectory($dir,0775);
        }
        return  $dir;
    }


    /*
     * 判断是否json
     */
    public static function isJson($string)
    {
        return !is_null(json_decode($string));
    }

    /*
     * 随机
     */
    public static function buildRandNo(){
    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}