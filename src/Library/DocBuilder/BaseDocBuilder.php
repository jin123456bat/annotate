<?php


namespace Annotate\Library\DocBuilder;


/**
 * Class BaseDocBuilder
 * @package Annotate\Library\Doc
 */
abstract class BaseDocBuilder
{
    /**
     * postman配置
     * @var array
     */
    protected $config;

    /**
     * Postman constructor.
     * @param array $config
     */
    function __construct(array $config)
    {
        $this->config = $config;
    }

    function beforeBuild()
    {

    }

    /**
     * 构建文档
     * @param array $annotate_route
     * @return string
     */
    abstract function build(array $annotate_route): string;

    function afterBuild()
    {

    }
}
