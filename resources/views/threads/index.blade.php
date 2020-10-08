@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')

                {{ $threads->render() }}
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        Search
                    </div>

                    <div class="card-body">
                        <form method="GET" action="/threads/search">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." name="q" class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </div>

                        </form>
                    </div>
                </div>

                @if (count($trending))
                    <div class="card">
                        <div class="card-header bg-transparent">
                            Trending Threads
                        </div>

                        <div class="card-body">
                            @foreach ($trending as $thread)
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <a href="{{ url($thread->path) }}">
                                            {{ $thread->title }}
                                        </a>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

