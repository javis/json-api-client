<?php
namespace Javis\JsonApiClient;

use WoohooLabs\Yang\JsonApi\Schema\Document;
use Javis\JsonApiClient\Hydrator\ClassHydrator;

class JsonApiResponse
{
    public $response;
    public $body;
    public $data;
    public $errors;
    public $meta;
    public $status;

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     * @param bool $throwException
     * @return null
     */
    public function __construct($response)
    {
        $this->response = $response;

        $this->status = $this->response->getStatusCode();

        $rawResponseData = $this->response->getBody()->getContents();

        $this->body = $rawResponseData ? \GuzzleHttp\json_decode($rawResponseData, true) : '';

        $this->errors = isset($this->body['errors']) ? $this->body['errors'] : [];

        if (substr($this->status, 0, 1) != 2) {
            throw new \Exception("Error Processing Response: " . $this->errors, $this->status);
        }

        //Set data
        if ($this->body) {
            //This happens when array was expected but it is empty
            if (empty($this->body['data'])) {
                $this->data = [];
            } else {
                $hydrator = new ClassHydrator();
                $hydrated = $hydrator->hydrate(Document::createFromArray($this->body));
                $this->data = $hydrated;
            }
        }

        //Set meta
        if (isset($this->body['meta'])) {
            $this->meta = $this->body['meta'];
        }
    }


}
