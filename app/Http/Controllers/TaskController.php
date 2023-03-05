<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $task = Task::all();
            return ApiResponder::successResponse($task);
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
            $data = $request->all();
            $validator = Validator::make(request()->all(), [
                'title' => 'required|string|max:255',
                'status' => 'required|boolean|max:255',
                'project_id' => 'required',
                'user_id' => 'required'
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $task = Task::create([
                'title' => $data['title'],
                'description' => isset($data['description']) ? $data['description'] : '',
                'status' => $data['status'],
                'project_id' => $data['project_id'],
                'user_id' => $data['user_id'],
            ]);

            return $this->successResponse($task, 201);
        }catch(QueryException  $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        try{
            return $this->successResponse($task);
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
    public function update(Request $request, Task $task)
    {
        try{
            $validator = Validator::make(request()->all(), [
                'title' => 'required|string|max:255',
                'status' => 'required|boolean|max:255'
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $task->update($request->all());
            return $this->successResponse($task, 200);

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
    public function destroy(Task $task)
    {
        try{
            $task->delete();
            return $this->successResponse('Task Deleted',200);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }
}
