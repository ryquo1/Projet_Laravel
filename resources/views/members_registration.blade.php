@extends('template')
@section('content')
<section class='createSection'>

<title>Création d'un adhérent</title>
</head>
    <body>
        <form class='createForm' action="{{route("register_member")}}" method="POST">
            @csrf
            <input type="number" hidden name="member_num" >

            <div class='createFields'>
                <label>Nom du membre :</label>
                <input type="text" required id="member_name" name="member_name"  />
            </div>

            <div class='createFields'>
                <label>Prénom du membre :</label>
                <input type="text" required id="member_surname" name="member_surname"  />
            </div>

            <div class='createFields'>
                <label>Numéro de licence :</label>
                <input type="text" required id="member_licence" name="member_licence" />
            </div>

            <div class='createFields'>
                <label>Mot de passe</label>
                <input type="password" required id="member_password" name="member_password" />
            </div>

            <div class='createFields'>
                <label>Date de certification :</label>
                <input type="date" required id="certif_date" name="certif_date" />
            </div>

            <div class='createFields'>
                <label>Type d'abonnement :</label>
                    <select name="pricing_type" id="pricing_type">
                    @foreach ($pricing as $price)
                            <option value='{{$price->MEM_PRICING}}'>{{$price->MEM_PRICING}}</option>
                    @endforeach
                </select>
            </div>

            <div class='createFields'>
                <label>Prérogative :</label>
                    <select name="member_prerog" id="member_prerog">
                    @foreach ($prerogations as $prerogation)
                            <option value='{{$prerogation->PRE_PRIORITY}}'>{{$prerogation->PRE_LEVEL}}</option>
                    @endforeach
                    </select>
            </div>

            <button class='btn btn-primary' type="submit">Inscrire l'adhérent</button>

        </form>
    </body>
</section>
@endsection
