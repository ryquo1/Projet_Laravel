@extends('template')
@section('content')
<section id='membersSection'>
    <a type="button" class="btn btn-primary mt-3 mb-3" href={{route('member_registration')}}>Ajouter un adhérent</a>
    <h3>Liste des adhérents :</h3>
        <table id='listMembers'>
            <tr>
                <td>Numéro d'adhérent </td>
                <td>Numéro de licence </td>
                <td>Nom </td>
                <td>Prénom </td>
                <td>Niveau</td>
                <td>Date de certification </td>
                <td>Type d'abonnement </td>
                <td>Date de dernier renouvellement d'abonnement </td>
                <td>Nombre de plongées restantes </td>
                <td>Statut </td>
            </tr>

            @foreach($members as $member)

                <tr>
                    <td>{{$member->MEM_NUM_MEMBER}}</td>
                    <td>{{$member->MEM_NUM_LICENCE}} </td>
                    <td>{{$member->MEM_SURNAME}} </td>
                    <td>{{$member->MEM_NAME}} </td>
                    <td>{{$member->PRE_LABEL }}</td>
                    <td>{{$member->MEM_DATE_CERTIF}} </td>
                    <td>{{$member->MEM_PRICING}} </td>
                    <td>{{$member->MEM_SUBDATE}} </td>
                    <td>{{$member->MEM_REMAINING_DIVES}}</td>
                    <td>@if($member->MEM_STATUS)
                            <span class="active_member">actif</span>
                        @else
                            <span class="inactive_member">inactif</span>
                        @endif
                    </td>
                    <td><a href={{route("member_modification",$member->MEM_NUM_MEMBER)}}>Modifier</a></td>
                </tr>

    @endforeach
    </table>
</section>
@endsection
