@extends('template')

@section("content")
@php
    use Carbon\Carbon;
@endphp

<section class='mainSection'>
    <a class='btn btn-secondary mb-3' href="{{ route('diveInformation', $dive['DIV_NUM_DIVE'] ) }}">Revenir aux informations de la plongée</a>
    <h3>Ajouter des membres pour la plongée n°{{$dive['DIV_NUM_DIVE']}} du {{Carbon::parse($dive['DIV_DATE'])->locale('fr_FR')->translatedFormat('l j F Y')}}</h3>
    @if ($directorRegistered)
        <form action="{{ route('removeDirectorFromDiveForm') }}" method="POST">
            @csrf
            @method('post')
            <input type="hidden" name="numMember" value="{{ $dive['DIV_NUM_MEMBER_LEAD'] }}">
            <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
            <label>Vous êtes inscrit à cette plongée. Voulez-vous vous désinscrire de la plongée : </label>
            <button class='btn btn-danger' type="submit">Se Désinscrire</button>
        </form>
    @else
        <form action="{{ route('addMemberToDiveForm') }}" method="POST">
            @csrf
            @method('post')
            <input type="hidden" name="numMember" value="{{ $dive['DIV_NUM_MEMBER_LEAD'] }}">
            <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
            <label>Vous n'êtes pas inscrit à cette plongée. Voulez-vous vous inscrire à la plongée :</label>
            <button class='btn btn-primary' type="submit">S'inscrire</button>
        </form>
    @endif
    @if ($maxReached)
        <p class="userError">Le nombre d'inscrits maximum a été atteint</p>
    @endif
    <table id='directorDives'>
        <thead>
            <tr>
                <th>Numéro de licence</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Niveau</th>
                <th>Ajouter</th>
            </tr>
        </thead>
        <tbody>
            @php
                $increment = 0;
            @endphp
            @foreach($members as $member)
                <tr>
                    <td>{{$member->MEM_NUM_LICENCE}}</td>
                    <td>{{$member->MEM_NAME}}</td>
                    <td>{{$member->MEM_SURNAME}}</td>
                    <td>{{$levels[$increment++]}}
                    <td>
                        <form action="{{ route('addMemberToDiveForm') }}" method="POST">
                            @csrf
                            @method('post')
                            <input type="hidden" name="numMember" value="{{ $member->MEM_NUM_MEMBER }}">
                            <input type="hidden" name="numDive" value="{{ $dive['DIV_NUM_DIVE'] }}">
                            @if ($maxReached)
                                <button disabled type="submit">Ajouter</button>
                            @else
                                <button class='btn btn-primary' type="submit">Ajouter</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
