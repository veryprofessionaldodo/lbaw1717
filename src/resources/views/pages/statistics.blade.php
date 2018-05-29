@extends('layouts.app')

@section('title', 'Stats')

@section('content')

@if(Auth::check())


<div id="container">

    <div id="options">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('project', ['id' => $project->id])}}">Project</a></li>            
            <li class="breadcrumb-item active">Stats</li>            
        </ol>     
    </div>

    <div class="sprints_stats">
        <div class="card">
            <div class="card-body">
                <h3><i class="fas fa-bolt"></i> &nbsp; &nbsp; {{$sprintsCompleted->count}} sprints completed</h3>
            </div>
        </div>
    </div>
    <div class="tasks_stats">
        <div class="card">
            <div class="card-body">
                <h3><i class="far fa-sticky-note"></i> &nbsp; &nbsp; {{$tasksCompleted->count}} tasks completed</h3>
            </div>
        </div>
    </div>

    <div class="members_stats">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">
                <h4>Top contributors</h4>
            </a>
            <a href="#" class="contributor list-group-item list-group-item-action">
                <div class="contributor_info">
                    @if($topContributor1->image != NULL)
                        <img alt="Profile Image of Contributor 1"src="{{$topContributor1->image}}" height="30px" width="30px">
                    @else
                        <img alt="Profile Default Image of Contributor 1"src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" height="30px" width="30px">
                    @endif
                    <p>{{$topContributor1->username}}</p>
                    <p>{{$topContributor1->num}} tasks completed</p>
                </div>   
            </a>
            <a href="#" class="contributor list-group-item list-group-item-action">
                <div class="contributor_info">
                    @if($topContributor2->image != NULL)
                        <img alt="Profile Image of Contributor 2"src="{{$topContributor2->image}}" height="30px" width="30px">
                    @else
                        <img alt="Profile Default Image of Contributor 2"src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" height="30px" width="30px">
                    @endif
                    <p>{{$topContributor2->username}}</p>
                    <p>{{$topContributor2->num}} tasks completed</p>
                </div>   
            </a>
            <a href="#" class="contributor list-group-item list-group-item-action">
                <div class="contributor_info">
                    @if($topContributor3->image != NULL)
                        <img alt="Profile Image of Contributor 3"src="{{$topContributor3->image}}" height="30px" width="30px">
                    @else
                        <img alt="Profile Default Image of Contributor 3"src="{{ asset('storage/'.'1ciQdXDSTzGidrYCo7oOiWFXAfE4DAKgy3FmLllM.jpeg')}}" height="30px" width="30px">
                    @endif
                    <p>{{$topContributor3->username}}</p>
                    <p>{{$topContributor3->num}} tasks completed</p>

                </div>
            </a>

            <div id="graphics">
                <div id="tasks_month">
                    <h4>Tasks completed this month</h4>
                    <canvas id="tasks_month_chart"></canvas>
                    <script>
                        var ctx = document.getElementById("tasks_month_chart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
                                datasets: [{
                                    label: '# of tasks done',
                                    data: [5, 3, 2, 5, 2, 3, 4, 2, 1, 8],
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255,99,132,1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>

                <div id="sprints_year">
                    <h4>Sprints completed this year</h4>
                    <canvas id="sprints_year_chart"></canvas>
                    <script>
                        var ctx = document.getElementById("sprints_year_chart").getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ["March", "April", "May"],
                                datasets: [{
                                    label: '# of sprints done',
                                    data: [5, 3, 2],
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255,99,132,1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@else

@endif

@endsection