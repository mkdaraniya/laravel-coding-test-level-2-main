<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponder;
    
    public function __construct()
    {
        // $this->middleware('auth'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $this->authorize('user-module');
            $user = User::all();
            return ApiResponder::successResponse($user);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $this->authorize('user-module');
            $data = $request->all();
            $validator = Validator::make(request()->all(), [
                'username' => 'required|string|max:255|unique:users',
                'role_id' => 'required|max:255',
                'password' => 'required|string|min:6|confirmed',
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

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try{
            $this->authorize('user-module');
            $validator = Validator::make(request()->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $user->update($request->all());
            return $this->successResponse($user, 200);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try{
            $this->authorize('user-module');
            return $this->successResponse($user);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{
            $this->authorize('user-module');
            $user->delete();
            return $this->successResponse('User Deleted',200);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

}
