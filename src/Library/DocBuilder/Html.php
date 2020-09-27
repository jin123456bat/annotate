<?php


namespace Annotate\Library\DocBuilder;


use Parsedown;
use Throwable;

/**
 * Class Html
 * @package Annotate\Library\DocBuilder
 */
class Html extends BaseDocBuilder
{
    /**
     * @var Parsedown
     */
    private $markdownParser;

    /**
     * @param array $annotate_route
     * @return string
     * @throws Throwable
     */
    function build(array $annotate_route): string
    {
        //先生成markdown
//        $this->markdownParser = new Parsedown();
//        $fileContent = file_get_contents(resource_path('views/vendor/annotate/template.md'));
//        $htmlContent = $this->convertMarkdownToHtml($fileContent);
//        $content = $this->convertMarkdownToHtml($htmlContent);
        return view('annotate::html')->with('annotates', $annotate_route)->render();
    }

    /**
     * @param $markdown
     * @return string
     */
//    public function convertMarkdownToHtml($markdown): string
//    {
//        return $this->markdownParser->setBreaksEnabled(true)->text($markdown);
//    }

    function getExt(): string
    {
        return 'html';
    }
}