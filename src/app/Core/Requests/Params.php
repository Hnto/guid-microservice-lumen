<?php

namespace App\Core\Requests;

use Illuminate\Support\Collection;

class Params
{

    /**
     * Contains a collection
     * object with post params
     *
     * @var Collection
     */
    private $post;

    /**
     * Contains a collection
     * object with query params
     *
     * @var Collection
     */
    private $query;

    /**
     * Contains a collection
     * object with route params
     *
     * @var Collection
     */
    private $route;

    /**
     * Contains the http
     * method that was used
     *
     * @var string
     */
    private $httpMethod;

    /**
     * Params constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->post = isset($data['post']) ? new Collection($data['post']) : new Collection([]);
        $this->query = isset($data['query']) ? new Collection($data['query']) : new Collection([]);
        $this->route = isset($data['route-params']) ? new Collection($data['route-params']) : new Collection([]);
        $this->httpMethod = isset($data['http-method']) ? $data['http-method'] : false;
    }

    /**
     * @return Collection
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Collection
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return Collection
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }
}
