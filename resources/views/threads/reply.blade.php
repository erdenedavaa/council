<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="my-2"  v-bind:class="{ 'd-none': isDeleted }">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <a href="{{ route('profile', $reply->owner) }}">
                            {{ $reply->owner->name }}
                        </a> said {{ $reply->created_at->diffForHumans() }}...
                    </h6>

{{--                    @if (Auth::check())--}}
                        <div>
                            <favorite :reply="{{ $reply }}"></favorite>

                            {{--                        <form method="POST" action="/replies/{{ $reply->id }}/favorites">--}}
                            {{--                            @csrf--}}

                            {{--                            <button type="submit"--}}
                            {{--                                    class="btn btn-light btn-outline-secondary"--}}
                            {{--                                {{ $reply->isFavorited() ? 'disabled' : '' }}--}}
                            {{--                            >--}}
                            {{--                                                            {{ $reply->favorites()->count() }} {{ Str::plural('Favorite', $reply->favorites()->count()) }}--}}
                            {{--                                {{ $reply->favorites_count}} {{ Str::plural('Favorite', $reply->favorites_count) }}--}}

                            {{--                            </button>--}}
                            {{--                        </form>--}}
                        </div>
{{--                    @endif--}}

                </div>
            </div>

            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body"></textarea>
                    </div>

                    <button class="btn btn-sm btn-primary" @click="update">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>

                </div>

                <div v-else v-text="body"></div>
            </div>

            @can('update', $reply)
                <div class="card-footer d-flex">
                    <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
                    <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>

{{--                    <form method="POST" action="/replies/{{ $reply->id }}">--}}
{{--                        @csrf--}}
{{--                        @method('DELETE')--}}

{{--                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>--}}
{{--                    </form>--}}
                </div>
            @endcan
        </div>
    </div>
</reply>
