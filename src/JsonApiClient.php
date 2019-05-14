<?php
namespace Javis\JsonApiClient;

use \GuzzleHttp\Client;
use GuzzleHttp\Psr7;


/**
 * Stores a instance of HTTP Client object with all the configuration
 * required to access the API. This instance can be use to do multiple calls to
 * different endopoints of the API
 */
class JsonApiClient
{
    protected $client;
    protected $base_uri;
    protected $options;

    /**
     * @param \GuzzleHttp\Client $http_client
     */
    public function __construct($base_uri, array $options = [], Client $http_client = null)
    {
        $this->base_uri = Psr7\uri_for($base_uri);

        $this->options = $options;

        $this->client = (!empty($http_client))? $http_client: new Client($this->getOptions());
    }

    /**
     * Returns an instance of a Json Api Query to do a call to an API endpoint
     * @param  string $endpoint relative uri to the endpoint
     * @return JsonApiQuery
     */
    public function endpoint($endpoint)
    {
        $endpoint = Psr7\uri_for($endpoint);

        if ($endpoint->isAbsolute()){
            throw new \Exception("Endpoint must be a relative path");
        }

        return new JsonApiQuery($this, $endpoint);
    }

    /**
     * Get the Guzzle HTTP Options to use with the requests
     * @return array
     */
    protected function getOptions()
    {
        return array_merge_recursive([
            'base_uri' => $this->base_uri,
            'http_errors' => true,
            'headers' => ['Accept' => 'application/json']
        ],$this->options);
    }

    /**
     * [request description]
     * @param  [type] $method   [description]
     * @param  [type] $endpoint [description]
     * @param  [type] $options  [description]
     * @return [type]           [description]
     */
    public function request($method, $endpoint, array $options)
    {
        $response = $this->client->request($method, $endpoint, $options);

        return new JsonApiResponse($response);
    }


}
