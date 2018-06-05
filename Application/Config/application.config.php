<?php
/**
 * 配置文件
 */
return [
    'db'=>[//数据库的配置信息
//        'host'=>'127.0.0.1',
//        'user'=>'root',
        'password'=>'root',
        'database'=>'member',
//        'port'=>3306,
//        'charset'=>'utf8'
    ],
    'default'=>[
        'platform'=>'Home',#前台 后台
        'controller'=>'index',
        'action'=>'index'
    ]
];
