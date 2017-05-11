<?php
return array(
    //'配置项'=>'配置值'
    //URL地址不区分大小写
    'URL_CASE_INSENSITIVE' => true,
    'URL_MODEL' => 0,
    /* 数据库设置 */
    'DB_TYPE' => 'mysql',            // 数据库类型
    'DB_HOST' => 'localhost',       // 服务器地址
    'DB_NAME' => 'imooc_singcms',  // 数据库名
    'DB_USER' => 'root',            // 用户名
    'DB_PWD' => 'root',             // 密码
    'DB_PORT' => '',                // 端口
    'DB_CHARSET' => 'utf8',        // 数据库表返回编码
    'DB_PREFIX' => 'cms_',         // 数据库表前缀
    //MD5扩充名
    'MD5_PRE' => 'sing_cms',
    //生成静态化html
    'HTML_FILE_SUFFIX' => '.html',
    //来源
    'COPY_FROM' => array(
        0 => '本站',
        1 => '新浪网',
        2 => '央视网',
        3 => '网易',
        4 => '搜狐',
    ),
);