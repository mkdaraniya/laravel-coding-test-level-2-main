<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function register(Request $request)
    {
        try{
            $data = $request->all();
            $validator = Validator::make(request()->all(), [
                'username' => 'required|string|max:255|unique:users',
                'role_id' => 'required|max:255',
                'password' => 'required|string|min:6|confirmed'
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $user = User::create([
                'name' => ($data['name']) ? $data['name'] : '',
                'username' => $data['username'],
                'role_id' => $data['role_id'],
                'password' => Hash::make($data['password']),
            ]);

            return $this->successResponse($user, 201);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make(request()->all(), [
                'username' => 'required|string|max:255',
                'password' => 'required|string'
            ]);
            
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            if(!Auth::attempt($request->only('username','password'))){
                return response([
                    'errors' => 'Invalid Credentials'
                ],Response::HTTP_UNAUTHORIZED);
            }

            $user = auth()->user();

            $token= $user->createToken('tokens')->plainTextToken;

            return $this->successResponse(['jwt' => $token], 201);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    public function logout()
    {
        try{
            auth()->user()->currentAccessToken()->delete();

            return $this->successResponse('successfully logout', 201);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }
}
