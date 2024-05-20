<?php

namespace App\Http\Controllers;

use App\Models\FavouriteStations;
use App\Models\Station;
use App\Models\Users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class StationsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
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
                $station->user_id = null;

                $station->save();
            }
        }
        $returnStations = Station::all();
        $returnStations->transform(function($station) {
            $station->latitude = floatval($station->latitude);
            $station->longitude = floatval($station->longitude);
            return $station;
        });

        $response_data['data'] = $returnStations;

        return response()->json($response_data);
    }
}
