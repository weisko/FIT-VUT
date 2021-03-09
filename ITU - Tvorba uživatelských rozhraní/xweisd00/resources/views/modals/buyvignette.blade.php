  
<!-- Modal for displaying vehicles you can buy vignettes for -->
<div class="modal fade" id="buyvignette" tabindex="-1" role="dialog" aria-labelledby="buyvignetteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content __bg-main">
        <div class="modal-header border-0">
            <h5 class="modal-title text-white" id="buyvignetteLabel">Vyberte vozidlo/-á.</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-white border-0">
        {!! Form::open(['action' => 'PagesController@buyvignette', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            @foreach ($data['vehicles'] as $vehicle)
                {{-- if the vehicle is registered it can get vignette --}}
                @if ($vehicle->registered == 'registered')
                    <div class="row border-bottom border-secondary">
                        <div class="text-center col py-2 px-0">
                            <span>{{ $vehicle->plate }}</span>
                        </div>
                        <div class="text-center col-2 custom-control custom-checkbox py-2 px-0">
                            <input type="checkbox" class="custom-control-input" name="vignettes_to_buy[]" id="vehicleCheck-{{ $vehicle->id }}" value={{ $vehicle->id }}>
                            <label class="custom-control-label" for="vehicleCheck-{{ $vehicle->id }}"></label>
                        </div>                
                    </div>
                @endif
            @endforeach
            {{Form::submit('Zaplatiť', ['class'=>'btn btn-primary mt-2 float-right'])}}
        {!! Form::close() !!}
        </div>
        </div>
    </div>
</div>