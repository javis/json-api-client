<?php
namespace Javis\JsonApi\Schema;

class Links
{
    /**
     * @var array
     */
    protected $links;

    /**
     * @param array $links
     * @return $this
     */
    public static function createFromArray(array $links)
    {
        $linkObjects = [];
        foreach ($links as $name => $value) {
            if (is_string($value)) {
                $linkObjects[$name] = Link::createFromString($value);
            } elseif (is_array($value)) {
                $linkObjects[$name] = Link::createFromArray($value);
            }
        }

        return new self($linkObjects);
    }

    /**
     * @param array $links
     */
    public function __construct(array $links)
    {
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var \Javis\JsonApi\Schema\Link $link */
            $links[$rel] = $link->toArray();
        }

        return $links;
    }

    /**
     * @return bool
     */
    public function hasSelf()
    {
        return $this->link("self") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function self()
    {
        return $this->link("self");
    }

    /**
     * @return bool
     */
    public function hasRelated()
    {
        return $this->link("related") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function related()
    {
        return $this->link("related");
    }

    /**
     * @return bool
     */
    public function hasFirst()
    {
        return $this->link("first") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function first()
    {
        return $this->link("first");
    }

    /**
     * @return bool
     */
    public function hasLast()
    {
        return $this->link("last") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function last()
    {
        return $this->link("last");
    }

    /**
     * @return bool
     */
    public function hasPrev()
    {
        return $this->link("prev") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function prev()
    {
        return $this->link("prev");
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->link("next") !== null;
    }

    /**
     * @return \Javis\JsonApi\Schema\Link
     */
    public function next()
    {
        return $this->link("next");
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasLink($name)
    {
        return $this->hasLink($name) !== null;
    }

    /**
     * @param $name
     * @return mixed $links
     */
    public function link($name)
    {
        return isset($this->links[$name]) ? $this->links[$name] : null;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return empty($this->links) === false;
    }

    /**
     * @return array
     */
    public function links()
    {
        return $this->links;
    }
}
