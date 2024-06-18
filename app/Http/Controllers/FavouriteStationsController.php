<?php

namespace App\Http\Controllers;

use App\Models\FavouriteStations;
use App\Models\Plug;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteStationsController extends Controller
{
    /**
     * @param Request $request
     * @param null $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $userId)
    {
        $favouritestations = FavouriteStations::where('user_id', $userId)->pluck('station_id');
        $stations = Station::whereIn('id', $favouritestations)->get();

        $stations->transform(function($station) {

            $station->plug_types = Plug::where('station_id', $station->id)->get()->pluck('type')->unique()->values()->all();

            if(!$station->is_public) {
                $owner = User::where('id', $station->user_id)->first();
                $station->owner_name = $owner->name ?? null;
                $station->owner_mail = $owner->email ?? null;
            }



            return $station;
        });

        $response_data['data'] = $stations;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $userId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFavouriteStationsIndex(Request $request, $userId)
    {
        $stationsId = FavouriteStations::where('user_id', $userId)->pluck('station_id');

        $response_data['data'] = $stationsId;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $userId
     * @param null $stationId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, $userId, $stationId)
    {
        $favStation = FavouriteStations::withTrashed()->where('user_id', $userId)->where('station_id', $stationId)->first();

        if($favStation) {
            $favStation->restore();
        } else {
            $favStation = new FavouriteStations();
            $favStation->user_id = $userId;
            $favStation->station_id = $stationId;
            $favStation->save();
        }

        $stationsId = FavouriteStations::where('user_id', $userId)->pluck('station_id');

        $response_data['data'] = $stationsId;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param integer $userId
     * @param integer $stationId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, $userId, $stationId)
    {
        $favStation = FavouriteStations::where('user_id', $userId)->where('station_id', $stationId)->first();
        $favStation->delete();

        $stationsId = FavouriteStations::where('user_id', $userId)->pluck('station_id');

        $response_data['data'] = $stationsId;
        return response()->json($response_data);
    }

}
