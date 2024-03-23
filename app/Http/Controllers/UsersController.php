<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $limit = 5;

    // Index
    public function index()
    {
        dd("test!!!");
        $users = Users::paginate($this->limit);

        return response()->json([
            'status' => 'success',
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ], 200);
    }

    // GET Method
    public function show($id)
    {
        $users = Users::find($id);
        if(!$users) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => $users
        ], 200);
    }

    // PUT method
    public function store(request $request)
    {
        // Validation
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);

        $users = new Users();
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User has been successfully added!'
        ], 201);
    }

    // UPDATE method
    public function update(request $request, $id)
    {
        // Validation
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);

        $users = Users::find($id);
        if(!$users) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ], 404);
        }
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User has been successfully updated!'
        ], 200);
    }

    // DELETE method
    public function destroy($id)
    {
        $users = Users::find($id);
        if(!$users) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!'
            ], 404);
        }
        $users->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User has been successfully deleted!'
        ], 200);
    }
}
