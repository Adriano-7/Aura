
<div class="container members-container" id="membros">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h1 id="results-title">Membros</h1>

            <div class="dashboard-actions">
                <button type="button" class="btn text-white" data-toggle="modal" data-target="#addMemberModal">
                    Adicionar Membro
                </button>
            </div>

            <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog"
                aria-labelledby="addMemberModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form id="addMemberForm" action="{{ route('organization.inviteUser') }}" method="POST">
                                @csrf
                                <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email" name="email">
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="members-table">
        <div class="row members-header">
            <div class="col-3">
                <h1>Utilizador</h1>
            </div>
            <div class="col-6">
                <h1>Email</h1>
            </div>
            <div class="col-2">
                <h1>Remover da organização</h1>
            </div>
        </div>

        @if($organization->organizers->count() == 0)
            <div class="row report">
                <div class="col-12 text-center">
                    <p style="color:#808080">Não há membros nesta organização.</p>
                </div>
            </div>
        @endif

        @foreach ($organization->organizers as $member)
            <div class="row report" id="member-{{$member->id}}">
                <div class="col-3 members-profile d-flex align-items-center">
                    <div class="pr-2">
                        <img src="{{ asset('assets/profile/' . $member->photo) }}">
                    </div>
                    <div>
                        <h1>{{ $member->name }}</h1>
                    </div>
                </div>
                <div class="col-6 members-text-content">
                    <p>{{ $member->email }}</p>
                </div>
                <div class="col-2 members-actions d-flex justify-content-center">
                    <div class="dropdown">
                        <button class="btn" onclick="eliminateMember({{ $organization->id }}, {{ $member->id }})">
                            <img src="{{ asset('assets/close-icon.svg') }}" alt="more">
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
</div>
