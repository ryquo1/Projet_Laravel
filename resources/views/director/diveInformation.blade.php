@extends('template')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<section class='mainSection'>
    <a class='btn btn-secondary mb-3' href="{{route('myDirectorDives')}}">Retour aux plongées</a>
    <h2 class='mb-3'>Plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')." (".$period.")"}}</h2>
    <div>
        <p> Site : {{ $site }}</p>
        <p>Directeur de plongée : {{$lead}}</p>
        <p>Sécurité de surface : {{$security}}</p>
        <p>Pilote : {{$pilot}}</p>
    </div>

    <div>
        @if(!$updatable)
            <a class="btn btn-secondary" href={{ route('diveModify', $dive['DIV_NUM_DIVE'] ) }}>Modifier la plongée</a>
        @else
            <p>Vous ne pourrez modifier la plongée que le jour où elle aura lieu.</p>
            <button disabled>Modifier la plongée</button>
        @endif
    </div>
    <div class='mt-4'>
        <h3>Membres inscrits ({{$nbMembers}}/{{$max_divers}}) :</h3>
        <table id='directorDives'>
            <thead>
                <tr>
                    <th>Numéro de licence</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $increment =0;
                @endphp
                @foreach($members as $member)
                        <tr>
                            <td>{{$member->MEM_NUM_LICENCE}}</td>
                            <td>{{$member->MEM_NAME}}</td>
                            <td>{{$member->MEM_SURNAME}}</td>
                            <td>{{$levels[$increment++]}}</td>
                            <td>
                                <form action="{{ route('removeMemberFromDiveForm') }}" method="POST">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" name="numMember" value="{{ $member->MEM_NUM_MEMBER }}">
                                    <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
                                    <button class='btn btn-danger' type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <p>Ajouter un adhérent ou vous inscrire :
        <a class='btn btn-primary mt-1' href="{{ route('addMember', $dive['DIV_NUM_DIVE'] ) }}">+</a></p>
        <a class='btn btn-secondary' id="palanqueButton" href={{route('groupsMaking',$dive)}}>Voir les palanquées</a>
        <a class='btn btn-secondary' id="palanqueButton" href={{route('safetyDataSheet',$dive['DIV_NUM_DIVE'])}}>Voir la fiche de sécuritée</a>
    </div>
</section>
@endsection
