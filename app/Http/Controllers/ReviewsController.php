<?php

namespace App\Http\Controllers;

use App\Models\Plug;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    /**
     * @param Request $request
     * @param null $stationId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $stationId)
    {
        $reviews = Review::where('station_id', $stationId)->get();

        $response_data['data'] = $reviews;
        return response()->json($response_data);
    }
}
