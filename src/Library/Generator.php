<?php


namespace Annotate\Library;


/**
 * 自动数据生成器
 * Class Generator
 * @package Annotate\Library
 */
class Generator
{
    /**
     * @return string
     */
    function email($length): string
    {

    }

    /**
     * 随机生成字符串
     * @param $length
     */
    private function random($length)
    {
        $numStr = range(0, 9);
        $lowerStr = implode(',', function () {

        }, array_rand());
    }
}
