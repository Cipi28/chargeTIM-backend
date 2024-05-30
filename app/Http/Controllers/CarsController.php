<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarsController
{
    /**
     * @param Request $request
     * @param null $distributionListId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request, $userId = null)
    {
        $cars = Car::where('user_id', $userId)->get();

        $response_data['data'] = $cars;

        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $distributionListId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, $userId = null)
    {
        $car = new Car();
        $car->user_id = $userId;
        $car->name = $request->name;
        $car->plate = $request->plate;
        $car->plug_type = $request->plug_type;
        $car->image = $request->image;
        $car->save();

        $response_data['data'] = $car;

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
        $car = Car::where('id', $id)->first();
        $car->delete();

        $response_data['data'] = $car;
        return response()->json($response_data);
    }

    /**
     * @param Request $request
     * @param null $distributionListId
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id = null)
    {
        $car = Car::where('id', $id)->first();
        $car->name = $request->name;
        $car->plate = $request->plate;
        $car->plug_type = $request->plug_type;
        $car->image = $request->image;
        $car->save();

        $response_data['data'] = $car;
        return response()->json($response_data);
    }
}
