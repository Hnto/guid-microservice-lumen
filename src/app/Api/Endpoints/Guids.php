<?php

namespace App\Api\Endpoints;

use App\Api\Skeletons\Endpoint;
use App\Core\Guid\GuidRepository;
use App\Core\Helpers\ArrayData;
use App\Core\Requests\Params;
use App\Core\Token\TokenRepository;

class Guids implements Endpoint
{

    /**
     * Contains the http status code
     *
     * @var int
     */
    private $httpStatusCode = 200;

    /**
     * Contains the guid repository
     *
     * @var GuidRepository
     */
    private $guidRepository;

    /**
     * Guids constructor.
     *
     * @param GuidRepository $guidRepository
     */
    public function __construct(GuidRepository $guidRepository)
    {
        $this->guidRepository = $guidRepository;
    }

    /**
     * Execute api endpoint with params object
     *
     * @param Params $params
     *
     * @return array
     */
    public function execute(Params $params): array
    {
        switch ($params->getHttpMethod()) {
            case Endpoint::HTTP_METHOD_GET:

                if ($params->getRoute()->has('guid')) {
                    return $this->get(
                        $params->getRoute()->get('guid', '')
                    );
                }

                return $this->list();

                break;
            case Endpoint::HTTP_METHOD_PUT:

                return $this->put();

                break;
            case Endpoint::HTTP_METHOD_POST:

                return $this->post(
                    $params->getPost()->get('guid', ''),
                    $params->getPost()->get('assign_to', '')
                );

                break;
        }
    }

    /**
     * GET method for guids endpoint
     * This method retrieves all the guids
     * that have not been assigned yet
     * and are available
     *
     * @return array
     */
    public function list(): array
    {
        return $this->sendSuccessMessage(
            $this->guidRepository
                ->findAllNonAssigned()
        );
    }

    /**
     * GET method for Guids endpoint
     * This method retrieves the guid
     * object by using the guid provided
     * in the api request
     *
     * @param string $guid
     *
     * @return array
     */
    private function get(string $guid): array
    {
        $guid = $this->guidRepository
            ->findByValue($guid);

        if (!$guid->isValid()) {

            return $this->sendErrorMessage(
                'could not find guid',
                404
            );
        }

        return $this->sendSuccessMessage([
            'guid' => $guid->getGuid(),
            'status' => $guid->getStatus(),
            'assigned_to' => $guid->getAssignedTo(),
            'created_at' => $guid->getCreatedAt()
        ]);
    }

    /**
     * PUT method for Guids endpoint
     * This method creates a new guid
     * and returns the value of the guid
     * to the requested api user
     *
     * @return array
     */
    private function put(): array
    {
        $guid = $this->guidRepository
            ->create();

        if (!$guid->isValid()) {
            return $this->sendErrorMessage(
                'could not create guid',
                    500
            );
        }

        return $this->sendSuccessMessage([
            'guid' => $guid->getGuid(),
            'status' => $guid->getStatus()
        ]);
    }

    /**
     * POST method for Guids endpoint
     * This method assigns an issued
     * GUID to an assigned item
     * requested by the api user
     *
     * @param string $guid
     * @param string $assign_to
     *
     * @return array
     */
    private function post(string $guid, string $assign_to): array
    {
        if (!$this->guidRepository->findByValue($guid)->isValid()) {
            return $this->sendErrorMessage(
                'could not find provided guid',
                404
            );
        }

        if ($this->guidRepository->findByValue($guid)->isAssigned()) {
            return $this->sendErrorMessage(
                'guid is already assigned',
                409
            );
        }

        $guid = $this->guidRepository
            ->assignGuidTo($guid, $assign_to);

        if (!$guid->isValid()) {
            return $this->sendErrorMessage(
                'could not assign guid',
                500
            );
        }

        return $this->sendSuccessMessage([
            'guid' => $guid->getGuid(),
            'status' => $guid->getStatus(),
            'assigned_to' => $guid->getAssignedTo()
        ]);
    }

    /**
     * Send error message back to user
     *
     * @param string $message
     * @param int $code
     *
     * @return array
     */
    private function sendErrorMessage(string $message, int $code): array
    {
        //Set http status code
        $this->setHttpStatusCode($code);

        return [
            'status' => 'error',
            'message' => $message,
        ];
    }

    /**
     * Send a success message
     * back to the user with
     * an http status code OK (200)
     * with the provided data
     *
     * @param array $data
     *
     * @return array
     */
    private function sendSuccessMessage(array $data): array
    {
        //Set http status code
        $this->setHttpStatusCode(200);

        return ArrayData::data($data);
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
     * Get the http status code
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
