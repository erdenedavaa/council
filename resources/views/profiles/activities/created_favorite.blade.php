@component('profiles.activities.activity')
    @slot('heading')
{{--        {{ $profileUser->name }} favorited a reply.--}}
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} favorited a reply.
        </a>
{{--        <a href="{{ $activity->subject->thread->path() }}">"{{ $activity->subject->thread->title }}"</a>--}}
    @endslot

    @slot('body')
{{--        {{ $activity->subject->body }}--}}
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
