<?php


namespace Annotate\Library\DocBuilder;


use Annotate\Library\AnnotateRoute;
use stdClass;

/**
 * Class Postman
 * @package Annotate\Library\DocBuilder
 */
class Postman extends BaseDocBuilder
{
    /**
     * 存储postman的数据
     * @var array
     */
    private $postman = [];

    /**
     * 生成文档前执行
     */
    function beforeBuild()
    {
        $this->postman['info'] = [
            '_postman_id' => $this->config['_postman_id'],
            'name' => $this->config['name'] ?? config('app.name'),
            'schema' => $this->config['schema'],
        ];

        $this->postman['protocolProfileBehavior'] = new stdClass();
    }

    /**
     * 生成API文档
     * @param array $annotate_route
     * @return string
     */
    function build(array $annotate_route): string
    {
        /** @var AnnotateRoute $route */
        foreach ($annotate_route as $route) {
            $name = $route->getName();
            if (empty($name)) {
                $name = current($route->getGroups());
            }
            if (empty($name)) {
                $name = $route->getUri();
            }

            $tmp = &$this->postman['item'];

            $groups = $route->getGroups();
            if (!empty($groups)) {
                foreach ($groups as $index => $group) {
                    $info = [
                        'name' => $group,
                        'item' => [],
                        'protocolProfileBehavior' => new stdClass()
                    ];
                    if ($index > 0) {
                        $info['_postman_isSubFolder'] = true;
                    }
                    $tmp[$group] = $info;
                    $tmp = &$tmp[$group]['item'];
                }
            }

            $tmp[] = [
                'name' => $name,
                'protocolProfileBehavior' => [
                    'disableBodyPruning' => true,
                ],
                'request' => [
                    'method' => current($route->getMethods()),
                    'header' => $this->getHeaders($route),
                    'body' => $this->getBody($route),
                    'url' => $this->getUrl($route),
                ],
                'response' => [],
            ];
        }

        $this->postman['item'] = $this->clearKey($this->postman['item']);

        return json_encode($this->postman, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * 生成postman格式的请求头
     * @param AnnotateRoute $route
     * @return array
     */
    private function getHeaders(AnnotateRoute $route): array
    {
        $data = [];
        $headers = $route->getHeaders();
        foreach ($headers as $key => $value) {
            $data[] = [
                'key' => $key,
                'value' => $value,
                'type' => 'text'
            ];
        }
        return $data;
    }

    /**
     * 生成postman格式的body
     * @param AnnotateRoute $route
     * @return array
     */
    private function getBody(AnnotateRoute $route, string $type = 'json'): array
    {
        switch ($type) {
            case 'json':
                return [
                    'mode' => 'raw',
                    'raw' => empty($route->getRequest()) ? '' : current($route->getRequest()),
                    'options' => [
                        'raw' => [
                            'language' => 'json',
                        ]
                    ]
                ];
        }
        return [];
    }

    /**
     * 生成postman的url部分
     * @param AnnotateRoute $route
     * @return array
     */
    private function getUrl(AnnotateRoute $route): array
    {
        return [
            'raw' => '{{domain}}/' . $route->getUri(),
            'host' => [
                '{{domain}}'
            ],
            'path' => explode('/', $route->getUri()),
            'query' => [],
        ];
    }

    /**
     * @param $data
     * @return array
     */
    private function clearKey($data): array
    {
        $t = [];
        foreach ($data as $k => $v) {
            if (isset($v['item']) && !empty($v['item'])) {
                $v['item'] = $this->clearKey($v['item']);
            }
            $t[] = $v;
        }
        return $t;
    }

    /**
     * 生成文档后执行
     */
    function afterBuild()
    {

    }
}
