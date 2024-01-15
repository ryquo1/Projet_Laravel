@extends("template")
@section("content")

@php
    use Carbon\Carbon;
@endphp

<table class='mainSection text-center' id='directorDives'>
    <tr>
        <th>N° de la plongée </th>
        <th>Date de la plongée</th>
        <th>Nom du bateau </th>
        <th>Niveau de la plongée </th>
        <th>Directeur de plongée </th>
        <th>Sécurité de surface</th>
        <th>Pilote </th>
    </tr>
    @if(count($archives) == 0)
        <td colspan="7" class='fw-bold'>Aucune archive</td>
    @else
        @foreach($archives as $archive)<tr>
            <td>{{$archive->DIV_NUM_DIVE}}</td>
            <td>{{Carbon::parse($archive->DIV_DATE)->locale('fr_FR')->translatedFormat('l j F Y')}} </td>
            <td>{{$archive->BOAT_NAME}} </td>
            <td>{{$archive->LEVEL}} </td>
            <td>{{$archive->LEADER }}</td>
            <td>{{$archive->SECURITY}} </td>
            <td>{{$archive->PILOT}} </td>
        </tr>
        @endforeach
    @endif
</table>
@endsection
