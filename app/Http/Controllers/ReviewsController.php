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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $review = new Review();
        $review->station_id = $request['stationId'];
        $review->user_id = $request['userId'];
        $review->rating = $request['rating'];
        $review->comment = $request['comment'];
        $review->is_public_reviewer = $request['isPublic'] ?? false;
        $review->reviewer_name = $request['reviewerName'] ?? null;
        $review->reviewer_photo = $request['reviewerPhoto'] ?? null;
        $review->published_at = $request['publishedAt'] ?? null;

        $review->save();

        $response_data['data'] = $review;

        return response()->json($response_data);
    }
}
