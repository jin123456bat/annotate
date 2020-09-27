<?php


namespace Annotate\Library\Element;


/**
 * Class Param
 * @package Annotate\Library\Element
 */
class Param
{
    /**
     * 参数类型
     * @var string
     */
    public $type;
    /**
     * 参数名
     * @var string
     */
    public $name;
    /**
     * 参数是否必须
     * @var bool
     */
    public $is_required;
    /**
     * 参数说明
     * @var string
     */
    public $desc;
    /**
     * 参数选值范围
     * @var array
     */
    public $enum;
    /**
     * 参数示例
     * @var string
     */
    public $example;
    /**
     * 参数可选类型
     * @var string[]
     */
    protected $paramType = [
        'int',
        'bool',
        'boolean',
        'number',
        'float',
        'string',
        'array',
        'object',
        'decimal',
        'null',
        'json',
        'double',
        'date',
        'datetime',
        'short',
        'long',
    ];
    /**
     * 参数是否必须的可能的类型
     * @var bool[]
     */
    protected $paramIsRequired = [
        'optional' => false,
        'option' => false,
        'required' => true,
        'require' => true,
        0 => false,
        1 => true,
        '0' => false,
        '1' => true,
        'yes' => true,
        'no' => false,
        'y' => true,
        'n' => false
    ];

    /**
     * Param constructor.
     * @param array $param
     */
    function __construct(array $param)
    {
        $type = $name = $is_required = $desc = $enum = $example = null;

        if (count($param) == 1) {
            $name = $param[0];
        } else if (count($param) == 2) {
            list($type, $name) = $param;
            list($type, $name) = $this->getTypeName($type, $name);
        } else if (count($param) == 3) {
            list($type, $name, $is_required) = $param;
            if (!$this->isRequired($is_required)) {
                $desc = $is_required;
                $is_required = null;
            }
            list($type, $name) = $this->getTypeName($type, $name);
        } else {
            $type = array_shift($param);
            $name = array_shift($param);
            list($type, $name) = $this->getTypeName($type, $name);

            $is_required = false;
            if (!empty($param)) {
                $index = null;
                $is_required = $this->getIsRequired($param, $index);
                if (!is_null($index)) {
                    unset($param[$index]);
                    $param = array_values($param);
                }
            }

            $enum = [];
            if (!empty($param)) {
                $index = null;
                $enum = $this->getEnum($param, $index);
                if (!is_null($index)) {
                    unset($param[$index]);
                    $param = array_values($param);
                }
            }

            if (!empty($param)) {
                $index = null;
                $example = $this->getExample($param, $enum, $index);
                if (!is_null($index)) {
                    unset($param[$index]);
                    $param = array_values($param);
                }
            }

            if (count($param) == 1) {
                $desc = current($param);
            } else if (count($param) >= 2) {
                list($desc, $example) = $param;
            }
        }

        $this->type = $type;
        $this->name = $name;
        $this->example = $example;
        $this->desc = $desc;
        $this->enum = $enum;
        $this->is_required = $is_required;
    }

    /**
     * 猜测2个参数，哪个是类型 哪个是名称
     * @param string $type
     * @param string $name
     * @return array 返回一个包含2个元素的数组，第一个是猜测后的参数类型，第二个是猜测后的参数名称
     */
    private function getTypeName(string $type, string $name): array
    {
        if ($type[0] == '$' && $name[0] != '$') {
            return [
                $name,
                $type,
            ];
        } else if (in_array($name, $this->paramType)) {
            return [
                $name,
                $type,
            ];
        } else {
            $preg = '/^[a-zA-Z\$_][a-zA-Z\d_]*$/';
            if (preg_match($preg, $name) && !preg_match($preg, $type)) {
                return [
                    $type,
                    $name
                ];
            } else if (preg_match($preg, $type) && !preg_match($preg, $name)) {
                return [
                    $name,
                    $type
                ];
            }
        }

        return [
            $type,
            $name,
        ];
    }

    /**
     * 判断是否是参数必须项
     * @param string $is_required
     * @return bool
     */
    private function isRequired(string $is_required): bool
    {
        return $this->paramIsRequired[strtolower($is_required)] ?? false;
    }

    /**
     * 判断是否是必须
     * @param array $params
     * @param int|null $index
     * @return bool
     */
    private function getIsRequired(array $params, int &$index = null): bool
    {
        foreach ($params as $key => $value) {
            if ($this->isRequired($value)) {
                $index = $key;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取枚举
     * @param array $params
     * @param int $index
     * @return array
     */
    private function getEnum(array $params, int $index = null): array
    {
        $num = [];
        foreach ($params as $key => $value) {
            $num[$key] = substr_count($value, '|');
            $num[$key] += substr_count($value, ',');
        }
        arsort($num);

        $max_key = null;
        foreach ($num as $key => $value) {
            $max_key = $key;
            break;
        }

        if (!empty($max_key)) {
            if (strpos($params[$max_key], '|')) {
                $index = $max_key;
                return explode('|', $params[$max_key]);
            } else if (strpos($params[$max_key], ',')) {
                $index = $max_key;
                return explode(',', $params[$max_key]);
            }
        }
        return [];
    }

    /**
     * 获取参数示例
     * @param array $params
     * @param array $enum
     * @param null $index
     * @return string
     */
    private function getExample(array $params, array $enum, &$index = null): string
    {
        foreach ($params as $key => $param) {
            if (in_array($param, $enum)) {
                $index = $key;
                return $param;
            }
        }
        return '';
    }

    /**
     * 转化为字符串
     * @return string
     */
    function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * 转化为json
     * @return string
     */
    function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * 转化为数组
     * @return array
     */
    function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'desc' => $this->desc,
            'is_required' => $this->is_required,
            'enum' => $this->enum,
            'example' => $this->example,
        ];
    }

    /**
     * 参数是否有效
     * @return bool
     */
    public function isValid()
    {
        return in_array($this->type, $this->paramType);
    }
}
