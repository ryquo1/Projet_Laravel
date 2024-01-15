@extends("template")

@section("content")
    @php
        use Carbon\Carbon;
        $date = Carbon::parse($dives->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
        $heureStart = $period->PER_START_TIME->format('H');
        $heureFin = $period->PER_END_TIME->format('H');
    @endphp
    <section id='diveInfos'>
        <div id='diveMainInfos'>
            <p><b>Plongée du {{$date}} de {{$heureStart}}h à {{$heureFin}}h.</b></p>
            <p>Niveau de la plongée: <b>{{$prerogative}}</b></p>
            <p>Site : <b>{{$site}}</b></p>
            <p>Directeur de plongée : <b>{{$dives_lead}}</b></p>
            <p>Sécurité surface : <b>{{$dives_secur}}</b></p>
            <p>Pilote : <b>{{$dives_pilot}}</b></p>
            <p>Nom du bateau : <b>{{$boat}}</b></p>
        </div>

        <h3>Liste des membres inscrits</h3>
        <div id='diveMembers'>
            @foreach($dives_register as $member)
                <p>{{$member->MEM_NAME}} {{$member->MEM_SURNAME}}</p>
            @endforeach
        </div>
        <a class="btn btn-secondary" href="{{route('dives')}}" >Retourner à la liste des plongées</a>
    </section>
@endsection









