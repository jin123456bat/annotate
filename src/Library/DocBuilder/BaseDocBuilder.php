<?php


namespace Annotate\Library\DocBuilder;


use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;

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
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputStyle
     */
    protected $output;

    /**
     * Postman constructor.
     * @param array $config
     */
    function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     *
     */
    function beforeBuild()
    {

    }

    /**
     * 构建文档
     * @param array $annotate_route
     * @return string
     */
    abstract function build(array $annotate_route): string;

    /**
     *
     */
    function afterBuild()
    {

    }

    /**
     * @param InputInterface $input
     */
    function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @param OutputStyle $output
     */
    function setOutput(OutputStyle $output)
    {
        $this->output = $output;
    }


}
