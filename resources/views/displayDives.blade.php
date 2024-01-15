@extends("template")
@php use App\Models\web\AcnMember; @endphp

@section("content")
@php
    $id=0;
    use Carbon\Carbon;
    $user = auth()->user();
@endphp
<div class="center_display">
    @if(!empty(session('errors')))
        @foreach (session('errors')->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif
    @foreach($months as $month)
        @if($dives[$month->mois_mot]->count() > 0)
            <h2>{{ucfirst(Carbon::parse($month->mois_nb."/01/2000")->locale('fr_FR')->translatedFormat('F')) }}</h2>
        @endif
        @foreach($dives[$month->mois_mot] as $dive)
                @php
                    $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                    $heureStart = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_START_TIME), 'G');
                    $heureFin = date_Format(DateTime::createFromFormat('H:i:s',$dive->PER_END_TIME), 'G');
                    $buttonText = "S'inscrire"
                @endphp
            <div id="divesDisplayed">
                <form
                    @if ($user->dives->contains("DIV_NUM_DIVE", $dive->DIV_NUM_DIVE))
                        action="{{ route('membersDivesUnregister') }}"
                        @php
                            $buttonText = "Se désinscrire"
                        @endphp
                    @else
                        action="{{ route('membersDivesRegister') }}"
                    @endif
                    method="POST">
                    @csrf
                    @method('post')
                    <p>
                        <a  class="linkDisplayDives hyperlink-no_style"  href="{{route('dives_informations',$dive->DIV_NUM_DIVE)}}">
                            <input
                            type='hidden'
                            name='dive'
                            value={{$dive->DIV_NUM_DIVE}}
                            >
                            {{ $date }}
                            de {{ $heureStart }}h à {{ $heureFin }}h <br/>
                            Site prévu : {{ $dive->SIT_NAME }}
                            ({{ $dive->SIT_DESCRIPTION }}) <br/>
                            Niveau : {{$dive->PRE_LABEL}}
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                    </p>
                    <div id='buttonsDive'>
                        <button class="btn btn-primary" type="submit" value="" @if ($dive->PRE_PRIORITY > $user->prerogatives->max("PRE_PRIORITY"))
                            disabled
                            @endif>{{ $buttonText }}
                        </button>
                        @if(AcnMember::isUserManager(auth()->user()->MEM_NUM_MEMBER))
                            <a class='btn btn-secondary' href="{{route('diveModify',$dive->DIV_NUM_DIVE)}}">Modifier</a>
                            <button class='btn btn-danger' type="button" onclick="document.getElementById('confirm').style.display='inline-block'">Supprimer</button>
                            <div id="confirm" class="modal">
                                <form class="modal-content" action="/action_page.php">
                                    <div class="container">
                                        <h1>Supprimer la plongée</h1>
                                        <p>Êtes-vous sûr de vouloir supprimer cette plongée ?</p>
                        
                                        <div class="clearfix">
                                            <button class='btn btn-secondary' type="button" onclick="document.getElementById('confirm').style.display='none'" class="cancelbtn" >Annuler</button>
                                            <a class='btn btn-danger' href ="{{ route('diveDeletion', $dive->DIV_NUM_DIVE) }}">Supprimer</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif 
                    </div>
                </form>
            </div>
        @endforeach
    @endforeach
</div>
<script>
    var modal = document.getElementById('confirm');

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection
