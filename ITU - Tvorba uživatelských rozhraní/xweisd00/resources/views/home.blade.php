{{-- Dashboard for users showing them their vehicles and violations amongst other things --}}
@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-row justify-content-center">
        <div class="col-12 col-lg-8 ">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div id="vehicles">
                <div class="row d-flex justify-content-between align-items-center mb-2">
                    <h3 class="text-left mb-0">Vozidlá</h3><button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#newregistration">Nová Evidencia</button>
                    @include('modals.newregistration')
                </div>
                {{-- if user has any vehicles we display them --}}
                @if (is_object($data['vehicles']))
                    @if (count($data['vehicles']) > 0)
                        @foreach ($data['vehicles'] as $vehicle)
                            <div class="row d-flex justify-content-center text-white bg-dark px-2 py-4 mb-2">
                                <div class=" col-sm-12 col-md-12 col-lg-1">
                                    {{-- status of vehicle --}}
                                    @if ( $vehicle->registered == 'registered')
                                        <div class="status reg mb-2" title="Evidované" data-toggle="modal" data-target="#registeredModal">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        @include('modals.registered')
                                    @endif
                                    @if ( $vehicle->registered == 'not registered')
                                        <div class="status nreg mb-2" title="Neevidované" data-toggle="modal" data-target="#unregisteredModal">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        @include('modals.unregistered')
                                    @endif
                                    @if ( $vehicle->registered == 'pending')
                                        <div class="status pen mb-2" title="Čakajúce" data-toggle="modal" data-target="#pendingModal">
                                            <i class="fas fa-exchange-alt"></i>
                                        </div>
                                        @include('modals.pending')
                                    @endif
                                </div>
                                <div class=" col-sm-12 col-md-6 col-lg-7">
                                    <div class="text-left mb-3">
                                        <strong class="font-weight-bold">ŠPZ: </strong><span>{{ $vehicle->plate }}</span>
                                    </div>
                                    <div class="text-left mb-3 smaller">
                                        <strong class="font-weight-bold">Evidenčné č.: </strong><span>{{ $vehicle->registration_number }}</span>
                                    </div>
                                    <div class="__dates text-white mb-2">
                                        <div class="mr-4">
                                            <strong class="small font-weight-bold">STK do:</strong>
                                            <span class="small">{{ \Carbon\Carbon::parse($vehicle->stk)->format('d/m/Y')}}</span>
                                        </div>
                                        <div>
                                            <strong class="small font-weight-bold">EK do:</strong>
                                            <span class="small">{{ \Carbon\Carbon::parse($vehicle->ek)->format('d/m/Y')}}</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- possibility of showing vignettes --}}
                                <div class="__btn-vig col-sm-12 col-md-6 col-lg-4 text-right">
                                    <button type="button" class="btn bnt-sm btn-light" data-toggle="modal" data-target="#showvignettes{{ $vehicle->id }}">
                                        Zakúpené Známky
                                    </button>
                                </div>
                                @include('modals.showvignettes')
                            </div>
                            
                        @endforeach
                        <div class="d-flex justify-content-center">{{$data['vehicles']->links()}}</div>
                    @else
                        <h4 class="text-center">Žiadne nájdené vozidlá.</h4>
                    @endif
                @endif
            </div>  
            <br>
            
            <div id="violations">
                <div class="row mb-2">
                    <h3 class="text-left  mb-0">Priestupky</h3>
                </div>
                {{-- if he has any violations we display them --}}
                <div class="row">
                    @if (is_object($data['violations']))
                        @if (count($data['violations']) > 0)
                            @foreach ($data['violations'] as $violation)
                                <div class="__thirds col-sm-12 col-md-4 bg-dark text-white p-4 mb-1 mr-1">
                                    <div class="w-100 d-flex justify-content-between">
                                        <div>
                                            <div class="mb-1">
                                                <strong>ID: </strong><span>{{ $violation->id }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <span class="small font-weight-bold">Vozidlo: </span>
                                                <span class="small">{{ $violation->vehicle_name }}</span>
                                            </div>
                                        </div>
                                        <div class="price d-flex justify-content-center align-items-center p-2">
                                            <span class="lead text-white">{{ $violation->price }}€</span>
                                        </div>
                                    </div>
                                    <div class="__dates">
                                        <div class="mr-2">
                                            <strong class="small font-weight-bold">Dňa: </strong>
                                            <span class="small">{{ \Carbon\Carbon::parse($violation->happened_on)->format('d/m/Y')}}</span>
                                        </div>
                                        <div>
                                            <span class="small font-weight-bold">Miesto: </span>
                                            <span class="small">{{ $violation->happened_at }}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h4 class="text-center">Žiadne nezaplatené priestupky :)</h4>
                        @endif
                    @endif
                </div>
            </div>
            
        
        </div>
    </div>
</div>
@endsection
