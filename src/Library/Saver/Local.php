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
     */
    function __construct(InputInterface $input, OutputStyle $output, array $config)
    {
        $this->config = $config;
        parent::__construct($input, $output);
    }

    /**
     * 执行存储
     * @param string $document
     * @return bool
     */
    function save(string $document): bool
    {
        $path = $this->config['storage'];
        if (!is_dir($path)) {
            $mask = umask(0);
            mkdir($path, true, 0777);
            umask($mask);
        }
        $filename = 'postman_' . date('Ymd_His') . '.json';
        $file = $path . DIRECTORY_SEPARATOR . $filename;
        $result = file_put_contents($file, $document);
        $this->output->writeln('[annotate]文档生成成功:' . $file);
        return $result > 0;
    }
}
