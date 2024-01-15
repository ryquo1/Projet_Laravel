@extends("template")

@section("content")
    <section class='mainSection createSection'>
        <h1>Mise à jour de {{ $boat->BOA_NAME }}</h1>
        @if(!empty(session('errors')))
            @foreach (session('errors')->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif
        <form class='createForm' action="{{ route('boatUpdateForm', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="POST">
            @csrf
            @method("patch")
            <div class='createFields'>
                <label for="boa_name">Nom</label>
                <input type="text" id="boa_name" name="boa_name" size="30" value="{{ $boat->BOA_NAME }}" />
            </div>
            <div class='createFields'>
                <label for="boa_capacity">Capacité</label>
                <input type="number" id="boa_capacity" name="boa_capacity" size="30" value="{{ $boat->BOA_CAPACITY }}" />
            </div>
            <input class='btn btn-primary' type="submit" value="Mettre à jour" />
        </form>
        <a class='btn btn-secondary' href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
    </section>
@endsection
