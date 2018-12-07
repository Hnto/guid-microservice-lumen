<?php
namespace App\Core\Helpers;

use Illuminate\Http\Request;

class Parameters
{

    /**
     * Extract base filters
     *
     * @param Request $request
     * @return array
     */
    public static function extractBaseFilters(Request $request)
    {
        return [
            'post' => $request->request,
            'query' => $request->query->all(),
            'route-params' => app('request')->route()[2],
            'http-method' => $request->method()
        ];
    }
}
