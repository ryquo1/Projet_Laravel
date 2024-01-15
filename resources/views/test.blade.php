@extends('template')
@section('content')
{{$period->PER_LABEL}}<br>
{{$period->PER_START_TIME}}<br>
{{$period->PER_END_TIME}}<br>
{{$registered}}<br>
{{$secure->MEM_NAME.' '.$secure->MEM_SURNAME}}  <br>
{{$pilote->MEM_NAME.' '.$pilote->MEM_SURNAME}}  <br>
{{$lead->MEM_NAME.' '.$lead->MEM_SURNAME}} <br>
{{$diveNum}} <br>
{{$boat->BOA_NAME}} <br>
{{$site->SIT_NAME}} <br>
@foreach ($groups as $group)
    @foreach ($group as $member)
        {{$member->level}} <br>
    @endforeach
@endforeach


@endsection
