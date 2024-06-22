<?php

namespace App\Http\Controllers;

use App\Models\Plug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlugsController extends Controller
{
    /**
     * @param Request $request
     * @param null $stationId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $stationId)
    {
        $plugs = Plug::where('station_id', $stationId)->get();

        $response_data['data'] = $plugs;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $stationId
     * @param null $carPlugType
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getPlugsByCarPlugType(Request $request, $stationId, $carPlugType)
    {
        $plugs = Plug::where('station_id', $stationId)->where('type', $carPlugType)->get();

        $response_data['data'] = $plugs;
        return response()->json($response_data);
    }
}
