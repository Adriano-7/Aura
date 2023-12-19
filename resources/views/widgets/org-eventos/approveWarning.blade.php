<div id="approveWarning" class="alert d-flex align-items-center">
    <div>Esta organização ainda não foi aprovada.</div>
    @if (Auth::check() && Auth::user()->is_admin)
        <button id="approveButton" class="btn" onclick="approveOrg({{$organization->id}})">Aprovar.</button>
    @endif
</div>