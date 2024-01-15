@php
    use App\Models\web\AcnMember;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{URL::asset('/css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>

    <title>Carantec Nautisme</title>
    <script src="https://kit.fontawesome.com/70f23b7858.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav>
            <a href="{{ route("welcome") }}"> <img id='logo' src="{{URL::asset('images/logo.png')}}" alt="Logo Carentec Nautisme"></a>
            <div id="headerLinks">
                @if(Auth::check())
                    <label id='remainingDives'>{{auth()->user()->MEM_REMAINING_DIVES}} plongées restantes</label>
                    @php
                        $isUserSecretary = AcnMember::isUserSecretary(auth()->user()->MEM_NUM_MEMBER);
                        $isUserManager = AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER);
                        $isUserDirector = AcnMember::isUserDirector(auth()->user()->MEM_NUM_MEMBER);
                    @endphp
                    @if($isUserSecretary)
                        <a class="no-deco" href="{{ route("members") }}">Liste d'adhérent</a>
                    @elseif($isUserManager)
                        <a class="no-deco" href="{{ route("managerPanel") }}">Administration</a>
                        <a class="no-deco" href="{{ route("diveCreation") }}">Création de plongée</a>
                        <a class="no-deco" href="{{ route("managerDivesReport") }}">Historique total</a>
                        <a class="no-deco" href="{{ route("archives") }}">Archives</a>
                    @elseif($isUserDirector)
                        <a class="no-deco" href={{ route("DirectorDivesReport") }}>Historique DP</a>
                    @endif

                    @if($isUserDirector)
                        <a class="no-deco" href="{{route('myDirectorDives')}}">Mes séances</a>
                    @endif
                        <a class="no-deco" href="{{ route('dives') }}">S'inscrire</a>
                        <a class="no-deco" href="{{route('diveReport')}}">Mon historique</a>
                        <a class="no-deco" href="{{route('profil_page')}}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a class="no-deco" id="logOutButton" :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Déconnexion') }}
                        </a>
                    </form>
                @elseif(!Route::is('login'))
                    <a class="no-deco" href="{{ route('login') }}">Connexion</a>
                @endif
            </div>
        </nav>
    </header>

    @yield('content')
</body>
</html>
