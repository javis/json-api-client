<?php
namespace Javis\JsonApi\Schema;

class ErrorSource
{
    /**
     * @var string
     */
    private $pointer;

    /**
     * @var string
     */
    private $parameter;

    /**
     * @param array $source
     * @return $this
     */
    public static function fromArray(array $source)
    {
        $pointer = empty($source["pointer"]) === false ? $source["pointer"] : "";
        $parameter = empty($source["parameter"]) === false ? $source["parameter"] : "";

        return new self($pointer, $parameter);
    }

    /**
     * @param string $pointer
     * @param string $parameter
     */
    public function __construct($pointer, $parameter)
    {
        $this->pointer = $pointer;
        $this->parameter = $parameter;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $content = [];

        if ($this->pointer) {
            $content["pointer"] = $this->pointer;
        }

        if ($this->parameter) {
            $content["parameter"] = $this->parameter;
        }

        return $content;
    }

    /**
     * @return bool
     */
    public function hasSource()
    {
        return $this->hasPointer() || $this->hasParameter();
    }

    /**
     * @return bool
     */
    public function hasPointer()
    {
        return empty($this->pointer) === false;
    }

    /**
     * @return string
     */
    public function pointer()
    {
        return $this->pointer;
    }

    /**
     * @return bool
     */
    public function hasParameter()
    {
        return empty($this->parameter) === false;
    }

    /**
     * @return string
     */
    public function parameter()
    {
        return $this->parameter;
    }
}
