<?php


namespace Annotate\Library;


/**
 * Class Annotate
 * @package Annotate\Library
 */
class Annotate
{
    /**
     * 注释内容
     * @var array
     */
    private $comment = [];

    /**
     * 注释内容解析后的数组内容
     * @var array
     */
    private $parseContent = [];

    /**
     * Annotate constructor.
     * @param string $comment
     */
    function __construct(string $comment = null)
    {
        $this->append($comment);
    }

    /**
     * 追加注释内容
     * @param string $comment
     * @return void
     */
    function append(string $comment)
    {
        if (!empty(trim($comment))) {
            $this->comment[] = trim($comment);
        }
    }

    /**
     * 注解内容解析
     * @return array
     */
    function parse(): array
    {
        if (empty($this->parseContent)) {
            $return = [];
            foreach ($this->getComments() as $comment) {
                $pattern = '/([\s]*\**[\s]*@)(?<key>[a-zA-Z0-9_]+)(?<value>([\s\S](?!(\*\ ?\@)))+)/m';
                if (preg_match_all($pattern, $comment, $matches)) {
                    $data = array_combine($matches['key'], $matches['value']);
                    foreach ($data as $key => $value) {
                        $key = strtolower(trim($key));
                        $value = trim($value, " *\r\n/");
                        $regex = '/^[\s]+\*[\s](?<content>.+)/m';
                        $return[$key][] = preg_replace_callback($regex, function ($item) {
                            return $item['content'];
                        }, $value);
                    }
                }
            }

            $parser = new Parser();
            foreach ($return as $key => $value) {
                if (method_exists($parser, $key)) {
                    $return[$key] = call_user_func([$parser, $key], $value);
                }
            }

            $this->parseContent = $return;
        }

        return $this->parseContent;
    }

    /**
     * 获取注释内容
     * @return array
     */
    public function getComments(): array
    {
        return $this->comment;
    }

    /**
     * 获取接口请求头
     * @return array
     */
    function getHeader(): array
    {
        return $this->getComment('header', []);
    }

    /**
     * 根据key获取注解内容
     * @param string $key
     * @param array $default
     * @return mixed
     */
    public function getComment(string $key, $default = [])
    {
        $key = strtolower(trim($key));
        return $this->parseContent[$key] ?? $default;
    }

    /**
     * 接口是否已经被废弃
     * 在生成的文档中将会增加`废弃`标识
     * @return bool
     */
    function isDeprecated(): bool
    {
        return $this->getComment('deprecated', false);
    }

    /**
     * 在生成文档或者测试的过程中是否忽略
     * @return bool
     */
    function isIgnore(): bool
    {
        return $this->getComment('ignore', false);
    }

    /**
     * 清空解析结果
     */
    private function clearParseContent()
    {
        $this->parseContent = [];
    }
}
