<?php


namespace Annotate\Library\Saver;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\OutputStyle;

abstract class BaseSaver
{
    protected $input;

    protected $output;

    function __construct(InputInterface $input, OutputStyle $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    abstract function save(string $document): bool;
}
