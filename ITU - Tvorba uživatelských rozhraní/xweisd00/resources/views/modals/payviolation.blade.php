  
<!-- Modal for showing violations user can pay for -->
<div class="modal fade" id="payviolation" tabindex="-1" role="dialog" aria-labelledby="payviolationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content __bg-main">
        <div class="modal-header border-0">
            <h5 class="modal-title text-white" id="payviolationLabel">Vyberte priestupok/-ky:</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body border-0">
            {!! Form::open(['action' => 'PagesController@payviolation', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            @foreach ($data['violations'] as $violation)
                    <div class="__violation-row row text-white border-bottom border-secondary">
                        <div class="__violation-info text-center col py-2 px-0 d-flex justify-content-around">
                            <div>
                                <strong>ID:&nbsp;&nbsp;</strong><span>{{ $violation->id }}</span>
                            </div>
                            <div>
                                <strong>Miesto:&nbsp;&nbsp;</strong><span class="">{{ $violation->happened_at }}</span>
                            </div>
                            <div>
                                {{-- using carbon to display time properly --}}
                                <strong>Dňa:&nbsp;&nbsp;</strong><span class="">{{ \Carbon\Carbon::parse($violation->happened_on)->format('d/m/Y')}}</span>
                            </div>
                        </div>
                        <div class="text-center col-2 custom-control custom-checkbox py-2 px-0">
                            <input type="checkbox" class="custom-control-input" name="violations_to_pay[]" id="violationCheck-{{ $violation->id }}" value={{ $violation->id }}>
                            <label class="custom-control-label" for="violationCheck-{{ $violation->id }}"></label>
                        </div>                
                    </div>
                    @endforeach
                    {{Form::submit('Zaplatiť', ['class'=>'btn btn-primary mt-2 float-right'])}}
            {!! Form::close() !!}
        </div>
        </div>
    </div>
</div>