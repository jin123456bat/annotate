<?php


namespace Annotate\Library;


use Annotate\Library\Saver\BaseSaver;

/**
 * Class Saver
 * @package Annotate\Library
 */
class Saver
{
    /**
     * @var BaseSaver
     */
    protected $driver;

    /**
     * Saver constructor.
     * @param BaseSaver $saver
     */
    function __construct(BaseSaver $saver)
    {
        $this->driver = $saver;
    }

    /**
     * @param string $content
     * @return bool
     */
    function save(string $content): bool
    {
        return $this->driver->save($content);
    }
}
