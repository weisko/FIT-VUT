{{-- Dashboard of clerks alloving them to search threw database of users and their wehicles, also erasing them and iserting violations/tickets --}}
@extends('layouts.clerk-app')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
</head>

<style>
    .__SearchButton{ background-color: #1B3B6F; }
    
    .__ViolationCard{ background-color: #1B3B6F; }
</style>

<div class="container py-3 myClerkContent">
    <div class="d-flex flex-row justify-content-center">
        <div class="col-12 col-lg-8 ">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-9 mb-3">
                    <div class="animated fadeInDown card shadow text-center">
                        <div class="__card-header"></div>
                        <div class="card-body text-white __ViolationCard">

                            {{-- Form for creating violations/tickets --}}
                            <h4>Vložiť nový priestupok</h4>
                            {!! Form::open(['action' => 'ClerkController@addViolation', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                            <div class="form-group">  
                                {!! Form::label('place', 'Place', []) !!}
                                {!! Form::text('place', '', []) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('vehicle', 'Auto', []) !!}
                                {!! Form::text('vehicle', '', []) !!}
                            </div>  
                            <div class="form-group">
                                {!! Form::label('date', 'Date', []) !!}
                                {!! Form::date('date', '', []) !!}
                            </div>  
                                {!! Form::submit('Submit', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            {{-- Search bar with selection of types, drivers or cars --}}
            {!! Form::open(['action' => 'ClerkController@searchFunction', 'method' => 'POST', 'role' => 'search', 'enctype' => 'multipart/form-data']) !!}
                <div class="input-group">
                    {!! Form::text('q', '', ['class' => 'form-control col-9', 'placeholder' => 'Search Users']) !!}
                    {!! Form::select('type', ['Vozidlá', 'Vodiči'], 1, ['class' => 'form-control col-3 rounded-0']) !!}
                    {!! Form::submit('Search', ['class' => 'btn __SearchButton btn-primary __rounded-left-0 rounded-right']) !!}
                </div>
            {!! Form::close() !!}

            {{-- Printing of search results into a table --}}
            @if(isset($user))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Meno</th>
                            <th>Priezvisko</th>
                            <th>Č. občianskeho preukazu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user as $u)
                        <tr id="citizen{{ $u->id }}">
                            <td>{{$u->name}}</td>
                            <td>{{$u->surname}}</td>
                            <td>{{$u->op}}</td>
                            <td>{{ Form::button('<i class="fas fa-trash-alt p-1"></i>', ['class' => 'btn btn-danger btn-sm', 'title' => "Odstrániť", 'onclick' => 'sendDelete(' . $u->id . ', "citizen")'] ) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if(isset($vehicle))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ŠPZ</th>
                            <th>Stav</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicle as $v)
                        <tr id="vehicle{{ $v->id }}">
                            <td>{{$v->plate}}</td>
                            <td>{{$v->registered}}</td>
                            <td>{{ Form::button('<i class="fas fa-trash-alt p-1"></i>', ['class' => 'btn btn-danger btn-sm', 'title' => "Odstrániť", 'onclick' => 'sendDelete(' . $v->id . ', "vehicle")'] ) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            {{-- list of users or cars vaiting to get validated (accept and decline buttons) --}}
            @foreach ( $pending as $p )
                <div id="pending{{ $p->id }}" class="w-100 d-flex align-items-center my-2 bg-white rounded p-2">
                    <p class="mr-auto my-auto">{{ $p->plate }}</p>
                    {{ Form::button('<i class="fas fa-check p-1"></i>', ['class' => 'btn btn-success btn-sm mr-1', 'title' => "Registrovať", 'onclick' => 'sendAccept(' . $p->id . ')'] ) }}
                    {{ Form::button('<i class="fas fa-trash-alt p-1"></i>', ['class' => 'btn btn-danger btn-sm', 'title' => "Zamietnuť", 'onclick' => 'sendReject(' . $p->id . ')'] ) }}
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function sendAccept(pending_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/clerk/accept",
        {
            p_id: pending_id,
        },
        function(data, status){
            $('#pending'+pending_id).remove();
        });
    }

    function sendReject(pending_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post("/clerk/reject",
        {
            p_id: pending_id,
        },
        function(data, status){
            $('#pending'+pending_id).remove();
        });
    }

    function sendDelete( to_delete, who ) {
        if ( confirm('Ste si istý odstránením záznamu?') ) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }   
            });
            $.post("/clerk/delete",
            {
                to_delete: to_delete,
                who: who,
            },
            function(data, status){
                if ( who == 'citizen' ) {
                    $('#citizen'+to_delete).remove();
                } else if ( who == 'vehicle' ) {
                    $('#vehicle'+to_delete).remove();
                }
            });
        }
    }
</script>
@endsection
