{{-- Messages that are displayed when somethin goes wrong or right --}}

@if(count($errors) > 0)
    @foreach(array_unique($errors->all()) as $error)
        <div class="alert m-3 alert-dismissible fade show alert-danger">
            {{$error}}
            <button type="button" class="close text-white" style="top:-4px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert m-3 alert-dismissible fade show alert-success">
        {{session('success')}}
        <button type="button" class="close text-white" style="top:-4px;" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('failure'))
    <div class="alert m-3 alert-dismissible fade show alert-danger">
        {{session('failure')}}
        <button type="button" class="close text-white" style="top:-4px;" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif