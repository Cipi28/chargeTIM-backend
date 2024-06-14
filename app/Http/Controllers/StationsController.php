<?php

namespace App\Http\Controllers;

use App\Models\FavouriteStations;
use App\Models\Plug;
use App\Models\Review;
use App\Models\Station;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

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
        $plug->kw_power = $request->stationPlug["kwPower"];
        $plug->cost_per_kw = $request->stationPlug["costPerKw"];
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
        $stations = Station::where('is_public', false)->where('user_id', $userId)->get();

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
}
