{{-- Home page -- first thing that user sees --}}

@extends('layouts.app')

@section('content')
    <section id="header">
        <div class="__header-box p-3 shadow">
            <h2 class="display-3 font-weight-bold">
                Koniec vyčkávania v rade.
            </h2>
            <h6>S naším systémom to ide rýchlejšie.</h6>
        </div>
    </section>
    <div id="about" class="__bg-main mt-4">
        <div class="container my-4">
            <h2 class="text-center text-white font-weight-light">Naše služby</h2>
            <div class="row text-white py-4 text-center justify-content-center">
                <div class="col-md-3 shadow border border-light d-flex flex-column justify-content-center my-2">
                    <div class="__circle d-flex justify-content-center align-items-center"><i class="fas fa-rocket"></i></div>
                    <h4 class="my-4 font-weight-light">Lorem</h4>
                    <p class="font-weight-light">Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit rem accusamus, dolorem modi nisi quam unde provident laborum placeat doloribus rerum corporis sit, repellendus, ipsam voluptas. Illo reprehenderit ipsum expedita!</p>

                </div>
                <div class="col-md-3 shadow border border-light d-flex flex-column justify-content-center my-2">
                    <div class="__circle d-flex justify-content-center align-items-center"><i class="fas fa-tools"></i></div>
                    <h4 class="my-4 font-weight-light">Lorem</h4>
                    <p class="font-weight-light">Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit rem accusamus, dolorem modi nisi quam unde provident laborum placeat doloribus rerum corporis sit, repellendus, ipsam voluptas. Illo reprehenderit ipsum expedita!</p>

                </div>
                <div class="col-md-3 shadow border border-light d-flex flex-column justify-content-center my-2">
                    <div class="__circle d-flex justify-content-center align-items-center"><i class="fas fa-car"></i></div>
                    <h4 class="my-4 font-weight-light">Lorem</h4>
                    <p class="font-weight-light">Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit rem accusamus, dolorem modi nisi quam unde provident laborum placeat doloribus rerum corporis sit, repellendus, ipsam voluptas. Illo reprehenderit ipsum expedita!</p>

                </div>
            </div>
        </div>
    </div>
    
    <section id="form">
        <div class="container d-flex flex-column justify-content-center align-items-center">
            <h2 class="text-center font-weight-light">Napíšte nám :)</h2>
            <div class="col-sm-12 col-8-md">
                <form>
                    <div class="form-group">
                      <label for="useremail">Emailová adresa</label>
                      <input type="email" class="form-control" id="useremail" aria-describedby="emailHelp" placeholder="Napíšte váš email">
                      <small id="emailHelp" class="form-text text-muted">Váš email nikde nezverejníme.</small>
                    </div>
                    <div class="form-group">
                        <label for="usertext">Text Správy</label>
                        <textarea class="form-control" id="usertext" rows="3"></textarea>
                    </div>
                    <button class="btn __btn-highlight" data-toggle="modal" data-target="#thankyou">Odoslať</button>
                </form>
                @include('modals.thankyou')
            </div>
        </div>
    </section>

    <script>
        // DEBOUNCE FUNCTION
        function debounce(func, wait = 20, immediate = true) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };
        // grabbing elements we want to animate
        var elementsToShow = document.querySelectorAll('#about .col-md-3');
        // function for sliding
        function checkSlide() {
            elementsToShow.forEach(el => {
                // half way through the image
                const slideInAt = (window.scrollY + window.innerHeight) - el.offsetHeight / 2;
                // bottom of the image
                const imageBottom = el.offsetTop + el.offsetHeight;
                // do we see half
                const isHalfShown = slideInAt > el.offsetTop;
                // and it is not scrolled past half
                const isNotScrolledPast = window.scrollY < imageBottom;
                // add class
                if (isHalfShown && isNotScrolledPast) {
                    el.classList.add('is-visible');
                } else {
                    el.classList.remove('is-visible');
                }
            });
        }                     
        // listen on scroll   
        window.addEventListener("scroll", function(){
            debounce(checkSlide());
        });

        // fake comment sending form
        document.querySelector('#form .__btn-highlight').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#useremail').value = '';
            document.querySelector('#usertext').value = '';
            $('#thankyou').modal('show');
        });
    </script>
@endsection
