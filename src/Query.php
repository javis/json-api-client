<?php
namespace Javis\JsonApi;

class Query
{
    // @var Client
    protected $api_client;
    protected $endpoint;
    protected $method;

    protected $includes = [];
    protected $fields = [];
    protected $filters = [];
    protected $multipart = false;
    protected $query = [];
    protected $limit;
    protected $offset;
    protected $formData;
    protected $jsonData;
    protected $json = [];
    protected $throwException = true;

    public function __construct(Client $api_client, $endpoint)
    {
        $this->api_client = $api_client;
        $this->endpoint = $endpoint;
    }

    /**
     * @param array $includes
     * @return $this
     */
    public function include(array $includes)
    {
        $this->includes = $includes;
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function filter(array $filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function withQuery(array $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function withFormData($data)
    {
        $this->formData = $data;

        foreach ($data as $d) {
            if ($d instanceof \SplFileInfo) {
                $this->multipart = true;
            }
        }

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function withJsonData($data)
    {
        $this->jsonData = $data;
        return $this;
    }

    /**
     * @param $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }



    /**
     * Build query params array
     * @return array
     */
    protected function buildQuery()
    {
        $query = [];
        if ($this->query) {
            $query = $this->query;
        }
        if ($this->limit || $this->offset) {
            $query['page'] = [];
            if ($this->limit) {
                $query['page']['limit'] = $this->limit;
            }
            if ($this->offset) {
                $query['page']['offset'] = $this->offset;
            }
        }

        if ($this->filters) {
            foreach ($this->filters as $resource => $columns) {
                if(is_array($columns)){
                    foreach ($columns as $column => $operands) {
                        foreach ($operands as $operand => $value) {
                            $query['filter'][$resource][$column][$operand] = is_array($value) ? implode(',',
                                $value) : $value;
                        }
                    }
                }
                else{
                    $query['filter'][$resource] = $columns;
                }
            }
        }
        if ($this->fields) {
            foreach ($this->fields as $resource => $fieldList) {
                $query['fields'][$resource] = implode(',', $fieldList);
            }
        }
        if ($this->includes) {
            $query['include'] = implode(',', $this->includes);
        }
        return $query;
    }

    /**
     * @return array
     */
    protected function getHeaders()
    {
        $headers = [];
        return $headers;
    }

    protected function request($type)
    {
        $params['headers'] = $this->getHeaders();
        $params['query'] = $this->buildQuery();

        if (isset($this->jsonData)) {
            $params['json'] = $this->jsonData;
        }

        if ($this->multipart) {
            $params['multipart'] = $this->convertFormDataIntoMultipart($this->formData);
        } else {
            $params['form_params'] = $this->formData;
        }

        return $this->api_client->request($type, $this->endpoint, $params);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function convertFormDataIntoMultipart($data = [])
    {
        $res = [];
        foreach ($data as $name => $value) {
            $row = ['name' => $name];

            if ($value instanceof \SplFileInfo) {
                $row['contents'] = fopen($value->getPath(), 'r');
                $row['filename'] = $value->getFilename();
            } else {
                $row['contents'] = $value;
            }

            $res[] = $row;
        }
        return $res;
    }

    /**
     * Do a GET request to API
     * @return api_clientResponse|null
     */
    public function get()
    {
        return $this->request('GET');
    }

    /**
     * Do a POST request to API
     * @return api_clientResponse|null
     */
    public function post()
    {
        return $this->request('POST');
    }

    /**
     * Do a PATCH request to API
     * @return api_clientResponse|null
     */
    public function patch()
    {
        return $this->request('PATCH');
    }

    /**
     * Do a DELETE request to API
     * @return api_clientResponse|null
     */
    public function delete()
    {
        return $this->request('DELETE');
    }






}
