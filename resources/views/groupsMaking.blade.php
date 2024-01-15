@extends('template')

@section('content')
<section class='mainSection text-center'>

    <a class='btn btn-secondary mb-3' href="{{ route('diveInformation', $dive ) }}">Revenir aux informations de la plongée</a>
    <h2 id='groupTitle'>{{$message}}</h2>
    <div id='groupContainer'>
    @foreach ($members as $key=>$group)
        @if ($key != null)
            <div class='groupBox'>
                @foreach ($group[0] as $member)
                    <div class='memberInGroup'>
                        <p>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</p>
                        <a class='btn btn-danger' href='{{Route('removeFromGroup', ['dive' => $dive, 'member' => $member->MEM_NUM_MEMBER])}}'>Supprimer</a>
                    </div>
                @endforeach
                <div class='addBox'>
                    @if (($group[1] == 1 ? 2 : 3) != sizeof($group[0]))
                        @if (array_key_exists(null, $members))
                            <form action='{{Route('addMemberToGroup')}}' method='POST'>
                                @csrf
                                <div class='createFields'>
                                    <label class='addLabel' for='member'>Membre : </label>
                                    <select class='addElement' name='member'>
                                        @foreach ($members[null][0] as $member)
                                            @if ($member->PRE_LABEL != 'PB' || sizeof($group[0]) != 2)
                                                <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <input type='hidden' name='dive' value='{{$dive}}'>
                                <input type='hidden' name='group' value='{{$key}}'>
                                <button class='btn btn-primary' type="submit">Ajouter</button>
                            </form>
                            <br/>
                        @endif
                        @if (array_key_exists(null, $members) && sizeof($supervisors) != 0)
                            <form action='{{Route('addMemberToGroup')}}' method='POST'>
                                @csrf
                                <div class='createFields'>
                                    <label class='addLabel' for='member'>Encadrant : </label>
                                    <select class='addElement' name='member'>
                                        @foreach ($supervisors as $member)
                                            <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type='hidden' name='dive' value='{{$dive}}'>
                                <input type='hidden' name='group' value='{{$key}}'>
                                <button class='btn btn-primary' type="submit">Ajouter</button>
                            </form>
                            <br/>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @endforeach
    </div>

    <div id='bottomGroups'>
    @if (array_key_exists(null, $members))
        <form id='addToNew' action='{{Route('addGroup')}}' method='POST'>
            @csrf
            <select class='addElement' name='member'>
                @foreach ($members[null][0] as $member)
                    <option value='{{$member->MEM_NUM_MEMBER}}'>{{$member->MEM_NAME.' '.$member->MEM_SURNAME.' '.$member->PRE_LABEL}}</option>
                @endforeach
            </select>
            <input type='hidden' name='dive' value='{{$dive}}'>
            <button class='btn btn-primary' type="submit">Ajouter à une nouvelle palanquée</button>
        </form>
    @else
        <a class='btn btn-success' href='{{Route('validateGroup', ["diveId" => $dive])}}'>Valider</a>
    @endif
        <a class='btn btn-secondary' href='{{Route('automaticGroup', ["diveId" => $dive])}}'>Arrangement aléatoire</a>
    </div>
</section>
@endsection
