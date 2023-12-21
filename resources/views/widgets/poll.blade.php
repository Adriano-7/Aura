@foreach($event->polls as $poll)
    @if($poll->hasUserVoted($user->id) || Auth::check() && (Auth::user()->organizations->contains($event->organization_id)|| Auth::user()->is_admin))
        <div class="card poll-card" data-poll-id="{{ $poll->id }}"
            data-is-organizer-or-admin="{{ Auth::check() && (Auth::user()->organizations->contains($event->organization_id) || Auth::user()->is_admin) ? 'true' : 'false' }}">
            <div class="poll-card-header" id="heading_{{ $poll->id }}" data-toggle="collapse"
                data-target="#collapse_{{ $poll->id }}" aria-expanded="false" aria-controls="collapse_{{ $poll->id }}">
                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                    {{ $poll->question }} (Resultados)
                    <i class="bi bi-chevron-down" id="arrow_{{ $poll->id }}"></i>
                </h5>
            </div>
            <div id="collapse_{{ $poll->id }}" class="collapse" aria-labelledby="heading_{{ $poll->id }}" data-parent="#polls">
                <div class="card-body">
                    @foreach($poll->options as $option)
                    <div class="option-box optResult disabled {{$option->hasUserVoted($user->id) ? 'highlighted' : ''}}"
                        data-option-id="{{ $option->id }}" style="width: {{$option->getPercentage()}}%;">
                        {{ $option->text }} ({{ $option->getPercentage() }}%)
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card poll-card" data-poll-id="{{ $poll->id }}"
            data-is-organizer-or-admin="{{ Auth::check() && (Auth::user()->organizations->contains($event->organization_id) || Auth::user()->is_admin) ? 'true' : 'false' }}">
            <div class="poll-card-header" id="heading_{{ $poll->id }}" data-toggle="collapse"
                data-target="#collapse_{{ $poll->id }}" aria-expanded="false" aria-controls="collapse_{{ $poll->id }}">
                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                    {{ $poll->question }}
                    <i class="bi bi-chevron-down" id="arrow_{{ $poll->id }}"></i>
                </h5>
            </div>
            <div id="collapse_{{ $poll->id }}" class="collapse" aria-labelledby="heading_{{ $poll->id }}" data-parent="#polls">
                <div class="card-body">
                    @foreach($poll->options as $option)
                    <div class="option-box" data-option-id="{{ $option->id }}">
                        {{ $option->text }}
                    </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    @endif
@endforeach