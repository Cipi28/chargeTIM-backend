<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Station;
use App\Models\User;
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

    function truncateToTwoDecimals($number) {
        return floor($number * 100) / 100;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $station = Station::where('id', $request['stationId'])->first();

        $station->rating = self::truncateToTwoDecimals(($station->rating * $station->rating_count + $request['rating']) / ($station->rating_count + 1));
        $station->rating_count = $station->rating_count + 1;
        $station->save();


        $owner = User::where('id', $station->user_id)->first();

        $review = new Review();
        $review->station_id = $request['stationId'];
        $review->user_id = $request['userId'];
        $review->rating = $request['rating'];
        $review->comment = $request['comment'];
        $review->is_public_reviewer = $request['isPublic'] ?? false;
        $review->reviewer_name = $owner->name ?? null;
        $review->reviewer_photo =$owner->profile_photo ?? null;
        $review->published_at = $request['publishedAt'] ?? null;

        $review->save();

        $response_data['data'] = $review;

        return response()->json($response_data);
    }
}
