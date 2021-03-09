  
<!-- Modal for showing bought vignettes -->
{{-- added vehicle id for specific vehicle --}}
<div class="modal fade" id="showvignettes{{ $vehicle->id }}" tabindex="-1" role="dialog" aria-labelledby="showvignettesLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content __bg-main">
        <div class="modal-header border-0">
            <h5 class="modal-title text-white" id="showvignettesLabel">Zakúpené známky pre vozidlo.</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body text-white border-0">
            @if ( count($vehicle->vignettes) > 0 )
                <div class="row d-flex flex-column">
                    @foreach ($vehicle->vignettes as $v)
                        <div class="col-sm-12">
                            <span class="font-weight-light">Známka č.{{ $v->id }}&nbsp;</span>
                            <strong>OD: </strong><span>{{ $v->valid_since }}</span>
                            <strong>DO: </strong><span>{{ $v->valid_until }}</span>
                        </div>
                        <hr>
                    @endforeach
                </div>
            @else
                <h6>Žiadne zakúpené známky pre vybrané auto.</h6>
            @endif
        </div>
        </div>
    </div>
</div>