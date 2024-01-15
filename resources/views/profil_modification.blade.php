@extends('template')
@section('content')

<title>Modification d'un adhérent</title>
</head>
<body>
    <form id='profilModifForm' action="{{route("modify_profil")}}" method="POST">
        @csrf
        <div class='profilModifField'>
            <label>Prénom du membre :</label>
            <input type="text" required id="memberName" name="memberName" value={{$member->MEM_NAME}} />
        </div>

        <div class='profilModifField'>
            <label>Nom du membre :</label>
            <input type="text" required id="memberSurname" name="memberSurname" value={{$member->MEM_SURNAME}} />
        </div>

        <p>Numéro de licence : <b>{{$member->MEM_NUM_LICENCE}}</b></p>

        <p>Date de certification : <b>{{$member->MEM_DATE_CERTIF}}</b></p>

        <p>Type d'abonnement : <b>{{$member->MEM_PRICING}}</b></p>

        <p><b>{{$member->MEM_REMAINING_DIVES}}</b> plongées restantes</p>

        <p>Prérogative : <b>{{$prerogative}}</b></p>
    
        <button class='btn btn-secondary' type="submit">Modifier les informations</button>
        <a class="btn btn-secondary" href="{{route('profil_page')}}" >Retourner à mon profil</a>
    </form>
</body>
@endsection
