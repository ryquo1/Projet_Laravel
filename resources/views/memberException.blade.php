@extends("template")

@section("content")

    @foreach($error_msg as $msg)
        <p>{{$msg}}</p><br>
    @endforeach

    @if($actionType == 'Modification')
        <a href="{{Route('member_modification',$member_num)}}">Retourner à la modification</a>
    @else
        <a href="{{Route('member_registration')}}">Retourner à la saisie</a>
    @endif

@endsection
