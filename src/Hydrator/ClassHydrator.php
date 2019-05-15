<?php
namespace Javis\JsonApi\Hydrator;

use stdClass;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

/**
 * Imported from WoohooLabs Yang to bring this feature to PHP 5.5
 */
class ClassHydrator
{
    /**
     * @return stdClass[]|stdClass
     */

    public function hydrate(Document $document)
    {
        if ($document->hasAnyPrimaryResources() === false) {
            return new stdClass();
        }
        if ($document->isSingleResourceDocument()) {
            return $this->hydratePrimaryResource($document);
        }
        return $this->hydratePrimaryResources($document);
    }

    public function hydrateObject(Document $document)
    {
        if ($document->isSingleResourceDocument() === false) {
            return new stdClass();
        }
        if ($document->hasAnyPrimaryResources() === false) {
            return new stdClass();
        }
        return $this->hydratePrimaryResource($document);
    }

    /**
     * @return stdClass[]
     */
    public function hydrateCollection(Document $document)
    {
        if ($document->hasAnyPrimaryResources() === false) {
            return [];
        }
        if ($document->isSingleResourceDocument()) {
            return [$this->hydratePrimaryResource($document)];
        }
        return $this->hydratePrimaryResources($document);
    }

    private function hydratePrimaryResources(Document $document)
    {
        $result = [];
        $resourceMap = [];
        foreach ($document->primaryResources() as $primaryResource) {
            $result[] = $this->hydrateResource($primaryResource, $document, $resourceMap);
        }
        return $result;
    }

    private function hydratePrimaryResource(Document $document)
    {
        $resourceMap = [];
        return $this->hydrateResource($document->primaryResource(), $document, $resourceMap);
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function hydrateResource(ResourceObject $resource, Document $document, array &$resourceMap)
    {
        // Fill basic attributes of the resource
        $result = new stdClass();
        $result->type = $resource->type();
        $result->id = $resource->id();
        foreach ($resource->attributes() as $attribute => $value) {
            $result->{$attribute} = $value;
        }
        //Save resource to the identity map
        $this->saveObjectToMap($result, $resourceMap);
        //Fill relationships
        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $object = $this->getObjectFromMap($link["type"], $link["id"], $resourceMap);
                if ($object === null && $document->hasIncludedResource($link["type"], $link["id"])) {
                    $relatedResource = $document->resource($link["type"], $link["id"]);
                    $object = $this->hydrateResource($relatedResource, $document, $resourceMap);
                }
                if ($object === null) {
                    continue;
                }
                if ($relationship->isToOneRelationship()) {
                    $result->{$name} = $object;
                } else {
                    $result->{$name}[] = $object;
                }
            }
        }
        return $result;
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function getObjectFromMap(string $type, string $id, array $resourceMap)
    {
        return $resourceMap[$type . "-" . $id] ?? null;
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function saveObjectToMap(stdClass $object, array &$resourceMap)
    {
        $resourceMap[$object->type . "-" . $object->id] = $object;
    }
}
