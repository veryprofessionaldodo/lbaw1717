@extends('layouts.app')

@section('title', 'Admin Page')

@section('content')


<section class="container-fluid">

    <h1>FAQ</h1>

    <div id="accordion">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    How to create a project
                    </button>
                </h5>
            </div>
        
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    After logging in, in your profile page, you will find a button <strong>"Create New Project"</strong>, that will take you to form.
                    After you fill the name, description, categories of the project and decide if you want the project to be public or private, submit
                    the form. 
                    You will now see the new project in your list of projects.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    How to create a sprint
                </button>
            </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    Inside a project in which you are a coordinator, click the button <strong>"Create New Sprint"</strong>, which will take you to a form.
                    Give the sprint a name, a date that corresponds with the deadline of all the tasks inside the sprint and a maximum effort value that
                    the sprint can take.
                    When you submit the form, a new sprint will appear in your project.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                How to create a task
                </button>
            </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    If you are a coordinator of a project, you can create tasks in it.
                    The tasks can be related to sprint or can exist on their own. If you want to create a task inside a sprint,
                    you need to open the <strong>Sprint</strong> Tab and, inside a sprint, you can create a new task.
                    To create a new task, you need to provide a name and an effort, that can't exceed the sprint's maximum effort value.
                    After the creation of a new task, you can access it by clicking on its name, which will take you to the task page.
                    If you want to create a task unrelated to any sprint, the process is the same but you need to create it in the <strong>Task</strong> Tab.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingFour">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                What functionalites does a task have?
                </button>
            </h5>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body">
                    Besides completing, uncompleting and edit a task, you can claim it to yourself or, if you are a project coordinator, you can assign it to another user.
                    You can access all these options inside the task page, which you can enter by clicking on the task name;
                </div>
            </div>
        </div>
    </div>
</section>

@endsection