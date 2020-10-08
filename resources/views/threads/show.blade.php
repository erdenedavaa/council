@extends('layouts.app')

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8" v-cloak>
                   @include('threads._question')

                    <replies
                             @added="repliesCount++"
                             @removed="repliesCount--"
                    ></replies>
                </div>

                <div class="col-md-4">
                    <div class="card mb-md-5">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                                @if ($thread->replies_count)
                                    , and currently
                                    has <span v-text="repliesCount"></span> {{ Str::plural('comment', $thread->replies_count) }}.
                                    <!-- SQL query just catch count, instead of return all collection, then count. -->
                                @endif
                            </p>

                            <p>
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                                <button class="btn btn-light"
                                        v-if="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked ? 'Unlock' : 'Lock'"></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
