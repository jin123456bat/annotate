<?php


namespace Annotate\Library;


use Annotate\Library\DocBuilder\BaseDocBuilder;

/**
 * Class DocBuilder
 * @package Annotate\Library
 */
class DocBuilder
{
    /**
     * @var BaseDocBuilder
     */
    private $driver;

    /**
     * DocBuilder constructor.
     * @param BaseDocBuilder $driver
     */
    function __construct(BaseDocBuilder $driver)
    {
        $this->driver = $driver;
    }

    /**
     * 生成文档
     * @param array $annotate_array
     * @return mixed
     */
    function build(array $annotate_array): string
    {
        $this->driver->beforeBuild();
        $return = $this->driver->build($annotate_array);
        $this->driver->afterBuild();
        return $return;
    }
}
