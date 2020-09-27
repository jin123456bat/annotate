<?php


namespace Annotate\Library;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Class Route
 * @package Annotate\Library
 */
class AnnotateRoute
{

    /**
     * 请求地址
     * @var string
     */
    private $uri;

    /**
     * 请求方式
     * @var array
     */
    private $methods;

    /**
     * 控制器的完整类名
     * @var string
     */
    private $controller;

    /**
     * 执行的方法名称
     * @var string
     */
    private $controller_method;

    /**
     * 当前路由是否可以正常生成Annotate
     * @var bool
     */
    private $is_valid = true;

    /**
     * 路由的注解
     * @var Annotate
     */
    private $annotate;

    /**
     * Route constructor.
     * @param Route $route
     */
    function __construct(Route $route)
    {
        $this->uri = $route->uri();

        $this->methods = $route->methods();

        list($this->controller, $this->controller_method) = Str::parseCallback($route->getAction('uses'));

        if ($route->getActionName() == 'Closure') {
            $this->is_valid = false;
        }

        $this->getAnnotate()->parse();
    }

    /**
     * 获取路由的注解
     * @return Annotate
     */
    function getAnnotate(): Annotate
    {
        if (empty($this->annotate)) {
            $comment = $this->getControllerComment($this->controller, $this->controller_method, $reflection_method);
            $this->annotate = new Annotate($comment);
            $comment = $this->getFormRequestComment($reflection_method);
            $this->annotate->append($comment);
        }
        return $this->annotate;
    }

    /**
     * 获取控制器中方法的注解
     * @param string $controller
     * @param string $controller_method
     * @param $reflection_method
     * @return string
     */
    protected function getControllerComment(string $controller, string $controller_method, &$reflection_method): string
    {
        try {
            $reflection_class = new ReflectionClass($controller);
            $reflection_method = $reflection_class->getMethod($controller_method);
            return (string)$reflection_method->getDocComment();
        } catch (ReflectionException $e) {
            return '';
        }
    }

    /**
     * 如果方法中存在继承了FormRequest的参数，则尝试获取FormRequest的参数的注解
     * @param ReflectionMethod $reflectionMethod
     * @return string
     */
    protected function getFormRequestComment(ReflectionMethod $reflectionMethod): string
    {
        try {
            $reflectionParameters = $reflectionMethod->getParameters();
            foreach ($reflectionParameters as $reflectionParameter) {
                $reflection_parameter_type = (string)$reflectionParameter->getType();
                if (class_exists($reflection_parameter_type)) {
                    $reflection_parameter_type_class = new ReflectionClass($reflection_parameter_type);
                    $reflection_parent_class = $this->getParentClasses($reflection_parameter_type_class);
                    if (in_array(FormRequest::class, $reflection_parent_class)) {
                        return (string)$reflection_parameter_type_class->getDocComment();
                    }
                }
            }
        } catch (ReflectionException $e) {
            return '';
        }
        return '';
    }

    /**
     * 获取所有继承的类
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function getParentClasses(ReflectionClass $reflectionClass): array
    {
        $parents = [];
        while ($parent = $reflectionClass->getParentClass()) {
            $parents[] = $parent->getName();
            $reflectionClass = $parent;
        }
        return $parents;
    }

    /**
     * 请求地址
     * @return string
     */
    function getUri(): string
    {
        return $this->uri;
    }

    /**
     * 接口请求头
     * key => value格式
     * @return array
     */
    function getHeaders(): array
    {
        return $this->getAnnotate()->getHeader();
    }

    /**
     * 获取接口的请求参数
     * @return array
     */
    function getParams(): array
    {
        return $this->getAnnotate()->getComment('param', []);
    }

    /**
     * 接口名称
     * @return string
     */
    function getName(): string
    {
        return $this->getAnnotate()->getComment('name', '');
    }

    /**
     * 接口是否已被废除
     * @return bool
     */
    function isDeprecated(): bool
    {
        return $this->getAnnotate()->isDeprecated();
    }

    /**
     * 获取分组信息
     * @return array
     */
    function getGroups(): array
    {
        return array_merge($this->getAnnotate()->getComment('package', []), $this->getAnnotate()->getComment('group', []));
    }

    /**
     * 获取请求参数示例
     * @return array
     */
    function getRequest(): array
    {
        return $this->getAnnotate()->getComment('request', []);
    }

    /**
     * 请求方式
     * @return array
     */
    function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * 当前路由是否可以正常生成api文档
     * @return bool
     */
    function isValid(): bool
    {
        if ($this->is_valid) {
            return !$this->getAnnotate()->isIgnore();
        }
        return $this->is_valid;
    }
}
