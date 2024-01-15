@extends('template')
@section('content')

<title>Modification d'un créneau</title>
</head>
<body>
    <div id='formContainer'>
        <form class='diveForm' action="{{ route("diveModifyForm") }}" method="POST">
            @csrf
            <div class='fieldContainer'>
                <label>Date de la plongée (non modifiable) :</label>
                <input type="date"  disabled required id="date" name="date" value={{$dive->DIV_DATE}}  min="{{date('Y')}}-03-01" max="{{date('Y')}}-11-31" />
            </div>

            <div class='fieldContainer'>
                <label> Période prévue (non modifiable) :</label>
                <input  disabled name="period" required id="period" value={{$period->PER_LABEL}} >
            </div>

            <div class='fieldContainer'>
            <label>Choisir un site :</label>
                <select name="site" id="site">
                @if($site == "")
                    <option selected value="">--Choisir un site--</option>
                @endif
                @foreach ($sites as $site)
                    @if($site->SIT_NUM_SITE == $dive->DIV_NUM_SITE)
                        <option selected value='{{$site->SIT_NUM_SITE}}'>{{$site->SIT_NAME}}</option>
                    @else
                        <option value='{{$site->SIT_NUM_SITE}}'>{{$site->SIT_NAME}}</option>
                    @endif
                @endforeach
                    <option value="">--Choisir un site--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Choisir un bateau :</label>
                <select name="boat" id="boat">
                @if($boat == "")
                    <option selected value="">--Choisir un bateau--</option>
                @endif
                @foreach ($boats as $boat)
                    @if($boat->BOA_NUM_BOAT == $dive->DIV_NUM_BOAT)
                        <option selected value='{{$boat->BOA_NUM_BOAT}}'>{{$boat->BOA_NAME." (".$boat->BOA_CAPACITY.")"}}</option>
                    @else
                        <option value='{{$boat->BOA_NUM_BOAT}}'>{{$boat->BOA_NAME}}</option>
                    @endif
                @endforeach
                    <option value="">--Choisir un bateau--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Choisir le niveau requis :</label>
                <select name="lvlRequired" id="lvlRequired">
                @if($prerogative == "")
                    <option selected value="">--Choisir un niveau--</option>
                @endif
                @foreach ($prerogatives as $prerogative)
                    @if($prerogative->PRE_NUM_PREROG == $dive->DIV_NUM_PREROG)
                        <option selected value='{{$prerogative->PRE_NUM_PREROG}}'>{{$prerogative->PRE_LABEL}}</option>
                    @else
                        <option value='{{$prerogative->PRE_NUM_PREROG}}'>{{$prerogative->PRE_LABEL}}</option>
                    @endif
                @endforeach
                <option value="">--Choisir un niveau--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Choisir un directeur de plongée :</label>
                @if (!$isDirector)
                    <select name="lead" id="lead">
                        @if($lead == "")
                            <option selected value="">--Choisir un adhérent--</option>
                        @endif
                    @foreach ($leads as $lead)
                        @if($lead->MEM_NUM_MEMBER == $dive->DIV_NUM_MEMBER_LEAD)
                            <option selected value='{{$lead->MEM_NUM_MEMBER}}'>{{$lead->MEM_NAME." ".$lead->MEM_SURNAME}}</option>
                        @else
                            <option value='{{$lead->MEM_NUM_MEMBER}}'>{{$lead->MEM_NAME." ".$lead->MEM_SURNAME}}</option>
                        @endif
                    @endforeach
                @else
                    <input hidden="hidden" value={{$dive->DIV_NUM_MEMBER_LEAD}} name="lead" id="lead"/>
                    <select disabled id="lead">
                    @foreach ($leads as $lead)
                        @if($lead->MEM_NUM_MEMBER == $dive->DIV_NUM_MEMBER_LEAD )
                            <option selected disabled value='{{$dive->DIV_NUM_MEMBER_LEAD}}'>{{$lead->MEM_NAME." ".$lead->MEM_SURNAME}}</option>
                        @endif
                    @endforeach
                @endif

                <option value="">--Choisir un ahdérent--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Choisir un pilote :</label>
                <select name="pilot" id="pilot">
                    @if($pilot == "")
                    <option selected value="">--Choisir un adhérent--</option>
                    @endif
                @foreach ($pilots as $pilot)
                    @if($pilot->MEM_NUM_MEMBER == $dive->DIV_NUM_MEMBER_PILOTING)
                        <option selected value='{{$pilot->MEM_NUM_MEMBER}}'>{{$pilot->MEM_NAME." ".$pilot->MEM_SURNAME}}</option>
                    @else
                        <option value='{{$pilot->MEM_NUM_MEMBER}}'>{{$pilot->MEM_NAME." ".$pilot->MEM_SURNAME}}</option>
                    @endif
                @endforeach
                <option value="">--Choisir un ahdérent--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Choisir une sécurité de surface :</label>
                <select name="security" id="security">
                    @if($security == "")
                    <option selected value="">--Choisir un adhérent--</option>
                    @endif
                @foreach ($securitys as $security)
                    @if($security->MEM_NUM_MEMBER == $dive->DIV_NUM_MEMBER_SECURED)
                        <option selected value='{{$security->MEM_NUM_MEMBER}}'>{{$security->MEM_NAME." ".$security->MEM_SURNAME}}</option>
                    @else
                        <option value='{{$security->MEM_NUM_MEMBER}}'>{{$security->MEM_NAME." ".$security->MEM_SURNAME}}</option>
                    @endif
                @endforeach
                <option value="">--Choisir un ahdérent--</option>
                </select>
            </div>

            <div class='fieldContainer'>
                <label>Effectif minimum :</label>
                <input type=number name="minDivers" id="minDivers" value={{$dive->DIV_MIN_REGISTERED}}>
            </div>

            <div class='fieldContainer'>
                <label>Effectif maximum :</label>
                <input type=number name="maxDivers" id="maxDivers" value={{$dive->DIV_MAX_REGISTERED}}>
            </div>

            <input type="hidden" name="numDive" value="{{ $dive->DIV_NUM_DIVE }}">

            <button type="submit">Valider les modifications</button>

        </form>
        <a id='goBackDives' class='hyperlink-no_style' href="{{ route("redirectDiveModify", $dive->DIV_NUM_DIVE) }}">Revenir aux plongées</a>
    </div>
</body>
@endsection
