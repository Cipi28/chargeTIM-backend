<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class UsersController extends Controller
{
    protected $limit = 5;

    // Index
    public function index()
    {
        $users = User::paginate($this->limit);

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
        $users = User::find($id);
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

    /**
     * Create user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(request $request)
    {
        // Validation
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id',
            'password' => 'min:5',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = app('hash')->make($request->get('password', Uuid::uuid4()));
        $user->save();

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

        $users = User::find($id);
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
        $users = User::find($id);
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
