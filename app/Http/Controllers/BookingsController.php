<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\FavouriteStations;
use App\Models\Plug;
use App\Models\Station;
use App\Models\User;
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
    const USER_ROLE = 0;
    const CONTRIBUTOR_ROLE = 1;

    const BOOKING_STATUS_ACTIVE = 0;
    const BOOKING_STATUS_STARTED = 1;
    const BOOKING_STATUS_ENDED = 2;
    const BOOKING_STATUS_PENDING = 3;
    const BOOKING_STATUS_REJECTED = 4;
    public function index(Request $request, $userId = null)
    {
        date_default_timezone_set('Europe/Bucharest');

        $statuses = $request->statuses;
        $role = $request->role;
        $returnedBookings = [];

        $allBookings = Booking::all();

        foreach ($allBookings as $booking) {

            if($booking->status === self::BOOKING_STATUS_ACTIVE) {
                if($booking->start_time <= date('Y-m-d H:i:s')) {
                    if($booking->end_time > date('Y-m-d H:i:s')) {
                        $booking->status = self::BOOKING_STATUS_STARTED;
                    } else {
                        $booking->status = self::BOOKING_STATUS_ENDED;
                    }
                    $booking->save();
                }
            }

            if($booking->status === self::BOOKING_STATUS_STARTED) {
                if($booking->end_time <= date('Y-m-d H:i:s')) {
                    $booking->status = self::BOOKING_STATUS_ENDED;
                    $booking->save();
                }
            }
        }


        if($role === self::USER_ROLE) {

            foreach ($statuses as $status) {
                $bookings = Booking::where('user_id', $userId)->where('status', $status)->get();
                $returnedBookings[$status] = $bookings;

                foreach ($bookings as $booking) {
                    $booking->car_name = Car::where('id', $booking['car_id'])->first()->name;
                    $booking->station_name = Station::where('id', $booking['station_id'])->first()->name;
                    $booking->plug_type = Plug::where('id', $booking['plug_id'])->first()->type;
                }
            }
        } else {
            foreach ($statuses as $status) {
                $bookings = Booking::where('status', $status)->get();

                $filteredBookings = $bookings->filter(function ($booking) use ($userId) {
                    $station = Station::where('id', $booking->station_id)->first();
                    return $station->user_id === intval($userId);
                });

                foreach ($filteredBookings as $booking) {
                    $booking->car_name = Car::where('id', $booking['car_id'])->first()->name;
                    $booking->station_name = Station::where('id', $booking['station_id'])->first()->name;
                    $booking->plug_type = Plug::where('id', $booking['plug_id'])->first()->type;
                    $booking->user_info = User::where('id', $booking['user_id'])->first();
                }

                $returnedBookings[$status] = array_values($filteredBookings->toArray());
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
    public function verify(Request $request)
    {
        $newStartDateRaw = new \DateTime($request->startDate);
        $newStartDate = $newStartDateRaw->format('Y-m-d H:i:s');
        $newEndDateRaw = new \DateTime($request->endDate);
        $newEndDate = $newEndDateRaw->format('Y-m-d H:i:s');

        $bookings = Booking::where('plug_id', $request->plugId)->get()->unique();
        $conflictingBookings = [];
        foreach ($bookings as $booking) {
            $bookingStartDate = new \DateTime($booking->start_time);
            if ($booking->start_time <= $newStartDate && $booking->end_time >= $newStartDate) {
                $conflictingBookings[] = $booking;
                continue;
            }
            if ($booking->start_time <= $newEndDate && $booking->end_time >= $newEndDate) {
                $conflictingBookings[] = $booking;
                continue;
            }
            if ($booking->start_time >= $newStartDate && $booking->end_time <= $newEndDate) {
                $conflictingBookings[] = $booking;
            }
        }

        $response_data['data'] = $conflictingBookings;
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

    /**
     * @param Request $request
     * * @param null $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateStatus(Request $request, $id = null)
    {
        $booking = Booking::where('id', $id)->first();
        $booking->status = $request->status;
        $booking->save();

        $response_data['data'] = $booking;
        return response()->json($response_data);
    }
}
