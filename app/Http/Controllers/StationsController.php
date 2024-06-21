<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Plug;
use App\Models\Review;
use App\Models\Station;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DateTime;

class StationsController extends Controller
{
    const BOOKING_TYPES = [
    'EV_CONNECTOR_TYPE_TYPE_1',
    'EV_CONNECTOR_TYPE_TYPE_2',
    'EV_CONNECTOR_TYPE_CCS_COMBO_1',
    'EV_CONNECTOR_TYPE_CCS_COMBO_2',
    'EV_CONNECTOR_TYPE_CHADEMO',
    'EV_CONNECTOR_TYPE_GB/T',
    'EV_CONNECTOR_TYPE_TESLA',
];


    /**
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $id = null)
    {
        $station = Station::where('id', $id)->first();

        $response_data['data'] = $station;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addStation(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4|max:25',
            'address' => 'required|min:4|max:50',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'open_periods' => 'required',
            'phone_number' => 'numeric',
            'website_link' => 'url',
        ]);

        $station = new Station();
        $station->name = $request->name;
        $station->website_URL = $request->websiteLink;
        $station->adress = $request->address;
        $station->phone = $request->phoneNumber ?? null;
        $station->image = $request->image ?? null;
        $station->maps_URL = null;
        $station->is_public = $request->is_public ?? false;
        $station->latitude = $request->latitude;
        $station->longitude = $request->longitude;
        $station->public_id = null;
        $station->open_periods = $request->open_periods;
        $station->user_id = $request->user_id;
        $station->rating = 0;
        $station->rating_count = 0;
        $station->save();


        $plug = new Plug();

        $plug->station_id = $station->id;
        $plug->type = $request->stationPlug["plugType"];
        $plug->kw_power = $request->stationPlug["kwPower"] ?? 20;
        $plug->cost_per_kw = $request->stationPlug["costPerKw"] ?? 0;
        $plug->status = $request->stationPlug["status"];

        $plug->save();

        $response_data['data'] = $station;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $stations = $request->stations;

        foreach ($stations as $newStation) {
            $station = Station::where('public_id', $newStation['publicId'])->first();

            if (!$station) {
                $station = new Station();
                $station->name = $newStation['name'];
                $station->public_id = $newStation['publicId'];
                $station->adress = $newStation['adress'];
                $station->image = $newStation['image'];
                $station->phone = $newStation['phone'];
                $station->open_periods = $newStation['openPeriods'];
                $station->maps_URL = $newStation['mapsURL'];
                $station->website_URL = $newStation['websiteURL'];
                $station->latitude = floatval($newStation['latitude']);
                $station->longitude = floatval($newStation['longitude']);
                $station->is_public = $newStation['isPublic'];
                $station->rating = $newStation['rating'] ?? 0;
                $station->rating_count = $newStation['ratingCount'] ?? 0;
                $station->user_id = null;

                $station->save();

                foreach ($newStation['plugs'] as $newPlug) {
                    for($i = 0; $i < $newPlug['count']; $i++) {
                        $plug = new Plug();

                        $plug->station_id = $station->id;
                        $plug->type = array_search($newPlug['type'], self::BOOKING_TYPES);
                        $plug->kw_power = $newPlug['kwPower'];
                        $plug->cost_per_kw = $newPlug['costPerKw'];
                        $plug->status = $newPlug['status'];

                        $plug->save();

                    }
                }

                foreach ($newStation['reviews'] as $newReview) {
                    $review = new Review();

                    $review->comment = $newReview['comment'];
                    $review->rating = $newReview['rating'];
                    $review->reviewer_name = $newReview['reviewerName'] ?? null;
                    $review->reviewer_photo = $newReview['reviewerPhoto'] ?? null;
                    $review->is_public_reviewer = $newReview['isPublicReviewer'] ?? false;
                    $review->published_at = $newReview['publishedAt'] ?? null;
                    $review->station_id = $station->id;
                    $review->user_id = $newReview['userId'] ?? null;

                    $review->save();
                }
            };

        }
        $returnStations = Station::all();
        $returnStations->transform(function($station) {
            $station->latitude = floatval($station->latitude);
            $station->longitude = floatval($station->longitude);
            $station->rating = floatval($station->rating);
            $station->rating_count = intval($station->rating_count);

            //for every station add a list with all the plug types their plugs have
            $station->plug_types = Plug::where('station_id', $station->id)->get()->pluck('type')->unique()->values()->all();

            if(!$station->is_public) {
                $owner = User::where('id', $station->user_id)->first();
                $station->owner_name = $owner->name ?? null;
                $station->owner_mail = $owner->email ?? null;
            }
            return $station;
        });

        $response_data['data'] = $returnStations;

        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStations(Request $request)
    {
        $stations = Station::all();

        foreach ($stations as $station) {
            if(!$station->is_public) {
                $owner = User::where('id', $station->user_id)->first();
                $station->owner_name = $owner->name ?? null;
                $station->owner_mail = $owner->email ?? null;
            }
        }

        $response_data['data'] = $stations;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param integer $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getUserStations(Request $request, $userId = null)
    {
        $stations = Station::where('is_public', false)->where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        $response_data['data'] = $stations;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, $id = null)
    {
        $plugs = Plug::where('station_id', $id)->get();
        foreach ($plugs as $plug) {
            $plug->delete();
        }

        $car = Station::where('id', $id)->first();
        $car->delete();

        $response_data['data'] = $car;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param integer $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getChartsData(Request $request, $userId = null)
    {
        $chartsData = [];
        $userStations = Station::where('is_public', false)->where('user_id', $userId)->get();


        //PIE CHART
        //get all bookings for the user's stations
        $bookings = [];
        foreach ($userStations as $station) {
            $stationBookings = Booking::where('station_id', $station->id)->whereIn('status', [0, 1, 2])->get();
            $bookings = array_merge($bookings, $stationBookings->toArray());
        }
        //mane an array with the number of bookings for each station
        $bookingsPerStation = [];
        foreach ($bookings as $booking) {
            if(array_key_exists($booking['station_id'], $bookingsPerStation)) {
                $bookingsPerStation[$booking['station_id']]++;
            } else {
                $bookingsPerStation[$booking['station_id']] = 1;
            }
        }
        //make percentage values for the bookings
        $dataPercentage = [];
        $totalBookings = count($bookings);
        foreach ($bookingsPerStation as $key => $value) {
            $dataPercentage[] = (double)number_format(($value / $totalBookings) * 100, 2);
        }
        $pieChartData = [];

        $pieChartData['data'] = $dataPercentage;
        $pieChartData['stationNames'] = $userStations->pluck('name')->toArray();
        $pieChartData['totalBookings'] = $totalBookings;


        //BAR CHART
        //get an array of all 7 timestamps of the days og the current week
        $days = [];
        $today = strtotime('today');

        // Find the start of the current week (Monday)
        $startOfWeek = strtotime('last Monday', $today);
        if (date('l', $today) == 'Monday') {
            $startOfWeek = $today; // If today is Monday, set the start of the week to today
        }

        // Loop through the current week and add each day to the array
        for ($i = 0; $i < 7; $i++) {
            $days[] = date('Y-m-d', strtotime("+$i day", $startOfWeek));
        }
        $numberOfBookingsPerDay = [];
        foreach ($days as $day) {
            $bookings = Booking::where('start_time', '>=', $day . ' 00:00:00')->where('end_time', '<=', $day . ' 23:59:59')->whereIn('station_id', $userStations->pluck('id'))->whereIn('status', [0, 1, 2])->get();
            $numberOfBookingsPerDay[] = count($bookings);
        }

        $barChartData = [];
        $barChartData['data'] = $numberOfBookingsPerDay;
        $barChartData['days'] = $days[0] . ' - ' . $days[6];
        $barChartData['totalWeekBookings'] = array_sum($numberOfBookingsPerDay);


        //LINE CHART
        $lineChartData = [];
        $months = [];
        $year = date('Y'); // Get the current year
        $totalBookings = 0;

        // Loop through each month of the current year
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('Y-m', strtotime("$year-$i-01"));
        }
        foreach ($userStations as $station) {
            foreach ($months as $month) {
                // Get the first day of the month
                $firstDay = "$month-01";
                // Create a DateTime object and find the last day of the month
                $date = new DateTime($firstDay);
                $lastDay = $date->format('Y-m-t'); // 'Y-m-t' gives the last day of the month

                // Fetch bookings for the station in the given month
                $bookings = Booking::where('station_id', $station->id)
                    ->where('start_time', '>=', "$firstDay 00:00:00")
                    ->where('end_time', '<=', "$lastDay 23:59:59")
                    ->whereIn('status', [0, 1, 2])
                    ->get();

                // Count the bookings and add to the line chart data
                $lineChartData[$station->name][] = count($bookings);
                $totalBookings += count($bookings);
            }
        }

        $lineChartDataComplete = [];

        foreach ($lineChartData as $key => $value) {
            $lineChartDataComplete[] = [
                'name' => $key,
                'data' => $value
            ];
        }
        $lineChartDataOutput = [];
        $lineChartDataOutput['data'] = $lineChartDataComplete;
        $lineChartDataOutput['totalBookings'] = $totalBookings;

        $chartsData['pieChartData'] = $pieChartData;
        $chartsData['barChartData'] = $barChartData;
        $chartsData['lineChartData'] = $lineChartDataOutput;

        $response_data['data'] = $chartsData;
        return response()->json($response_data);
    }
}
