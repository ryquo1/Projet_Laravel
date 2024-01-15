@extends('template')

@section('content')
<label for='boat'>Sélectionnez le bateau : </label>
<select id='boat'>
    @foreach ($boats as $boat)
        <option value='{{ $boat->BOA_NUM_BOAT }}'>{{ $boat->BOA_NAME }}</option>
    @endforeach

</select>
@endsection
