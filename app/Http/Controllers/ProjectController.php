<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
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
            $this->authorize('project-module');

            $data = request()->all();

            $pageIndex = ($data['pageIndex']) ? $data['pageIndex'] : 0;
            $pageSize = ($data['pageSize']) ? $data['pageSize'] : 3;
            $sortBy = ($data['sortBy']) ? $data['sortBy'] : 'name'; 
            $sortDirection = ($data['sortDirection']) ? $data['sortDirection'] : 'ASC'; 


            $project = Project::select('id','name','created_at','updated_at');
            if(isset($data['q']) && !empty($data['q'])){
                $project = $project->where('name', 'LIKE', '%'. $data['q']. '%');
            }
            $project = $project->orderBy($sortBy, $sortDirection)->skip($pageIndex * $pageSize)->limit($pageSize)->get();

            $result = [
                'data' => $project,
                'pageIndex' => $pageIndex,
                'pageSize' => $pageSize,
                'sortBy' => $sortBy,
                'sortDirection' => $sortDirection
            ];
            
            return ApiResponder::successResponse($result);
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
            $this->authorize('project-module');
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
            $this->authorize('project-module');
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
            $this->authorize('project-module');
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
     * assign project to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignProject(Request $request)
    {
        try{
            $this->authorize('project-module');
            $validator = Validator::make(request()->all(), [
                'user_id' => 'required|string|max:255',
                'project_id' => 'required|string|max:255'
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $projectUser = new ProjectUser();
            $projectUser->user_id = $request->user_id;
            $projectUser->project_id = $request->project_id;
            $projectUser->save();

            return $this->successResponse($projectUser, 200);

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
            $this->authorize('project-module');
            $project->delete();
            return $this->successResponse('Project Deleted',200);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(),$e->getCode());
        }
    }
}
