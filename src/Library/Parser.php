<?php


namespace Annotate\Library;


use Annotate\Library\Element\Param;

/**
 * 注解解释器
 * Class Parser
 * @package Annotate\Library
 */
class Parser
{
    /**
     * 响应参数说明
     * @param array $content
     * @return Param[]|array
     */
    function response(array $content): array
    {
        return $this->param($content);
    }

    /**
     * 请求参数解析
     * @param string[] $content
     * @return Param[]
     */
    function param(array $content): array
    {
        $data = [];
        foreach ($content as $value) {
            $param = array_filter(array_map(function ($item) {
                return trim($item);
            }, explode(' ', $value)));
            if (empty($param)) {
                continue;
            } else {
                $param = new Param($param);
                if ($param->isValid()) {
                    $data[] = $param;
                }
            }
        }
        return $data;
    }

    /**
     * package的别名
     * @param array $content
     * @return array
     */
    function group(array $content): array
    {
        return $this->package($content);
    }

    /**
     * 分组信息
     * @param array $content
     * @return array
     */
    function package(array $content): array
    {
        $string = array_shift($content);
        $string = str_replace([
            '\\',
            ',',
            ' ',
        ], '/', $string);
        return array_filter(explode('/', $string));
    }

    /**
     * 请求头
     * @param array $content
     * @return array
     */
    function header(array $content): array
    {
        $header = [];
        foreach ($content as $value) {
            $value = str_replace([
                '/',
                '\\',
                ',',
                ':',
                '=>'
            ], ' ', $value);
            list($k, $v) = explode(' ', $value, 2);
            $header[$k] = $v;
        }
        return $header;
    }

    /**
     * 是否需要登录后使用
     * @param array $content
     * @return bool
     */
    function authorized(array $content): bool
    {
        return true;
    }

    /**
     * 接口版本信息
     * @param array $content
     * @return string
     */
    function version(array $content): string
    {
        return array_shift($content);
    }

    /**
     * 是否忽略接口
     * @param array $content
     * @return bool
     */
    function ignore(array $content): bool
    {
        return true;
    }

    /**
     * 接口是否已被废除
     * @param array $content
     * @return bool
     */
    function deprecated(array $content): bool
    {
        return true;
    }

    /**
     * 格式化响应数据
     * @param array $content
     * @return array
     */
    function example(array $content): array
    {
        return $this->request($content);
    }

    /**
     * 格式化请求数据
     * @param array $content
     * @return array
     */
    function request(array $content): array
    {
        $data = [];
        foreach ($content as $value) {
            $result = json_decode($value, true, 512, JSON_BIGINT_AS_STRING);
            if ($result === null) {
                $data[] = $value;
            } else {
                $data[] = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            }
        }
        return $data;
    }

    /**
     * 接口名称
     * @param array $content
     * @return string
     */
    function name(array $content): string
    {
        return current(array_filter($content));
    }
}
