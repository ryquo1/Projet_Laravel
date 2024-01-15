@extends("template")

@section("content")

    @foreach($error_msg as $msg)
        <p>{{$msg}}</p><br>
    @endforeach

    @if(False)
        {{-- <a href="{{Route('member_modification',$member_num)}}">Retourner à la modification</a> --}}
    @else
        <a class="btn btn-secondary" href="{{Route('diveCreation')}}">Retourner à la saisie</a>
    @endif

@endsection
