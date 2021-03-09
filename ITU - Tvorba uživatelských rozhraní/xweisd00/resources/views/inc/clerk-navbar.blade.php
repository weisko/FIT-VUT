<nav class="navbar navbar-expand-lg navbar-dark __bg-main mb-4">
    <div class="container">
        <a class="navbar-brand text-white" href="/">{{ config('app.name', 'Laravel') }}</a>
        <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-white"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto d-flex align-items-center">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('register'))
                        <li class="nav-item m-1">
                            <a class="btn __btn-info text-white nav-link" href="{{ route('register') }}">{{ __('Registrácia') }}</a>
                        </li>
                    @endif
                    <li class="nav-item m-1">
                        <a class="btn __btn-outline-info nav-link __hover-white" href="{{ route('login') }}">{{ __('Prihlásenie') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="text-center dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="/clerk" class="dropdown-item">Hlavný Panel</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Odhlásiť sa') }}
                            </a>

                            

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>