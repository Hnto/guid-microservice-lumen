<?php

namespace App\Api\Endpoints;

use App\Api\Skeletons\Endpoint;
use App\Core\Requests\Params;
use App\Core\Token\TokenRepository;

class Authenticate implements Endpoint
{

    const STATUS_AUTHENTICATED = 'authenticated';

    private $httpStatusCode = 200;

    /**
     * Execute api endpoint with params object
     *
     * @param Params $params
     *
     * @return array
     */
    public function execute(Params $params): array
    {
        /** @var TokenRepository $tokenRepo */
        $tokenRepo = app(TokenRepository::class);
        $token = $tokenRepo->createToken();

        return [
            'status' => self::STATUS_AUTHENTICATED,
            'value' => $token->getValue(),
            'expires' => $token->getEndsAt()
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
        $this->httpStatusCode = $code;
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
