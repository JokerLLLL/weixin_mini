# 错误机制 #
所有异常错误都抛出 MiniException 包括微信的错误返回

# 使用 #
```php
<?php
    /*
     * 请求参数
     */
    $arr = [
         "touser"=>"OPENID",
         "template_id"=>"TEMPLATE_ID",
         "page"=>"index",
         "form_id"=>"FORMID",
         "data"=>[
             "keyword1"=>[
                 "value"=>"339208499"
             ],
             "keyword2"=>[
                 "value"=>"2015年01月05日 12:30"
             ],
             "keyword3"=>[
                 "value"=>"腾讯微信总部"
             ],
             "keyword4"=>[
                 "value"=>"广州市海珠区新港中路397号"
             ],
         ],
         "emphasis_keyword"=>"keyword1.DATA" 
    ];
    
        /** 发送模版消息
         * @param array $arr
         * @return bool
         * @throws MiniException
         */
    \backend\modules\mini\services\WechatApplet::sendMessage($arr);
```
```php
<?php    
    
       
          $arr = [
                'scene'=>$id,
                'page'=>'pages/login/login',
                'width'=>430,
                'auto_color'=>true,
                'is_hyaline'=>false,
            ];
    /**
     * 获取二维码图片 
     */
    $path = \backend\modules\mini\services\WechatApplet::sendCodePic($arr);
    
    var_dump($path);
```