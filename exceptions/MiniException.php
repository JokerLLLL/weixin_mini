<?php
/**
 * Created by PhpStorm.
 * User: jokerl
 * Date: 2018/11/13
 * Time: 17:58
 */

namespace backend\modules\mini\exceptions;


use yii\base\Exception;

class MiniException extends Exception
{
        protected $code = 500000;
        protected $message = '小程序内部错误';
}