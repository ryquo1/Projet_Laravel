@extends("template")

@section("content")
    @php
         use Carbon\Carbon;
         $increment = 0;
    @endphp
    <table id='reportTable'>
        @foreach($dives as $dive)
        <tr>
            @php
                $date = Carbon::parse($dive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y');
                $startTime = $periods[$increment]->PER_START_TIME->format('H');
                $endTime = $periods[$increment++]->PER_END_TIME->format('H');
            @endphp
            <td>Plongée du {{$date}} de {{$startTime}}h à {{$endTime}}h.</td>
        </tr>
        @endforeach
    </table>
@endsection
