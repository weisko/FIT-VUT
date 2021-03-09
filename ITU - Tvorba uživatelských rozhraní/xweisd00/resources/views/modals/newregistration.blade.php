<!-- Modal for displaying form with new registration for vehicle -->
<div class="modal fade" id="newregistration" tabindex="-1" role="dialog" aria-labelledby="newregistrationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content __bg-main">
        <div class="modal-header border-0">
            <h5 class="modal-title text-white" id="newregistrationLabel">Nová Evidencia</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-white border-0">
        {!! Form::open(['action' => 'HomeController@newregistration', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group text-left">
                {{Form::label('plate', 'ŠPZ*')}}
                {{Form::text('plate', '', ['class' => 'form-control', 'placeholder' => 'Napr. AB123XY', 'required'])}}
            </div>
            <div class="form-group text-left">
                {{Form::label('registration_number', 'Evidenčné č.*')}}
                {{Form::text('registration_number', '', ['class' => 'form-control', 'placeholder' => 'Napr. 123456789', 'required'])}}
            </div>
            <div class="form-group">
                {{ Form::label('stk', 'Dátum budúcej STK*') }}
                {{ Form::date('stk', '', ['class' => 'form-control', 'required']) }}
            </div> 
            <div class="form-group">
                {{ Form::label('ek', 'Dátum budúcej EK*') }}
                {{ Form::date('ek', '', ['class' => 'form-control', 'required']) }}
            </div> 
            <p class="small text-white text-left">Políčka označené * sú povinné.</p>
            {{Form::submit('Odoslať', ['class'=>'btn btn-primary mt-2 float-right'])}}
        {!! Form::close() !!}
        </div>
        </div>
    </div>
</div>