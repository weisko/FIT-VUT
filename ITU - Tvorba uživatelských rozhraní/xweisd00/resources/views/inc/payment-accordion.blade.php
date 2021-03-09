<div class="accordionContainer">
    <div class="accordion" id="paymentAccordion">
        <div class="card paymentAccordionContent">
            <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Platba kartou
                </button>
            </h5>
            </div>
        
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#paymentAccordion">
            <div class="card-body">
                <div class="col-md-6">
                    {{-- CARD PAYMENT FORM --}}
                    <form method="POST" {{-- action="" --}}>
                    
                    {{-- Card number --}}
                    <div class="form-row">
                        <div class="col-md-12">
                            <label for="inputCardNumber">Číslo karty</label>
                            <input type="text" class="form-control" id="inputCardNumber" placeholder="Číslo karty">
                        </div>
                    </div>
                    
                    {{-- Expiry date --}}
                    <div class="form-row">
                        {{-- <input-card-date-selector></input-card-date-selector> --}}

                        <div class="col-md-6">
                            <label for="inputExpiryDate">Dátum expirácie</label>
                            <input type="text" class="form-control" id="inputExpiryDate" maxlength="5" placeholder="MM/RR">
                        </div>

                        <div class="col-md-6">
                            <label for="inputCVV">Verifikačný kód</label>
                            <input type="text" class="form-control" id="inputCVV" maxlength="3" placeholder="CVV">
                        </div>
                    </div>
                    
                    {{-- PAYMENT BUTTON --}}
                    {!! Form::submit('Zaplatiť', ['class' => '__submit btn btn-primary', 'id' => 'carPayButton']) !!}
                    </form>
                </div>
            </div>
            </div>
        </div>
        
        {{-- TatraBanka Payment --}}
        <div class="card paymentAccordionContent">
            <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                TatraPay
                </button>
            </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#paymentAccordion">
            <div class="card-body">
                {!! Form::submit('TatraPay', ['class' => '__submit btn btn-link', 'id' => 'tatraPayButton']) !!}
            </div>
            </div>
        </div>

        {{-- VÚB Payment --}}
        <div class="card paymentAccordionContent">
            <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                VÚB Pay
                </button>
            </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#paymentAccordion">
            <div class="card-body">
                {!! Form::submit('VÚB Pay', ['class' => '__submit btn btn-link', 'id' => 'vubPayButton']) !!}
            </div>
            </div>
        </div>
    </div>
</div>