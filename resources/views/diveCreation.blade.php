@extends('template')
@section('content')
    <form class='diveForm' action="{{ route("diveCreationForm") }}" method="POST">
        @csrf
        <div class='fieldContainer'>
            <label>Insérer une date (obligatoire) :</label>
            <input type="date" required id="date" name="date"  min="{{date('Y')}}-03-01" max="{{date('Y')}}-11-31" />
        </div>

        <div class='fieldContainer'>
            <label> Choisir une période (obligatoire) :</label>
            <select name="period" required id="period">
            @foreach ($periods as $period)
                <option value='{{$period->PER_NUM_PERIOD}}'>{{$period->PER_LABEL}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir un site :</label>
            <select name="site" id="site">
            <option value="">--Choisir un site--</option>
            @foreach ($sites as $site)
                <option value='{{$site->SIT_NUM_SITE}}'>{{$site->SIT_NAME}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir un bateau :</label>
            <select name="boat" id="boat">
            <option value="">--Choisir un bateau--</option>
            @foreach ($boats as $boat)
                <option value='{{$boat->BOA_NUM_BOAT}}'>{{$boat->BOA_NAME." (".$boat->BOA_CAPACITY.")"}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir le niveau requis :</label>
            <select name="lvl_required" id="lvl_required">
            <option value="">--Choisir un niveau--</option>
            @foreach ($prerogatives as $prerogative)
                <option value='{{$prerogative->PRE_NUM_PREROG}}'>{{$prerogative->PRE_LEVEL}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir un directeur de plongée :</label>
            <select name="lead" id="lead">
            <option value="">--Choisir un adhérent--</option>
            @foreach ($leads as $lead)
                <option value='{{$lead->MEM_NUM_MEMBER}}'>{{$lead->MEM_NAME." ".$lead->MEM_SURNAME}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir un pilote :</label>
            <select name="pilot" id="pilot">
            <option value="">--Choisir un adhérent--</option>
            @foreach ($pilots as $pilot)
                <option value='{{$pilot->MEM_NUM_MEMBER}}'>{{$pilot->MEM_NAME." ".$pilot->MEM_SURNAME}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Choisir une sécurité de surface :</label>
            <select name="security" id="security">
            <option value="">--Choisir un adhérent--</option>
            @foreach ($securitys as $security)
                <option value='{{$security->MEM_NUM_MEMBER}}'>{{$security->MEM_NAME." ".$security->MEM_SURNAME}}</option>
            @endforeach
            </select>
        </div>

        <div class='fieldContainer'>
            <label>Effectif minimum :</label>
            <input type=number name="min_divers" id="min_divers" value=0>
        </div>

        <div class='fieldContainer'>
            <label>Effectif maximum :</label>
            <input type=number name="max_divers" id="max_divers" value=0>
        </div>

        <button type="submit">Créer le créneau</button>
    </form>
@endsection
