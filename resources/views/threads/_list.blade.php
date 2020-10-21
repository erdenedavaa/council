<div class="">
    @forelse ($threads as $thread)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
                <div class="d-flex flex-column">
                    <h4 class="m-0 mb-1">
                        <a href="{{ $thread->path() }}">
                            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>

                    <h6 class="m-0">
                        Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                    </h6>

                </div>
                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ \Illuminate\Support\Str::plural('reply', $thread->replies_count) }}
                </a>
            </div>

            <div class="card-body">
                <div class="body">{!! $thread->body !!}</div>
            </div>

            <div class="card-footer">
                {{ $thread->visits }} visits
            </div>

        </div>
    @empty
        <p>There are no relevant results at this time.</p>
    @endforelse
</div>
