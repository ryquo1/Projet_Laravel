@extends("template")

@section("content")
    <section class="mainSection createSection">
        <h1>Mise à jour de {{ $site->SIT_NAME }}</h1>
        @if(!empty(session('errors')))
            @foreach (session('errors')->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif
        <form class='createForm' action="{{ route('siteUpdateForm', ['siteId' => $site->SIT_NUM_SITE]) }}" method="POST">
            @csrf
            @method("patch")
            <div class='createFields'>
                <label for="sit_name">Nom</label>
                <input type="text" id="sit_name" name="sit_name" size="30" value="{{ $site->SIT_NAME }}" />
            </div>
            <div class='createFields'>
                <label for="sit_coord">Coordonnées</label>
                <input type="text" id="sit_coord" name="sit_coord" size="30" value="{{ $site->SIT_COORD }}" />
            </div>
            <div class='createFields'>
                <label for="sit_depth">Profondeur</label>
                <input type="number" id="sit_depth" name="sit_depth" size="30" value="{{ $site->SIT_DEPTH }}" />
            </div>
            <div class='createFields'>
                <label for="sit_description">Description</label>
                <input type="textarea" id="sit_description" name="sit_description" size="30" value="{{ $site->SIT_DESCRIPTION }}" />
            </div>
            <input class='btn btn-primary' type="submit" value="Mettre à jour" />
        </form>
        <a class='btn btn-secondary' href="{{ route('managerPanel') }}">Retour au panel d'administration</a>
    </section>
@endsection
