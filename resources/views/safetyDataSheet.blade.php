@extends('template')

@section('content')
    @php
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $timestamp = strtotime($dives->DIV_DATE);
        $date = strftime('%A %d %B %Y', $timestamp);
        $startTimestamp = strtotime($period->PER_START_TIME);
        $startTime = strftime('%H', $startTimestamp);
        $endTimestamp = strtotime($period->PER_END_TIME);
        $endTime = strftime('%H', $endTimestamp);
    @endphp
<div>
</div>
<div id='safetySheet'>
    <table id='firstTable' class='bold'>
        <thead>
            <tr>
                <th colspan='4'>Fiche de sécurité</th>
            </tr>
        </thead>
        <tr>
            <td colspan='1'>Date</td>
            <td colspan='1'>{{$date}} <br> {{$startTime.'h - '.$endTime.'h'}}</td>
            <td colspan='2' rowspan='4'><img id='logoSafetySheet' src='/images/logoSafetySheet.png' alt='carantec nautism' width='600px'></td>
        </tr>
        <tr>
            <td colspan='1'>Directeur de plongée</td>
            <td colspan='1'>{{$lead->MEM_SURNAME.' '.$lead->MEM_NAME}}</td>
        </tr>
        <tr>
            <td colspan='1'>Site de plongée</td>
            <td colspan='1'>{{$site->SIT_NAME}}</td>
        </tr>
        
    </table>

    <table id='secondTable'>
        <tr>
            <td class='firstRow'>Embarcation</td>
            <td class='secondRow'>{{$boat->BOA_NAME}}</td>
        </tr>
        <tr>
            <td class='firstRow'>Sécurité de surface</td>
            <td class='secondRow'>{{$secure->MEM_SURNAME.' '.$secure->MEM_NAME}}</td>
        </tr>
        <tr>
            <td class='firstRow'>Pilote</td>
            <td class='secondRow'>{{$pilote->MEM_SURNAME.' '.$pilote->MEM_NAME}}</td>
        </tr>
        <tr>
            <td class='firstRow'>Observation <br> > météo et marée</td>
            <td class='secondRow'></td>
        </tr>
    </table>
    @php
        $id = 1;
        $increment = 0;
    @endphp
    @foreach ($groups as $group)
        <table class='palanquing'>
            <thead>
                <tr>
                    <th class='headerText' colspan='6'>PALANQUEE N° {{$id++}}</th>
                </tr>
            </thead>
            <tbody>
                    @php
                    $info = $groupInfo[$increment];
                    $immertionTimestamp = strtotime($info->GRP_TIME_OF_IMMERSION);
                    $immertionTime = strftime('%H:%M', $immertionTimestamp);
                    $emertionTimestamp = strtotime($info->GRP_TIME_OF_EMERSION);
                    $emertionTime = strftime('%H:%M', $emertionTimestamp);  
                    @endphp   
                        <tr class='bold'>
                            <td>Heure de départ</td>
                            <td>{{$immertionTime}}</td>
                            <td>Heure de retour</td>
                            <td colspan='3'>{{$emertionTime}}</td>
                        </tr>
                        <tr>
                            <td>Temps Prévu</td>
                            <td>{{$info->GRP_EXPECTED_DURATION}} min</td>
                            <td>Profondeur Prévu</td>
                            <td colspan='3'>{{$info->GRP_EXPECTED_DEPTH}} m</td>
                        </tr>
                        <tr>
                            <td>Temps Réalisé</td>
                            <td>{{$info->GRP_DIVING_TIME}} min</td>
                            <td>Profondeur Réalisé</td>
                            <td colspan='3'>{{$info->GRP_REACHED_DEPTH}} m</td>
                        </tr>
                        
                        <tr>
                            <td class='headerText bold' colspan='3'>Nom Prénom</td>
                            <td>Aptitudes</td>
                            <td class='bold'>Formation vers</td>
                            <td class='bold'>Fonction</td>
                        </tr>
                        @foreach ($group as $member)
                        <tr>
                            <td colspan='3'>
                                {{$member->MEM_SURNAME.' '.$member->MEM_NAME}}
                            </td>
                            <td>{{$member->level}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @php
                    $increment++;
                @endphp
        @endforeach
        <a class='btn btn-secondary' id="palanqueButton" href={{route('diveInformation', $dives->DIV_NUM_DIVE)}}>Retourner à la modification</a>
    </div>
    @endsection
