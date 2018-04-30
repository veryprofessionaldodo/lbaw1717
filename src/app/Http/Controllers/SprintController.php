<?php

namespace App\Http\Controllers;

use App\Sprint;

use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function create(Request $request) {
        if(!Auth::check()) return redirect('/login');

        $sprint = new Sprint();
        echo dd($request);
        
        // check if is coordinator
        //$this->authorize('create', $sprint); TODO

        $sprint->name = $request->input('name');
        $sprint->deadline = $request->input('deadline');
        $sprint->project_id = $request->project_id;
        $sprint->save();

        //$sprint->project()->attach($request->input('project_id'));

        //return $sprint;

        return redirect()->route('project', ['project_id' => $request->project_id]);
    }

    public function delete(Request $request, $id)
    {
      $sprint = Sprint::find($id);

      //$this->authorize('delete', $sprint); TODO
      $sprint->delete();

      return $sprint;
    }

    public function showForm(int $project_id) {

        $viewHTML = view('partials.create_sprint_form', ['project_id' => $project_id])->render();
        return response()->json(array('success' => true, 'html' => $viewHTML));
    }
}
