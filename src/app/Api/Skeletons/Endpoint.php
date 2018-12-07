<?php
namespace App\Api\Skeletons;

use App\Core\Requests\Params;

interface Endpoint
{

    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';

    /**
     * Execute api endpoint with params object
     *
     * @param Params $params
     *
     * @return array
     */
    public function execute(Params $params): array;

    /**
     * Set the http status code
     *
     * @param int $code
     *
     * @return void
     */
    public function setHttpStatusCode(int $code);

    /**
     * Get the http status code*
     *
     * @return int
     */
    public function getHttpStatusCode(): int;
}
