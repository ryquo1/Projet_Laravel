@extends("template")

@php
$i=0;
@endphp

@section("content")
    <section id='adminPage'>
        <div class='sectionAdmin'>
            <h2>Bateaux</h2>
            <a class='btn btn-primary' id='addBoat' href="{{ route('boatCreate') }}">Créer un bateau</a>
            <div class='adminContainer'>
                @foreach ($boats as $boat)
                <div class='adminElement'>
                    <b>{{ $boat->BOA_NAME }} ({{ $boat->BOA_CAPACITY }})</b>
                    <form id='modifyBoat' action="{{ route('boatUpdate', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="GET">
                        @method("get")
                        <input class='btn btn-secondary' type="submit" value="Modifier le bateau" />
                    </form>
                    <form id='deleteBoat' action="{{ route('boatDelete', ['boatId' => $boat->BOA_NUM_BOAT]) }}" method="POST">
                        @csrf
                        @method("delete")
                        <input class='btn btn-danger' type="submit" value="Supprimer le bateau" />
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <div class='sectionAdmin'>
            <h2>Sites</h2>
            <a class='btn btn-primary' href="{{ route('siteCreate') }}">Créer un site</a>
            <div class='adminContainer'>
                @foreach ($sites as $site)
                <div class='adminElement'>
                    <b>{{ $site->SIT_NAME }}</b>
                    <b>({{ $site->SIT_COORD }})</b>
                    <span>Profondeur : {{ $site->SIT_DEPTH }}</span>
                    @if (!empty($site->SIT_DESCRIPTION))
                        <span> {{ $site->SIT_DESCRIPTION }}</span>
                    @endif
                    <form action="{{ route('siteUpdate', ['siteId' => $site->SIT_NUM_SITE]) }}" method="GET">
                        @method("get")
                        <input class='btn btn-secondary' type="submit" value="Modifier le site" />
                    </form>
                    <form action="{{ route('siteDelete', ['siteId' => $site->SIT_NUM_SITE]) }}" method="POST">
                        @csrf
                        @method("delete")
                        <input class='btn btn-danger' type="submit" value="Supprimer le site" />
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        <div class='sectionAdmin'>
            <h2>Adhérents</h2>
            <div class='adminContainer'>
                @foreach ($members as $member)
                    <div class='adminElement'>
                        <b>{{ $member->MEM_NAME }} {{ $member->MEM_SURNAME }}
                            (@if ($member->MEM_STATUS === 1)
                                ACTIF
                            @else
                                INACTIF
                            @endif)
                        </b>
                        <p>Il reste {{ $member->MEM_REMAINING_DIVES }} plongées à ce membre.</p>
                        @php
                            $memberFunction = $member->functions;
                            $f
                        @endphp
                        <form action="{{ route('userRolesUpdate', ['userId' => $member->MEM_NUM_MEMBER]) }}" method="POST">
                            @csrf
                            @method("patch")
                            <div id='allChecksAdmin'>
                                <div class='checkAdminContainer'>
                                    <label for="security{{$i}}">Sécurité de surface</label>
                                    <input type="checkbox" id="security{{$i}}" name="security"
                                    @if (!$memberFunction->where("FUN_LABEL", "=", "Sécurité de surface")->isEmpty())
                                        checked
                                    @endif
                                    />
                                </div>
                                <div class='checkAdminContainer'>
                                    <label for="pilot{{$i}}">Pilote</label>
                                    <input type="checkbox" id="pilot{{$i}}" name="pilot"
                                    @if (!$memberFunction->where("FUN_LABEL", "=", "Pilote")->isEmpty())
                                        checked
                                    @endif
                                    />
                                </div>
                                <div class='checkAdminContainer'>
                                    <label for="secretary{{$i}}">Secrétaire</label>
                                    <input type="checkbox" id="secretary{{$i}}" name="secretary"
                                    @if (!$memberFunction->where("FUN_LABEL", "=", "Secrétaire")->isEmpty())
                                        checked
                                    @endif
                                    />
                                </div>
                            </div>
                            <input class='btn btn-secondary' type="submit" value="Mettre à jour le membre" />
                        </form>
                        {{ $i = $i + 1 }}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
