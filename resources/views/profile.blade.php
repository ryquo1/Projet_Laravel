@extends('template')
@section('content')

@php
use app\Models\web\AcnMember;

$members = AcnMember::all();
 AcnMember::checkStatus();

@endphp
<div id='profileCard'>
    @if($member->MEM_PRICING == 'enfant')
        <img class='profilePicture' src="{{URL::asset('images/babyDiver.avif')}}" alt="baby diver">
    @else
        <img class='profilePicture' src="{{URL::asset('images/adultDiver.avif')}}" alt="adult diver">
    @endif
    <div id='profileInfos'>
        <p>Numéro de licence : <b>{{$member->MEM_NUM_LICENCE}}</b></p>
        <p><b>{{$member->MEM_NAME}} {{$member->MEM_SURNAME}}</b></p>
        <p>Date de certificat : <b>{{$member->MEM_DATE_CERTIF}}</b></p>
        <p>Type d'abonnement : <b>{{$member->MEM_PRICING}}</b></p>
        <p><b>{{$member->MEM_REMAINING_DIVES}}</b> plongées restantes</p>
        <a class='btn btn-secondary' href={{route('profil_modification')}}>Modifier mes informations</a>
    </div>
</div>
@endsection
