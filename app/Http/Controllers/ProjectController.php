<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $project = Project::all();
            return ApiResponder::successResponse($project);
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
            $validator = Validator::make(request()->all(), [
                'name' => 'required|string|max:255|unique:projects'
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $project = Project::create([
                'name' => $request->get('name'),
            ]);

            return $this->successResponse($project, 201);
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
    public function show(Project $project)
    {
        try{
            return $this->successResponse($project);
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
    public function update(Request $request, Project $project)
    {
        try{
            $validator = Validator::make(request()->all(), [
                'name' => 'required|string|max:255|unique:projects,name,'.$project->id,
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $project->update($request->all());
            return $this->successResponse($project, 200);

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
    public function destroy(Project $project)
    {
        try{
            $project->delete();
            return $this->successResponse('Project Deleted',200);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }
}
