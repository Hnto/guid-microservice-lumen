<?php
namespace App\Api\Endpoints;

use App\Api\Skeletons\Endpoint;
use App\Core\Helpers\Assist;
use App\Core\Requests\Params;
use Dingo\Api\Routing\Router;

class Resources implements Endpoint
{

    private $httpStatusCode = 200;

    public function execute(Params $params): array
    {
        return [
            'API' => [
                'message' => 'API is alive',
                'environment' => app()->environment(),
                'documentation' => '',

            ],
            'data' => [
                'resources' => [
                    Assist::getResources()
                ],
            ],
        ];
    }

    /**
     * Set the http status code
     *
     * @param int $code
     *
     * @return void
     */
    public function setHttpStatusCode(int $code)
    {
        //No code
    }

    /**
     * Get the http status code*
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}