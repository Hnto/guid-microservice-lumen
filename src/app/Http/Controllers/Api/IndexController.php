<?php

namespace App\Http\Controllers\Api;

use App\Api\EndpointFactory;
use App\Core\Helpers\Assist;
use App\Core\Helpers\Parameters;
use App\Core\Requests\Params;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __construct()
    {
    }

    public function resource(Request $request)
    {
        $endpoint = EndpointFactory::create(
            Assist::extractResourceFromPath(
                $request
            )
        );

        return response($endpoint->execute(new Params(Parameters::extractBaseFilters($request))))
            ->setStatusCode($endpoint->getHttpStatusCode())
            ->send();
    }

    public function init(Request $request)
    {
        return $this->resource($request);
    }
}
