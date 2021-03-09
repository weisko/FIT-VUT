@extends('layouts.app')

@section('content')
<div class="container" style="overflow-x: hidden;">
    {{-- Table for the individual violations --}}
    <div class="table-responsive">
        <table class="table" id="violation-payment-table">
            <thead>
                <tr>
                    <td><strong>Vozidlo</strong></td>
                    <td class="text-left"><strong>Dátum</strong></td>
                    <td class="text-right"><strong>Výška pokuty</strong></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentData as $v)
                    <tr>
                        <td>
                            @foreach ($data['vehicles'] as $i)
                                @if ($i->id == $v->vehicle_id)
                                    <span class="text-monospace">{!! $i->plate !!}</span>
                                @endif
                            @endforeach
                        </td>
                        <td>{!! $v->happened_on !!}</td>
                        <td class="text-right" id={!! 'violationPrice'.$loop->index !!}>10€</td>
                    </tr>
                @endforeach
                <tr>
                    {{-- Last payment table row for the price sum --}}
                    <td><strong>SPOLU:</strong></td>
                    <td></td>
                    <td class="text-right"><span id="violation-sum-price"></span></td>
                    <script>
                        /**
                         * Sumarizes the prices of the individual violations
                         */
                        function sumViolationPrices() {
                            let number_of_rows = 0;
                            let table = document.getElementById("violation-payment-table");
                            let rows = table.getElementsByTagName("tr");

                            for (let i = 0; i < rows.length; i++) {
                                number_of_rows++;
                            }
                            number_of_rows -= 2;    // disregard the <head> row and the last row

                            let price_sum = 0;
                            let elem;
                            for (let i = 0; i < number_of_rows; i++) {
                                elem = document.getElementById('violationPrice'+i).innerHTML;    // get the price
                                console.log(elem);
                                elem = elem.substring(0, elem.length - 1); // get rid of the € sign
                                price_sum += Number(elem);
                            }
                            
                            document.getElementById("violation-sum-price").innerHTML = price_sum + "€";
                        }
                        
                        sumViolationPrices(); // sum the prices
                    </script>
                </tr>
            </tbody>
        </table>
    </div>

    {!! Form::open(['action' => 'PagesController@payForViolations', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @foreach ($paymentData as $v)
            {{-- <h6>{{$v}}</h6> --}}
            {!! Form::hidden('to_pay[]', $v->id ) !!}
        @endforeach

        {{-- Payment Form --}}
        @include('inc.payment-accordion')

        {{-- {!! Form::submit('Zaplatiť', ['class' => 'btn btn-danger']) !!} --}}
    {!! Form::close() !!}
</div>
@endsection
