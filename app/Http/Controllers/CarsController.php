<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarsController
{
    /**
     * @param Request $request
     * @param null $distributionListId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $userId = null)
    {
        $cars = Car::where('user_id', $userId)->get();

        $response_data['data'] = $cars;

        return response()->json($response_data);
    }
}
