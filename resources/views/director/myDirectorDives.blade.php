@extends('template')

@section('content')
@php
    use Carbon\Carbon;
    $increment = 0;
@endphp

<div id='directorDivesSection' class='mainSection'>
    @if ($dives->count()==0)
        <p>Vous êtes le directeur d'aucune plongée</p>
    @else
        <h3>Vos plongées :</h3>
        <table id='directorDives'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure de début</th>
                    <th>Heure de fin</th>
                    <th>Site</th>
                    <th>Niveau</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dives as $dive)
                    @php
                        $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                        $startTime = $periods[$increment]->PER_START_TIME->format('H');
                        $endTime = $periods[$increment]->PER_END_TIME->format('H');
                    @endphp
                    <tr>
                        <td>{{$date}}</td>
                        <td>{{$startTime}}</td>
                        <td>{{$endTime}}</td>
                        <td>{{$sites[$increment]}}</td>
                        <td>{{$prerogatives[$increment++]}}</td>
                        <td>
                            <a class='btn btn-secondary'href="{{route('diveInformation', $dive->DIV_NUM_DIVE)}}">Gérer la plongée</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>




@endsection
