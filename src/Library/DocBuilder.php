<?php


namespace Annotate\Library;


use Annotate\Library\DocBuilder\BaseDocBuilder;
use Exception;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;

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
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputStyle
     */
    private $output;

    /**
     * @var string
     */
    protected $file;

    /**
     * DocBuilder constructor.
     * @param BaseDocBuilder $driver
     */
    function __construct(BaseDocBuilder $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param InputInterface $input
     */
    function setInput(InputInterface $input)
    {
        $this->input = $input;
        $this->driver->setInput($input);
    }

    /**
     * @param OutputStyle $output
     */
    function setOutput(OutputStyle $output)
    {
        $this->output = $output;
        $this->driver->setOuput($output);
    }

    /**
     * 生成文档
     * @param array $annotate_array
     * @return mixed
     * @throws Exception
     */
    function build(array $annotate_array): string
    {
        $this->driver->beforeBuild();
        $document = $this->driver->build($annotate_array);
        $this->driver->afterBuild();
        if ($this->save($document)) {
            return $this->getFile();
        }
        throw new Exception('无法生成临时文件');
    }

    /**
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
        $filename = 'annotate_' . date('Ymd_His') . '.json';
        $this->file = $path . DIRECTORY_SEPARATOR . $filename;
        $result = file_put_contents($this->file, $document);
        $this->output->writeln('[annotate]文档生成成功:' . $this->file);
        return $result > 0;
    }

    /**
     * @return string
     */
    function getFile(): string
    {
        return $this->file;
    }
}
