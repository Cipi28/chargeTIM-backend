<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\FavouriteStations;
use App\Models\Plug;
use App\Models\Station;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    /**
     * @param Request $request
     * @param null $userId
     * @param null $statuses
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    const BOOKING_STATUS_BOOKED = 0;
    const BOOKING_STATUS_STARTED = 1;
    const BOOKING_STATUS_ENDED = 2;
    const BOOKING_STATUS_PENDING = 3;
    const BOOKING_STATUS_REJECTED = 4;
    public function index(Request $request, $userId = null)
    {
        $statuses = $request->statuses;
        $returnedBookings = [];

        foreach ($statuses as $status) {
            $bookings = null;
            if ($status === self::BOOKING_STATUS_BOOKED) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[ self::BOOKING_STATUS_BOOKED] = $bookings;
            } elseif ($status === self::BOOKING_STATUS_STARTED) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[ self::BOOKING_STATUS_STARTED] = $bookings;
            } elseif ($status === self::BOOKING_STATUS_ENDED) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[ self::BOOKING_STATUS_ENDED] = $bookings;
            } elseif ($status === self::BOOKING_STATUS_PENDING) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[ self::BOOKING_STATUS_PENDING] = $bookings;
            } elseif ($status === self::BOOKING_STATUS_REJECTED) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[ self::BOOKING_STATUS_REJECTED] = $bookings;
            }

            foreach ($bookings as $booking) {
            $booking->car_name = Car::where('id', $booking['car_id'])->first()->name;
            $booking->station_name = Station::where('id', $booking['station_id'])->first()->name;
            $booking->plug_type = Plug::where('id', $booking['plug_id'])->first()->type;
            }
        }

        $response_data['data'] = $returnedBookings;
        return response()->json($response_data);
    }

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
        $booking->status = $request->status;
        $booking->user_id = $request->userId;
        $booking->is_reviewed = $request->isReviewed ?? false;
        $booking->save();

        $response_data['data'] = $booking;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * * @param null $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, $id = null)
    {
        $booking = Booking::where('id', $id)->first();
        $booking->delete();

        $response_data['data'] = $booking;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * * @param null $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id = null)
    {
        $booking = Booking::where('id', $id)->first();
        $booking->car_id = $request->car_id;
        $booking->station_id = $request->station_id;
        $booking->plug_id = $request->plug_id;
        $booking->start_time = $request->start_time;
        $booking->end_time = $request->end_time;
        $booking->status = $request->status;
        $booking->user_id = $request->user_id;
        $booking->is_reviewed = $request->is_reviewed ?? false;
        $booking->save();

        $response_data['data'] = $booking;
        return response()->json($response_data);
    }
}
