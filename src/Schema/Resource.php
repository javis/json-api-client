<?php
namespace Javis\JsonApi\Schema;

class Resource
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \Javis\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var \Javis\JsonApi\Schema\Relationship[]
     */
    protected $relationships;

    /**
     * @param array $array
     * @param \Javis\JsonApi\Schema\Resources $resources
     */
    public function __construct($array, Resources $resources)
    {
        $this->type = empty($array["type"]) ? "" : $array["type"];
        $this->id = empty($array["id"]) ? "" : $array["id"];
        $this->meta = $this->isArrayKey($array, "meta") ? $array["meta"] : [];
        $this->links = Links::createFromArray($this->isArrayKey($array, "links") ? $array["links"] : []);
        $this->attributes = $this->isArrayKey($array, "attributes") ? $array["attributes"] : [];

        $this->relationships = [];
        if ($this->isArrayKey($array, "relationships")) {
            foreach ($array["relationships"] as $name => $relationship) {
                $this->relationships[$name] = new Relationship($name, $relationship, $resources);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            "type" => $this->type,
            "id" => $this->id
        ];

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        if ($this->links) {
            $result["links"] = $this->links->toArray();
        }

        if (empty($this->attributes) === false) {
            $result["attributes"] = $this->attributes;
        }

        if (empty($this->relationships) === false) {
            $result["relationships"] = [];
            foreach ($this->relationships as $name => $relationship) {
                $result["relationships"][$name] = $relationship->toArray();
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasMeta()
    {
        return empty($this->meta) === false;
    }

    /**
     * @return array
     */
    public function meta()
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return $this->links->hasLinks();
    }

    /**
     * @return \Javis\JsonApi\Schema\Links
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function attribute($name)
    {
        return $this->hasAttribute($name) ? $this->attributes[$name] : null;
    }

    /**
     * @return Relationship[]
     */
    public function relationships(): array
    {
        return $this->relationships;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRelationship($name)
    {
        return array_key_exists($name, $this->relationships);
    }

    /**
     * @param string $name
     * @return \Javis\JsonApi\Schema\Relationship|null
     */
    public function relationship($name)
    {
        return $this->hasRelationship($name) ? $this->relationships[$name] : null;
    }

    /**
     * @param array $array
     * @param string $key
     * @return bool
     */
    protected function isArrayKey($array, $key)
    {
        return isset($array[$key]) && is_array($array[$key]);
    }
}
