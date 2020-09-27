<?php

use Annotate\Library\DocBuilder\Html;
use Annotate\Library\Saver\Local;


#模板配置
return [
    /**
     * 生成什么格式的文档
     * 支持一次性配置多个
     */
    'driver' => [
//        Postman::class => [
//            '_postman_id' => 'c0a7be61-cb7f-4897-9cdb-50ed053544b9',
//            'name' => config('app.name'),
//            'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
//        ],
        Html::class => [

        ]
    ],

    /*
     * 保存的文档的信息
     */
    'saver' => [
        /**
         * 保存驱动
         */
        Local::class => [
            /**
             * 文件存储位置
             */
            'storage' => resource_path('annotate' . DIRECTORY_SEPARATOR . 'document'),
        ]
    ]
];
