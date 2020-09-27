<?php


namespace Annotate\Library\Saver;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\OutputStyle;

/**
 * Class Local
 * @package Annotate\Library\Saver
 */
class Local extends BaseSaver
{
    /**
     * @var array
     */
    private $config;

    /**
     * Local constructor.
     * @param InputInterface $input
     * @param OutputStyle $output
     * @param array $config
     */
    function __construct(InputInterface $input, OutputStyle $output, array $config)
    {
        $this->config = $config;
        parent::__construct($input, $output);
    }

    /**
     * 执行存储
     * @param string $file
     * @return bool
     */
    function save(string $file): bool
    {
        return $file;
    }
}
