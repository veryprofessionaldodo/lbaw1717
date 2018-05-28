<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Sprint;
use App\Project;

class SprintController extends Controller
{
    public function create(Request $request) {
        if(!Auth::check()) return redirect('/login');

        // check if is coordinator
        //$this->authorize('create', $sprint); TODO
        try {
            $sprint = new Sprint();
            $sprint->name = $request->name;

            $date = new \DateTime($request->deadline);
            $sprint->deadline = $date->format('Y-m-d');

            $sprint->effort = $request->effort;
            $sprint->project_id = $request->project_id;
            $sprint->user_creator_id = Auth::user()->id;
            $sprint->save();

            //return $sprint;
            return redirect()->route('project', ['project_id' => $request->project_id]);

        }  catch(\Illuminate\Database\QueryException $qe) {
            // Catch the specific exception and handle it 
            //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
            // Handle unexpected errors
        }

    }

    public function destroy(Request $request)
    {
      $sprint = Sprint::find($request->input('sprint_id'));
      $tasks = Sprint::find($request->input('sprint_id'))->tasks()->get();


      switch($request->input('value')){
          case "all":
            for($x = 0; $x < count($tasks); $x++){
                $tasks[$x]->delete();
            }
            $sprint->delete();
            break;

          case "move":
            for($x = 0; $x < count($tasks); $x++){
                $tasks[$x]->sprint_id = NULL;
                $tasks[$x]->save();
            }
            $sprint->delete();
            break;

          case "change":
            break;
      }

      return response()->json(array('success' => true,'value' => $request->input('value'), 'sprint_id' => $request->input('sprint_id')));
    }

    public function showForm(int $project_id) {

        $viewHTML = view('partials.create_sprint_form', ['project_id' => $project_id])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }

    public function edit($project_id, $sprint_id){
        if (!Auth::check()) return redirect('/login');

        try {
            if(Auth::user()->isCoordinator($project_id)){
                $sprint = Sprint::where('id',$sprint_id)->first();
                $project = Project::where('id',$project_id)->first();
                $html = view('partials.edit_sprint_form', ['project' => $project, 'sprint' => $sprint])->render();
                return response()->json(array('success' => true, 'html' => $html));

            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
        // Handle unexpected errors
        }
    }

    public function update(Request $request, $project_id, $sprint_id){
        if (!Auth::check()) return redirect('/login');

        try {
            if(Auth::user()->isCoordinator($project_id)){

                $sprint = Sprint::where('id',$sprint_id)->first();
                $sprint->name = $request->input('name');
                
                $date = new \DateTime($request->input('deadline'));
                $sprint->deadline = $date->format('Y-m-d');

                $sprint->effort = $request->input('effort');

                $sprint->save();
                
                return back();

            }
            
        } catch(\Illuminate\Database\QueryException $qe) {
        // Catch the specific exception and handle it 
        //(returning the view with the parsed errors, p.e)
        } catch (\Exception $e) {
        // Handle unexpected errors
        }
    }
}
