<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FavouriteStations;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $booking = new Booking();
        $booking->car_id = $request->carId;
        $booking->station_id = $request->stationId;
        $booking->plug_id = $request->plugId;
        $booking->start_time = $request->startDate;
        $booking->end_time = $request->endDate;
        $booking->save();

        $response_data['data'] = $booking;
        return response()->json($response_data);
    }
}
