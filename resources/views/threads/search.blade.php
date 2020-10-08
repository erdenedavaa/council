@extends('layouts.app')

@section('content')
    <div class="container">
        <ais-instant-search
                :search-client="searchClient"
                index-name="threads"
        >
            <div class="row">
                    <div class="col-md-8">
                            <ais-configure
                                query="{{ request('q') }}"
                            ></ais-configure>

                            <div>   <!-- Huuchin class ni class="search-panel" -->
                                <!--                    <div class="search-panel__filters">-->
                                <!--                        <ais-refinement-list attribute="channel.name" searchable />-->
                                <!--                    </div>-->

                                <div >    <!-- Huuchin ni class="search-panel__results"  -->
                                    <!--                        <ais-search-box placeholder="Search here…" class="searchbox" />-->
                                    <ais-hits>
                                        <template slot="item" slot-scope="{ item }">
                                            <p>
                                                <a :href="item.path">
                                                    <ais-highlight :hit="item" attribute="title"></ais-highlight>
                                                </a>
                                            </p>
                                                <p>
                                                    <ais-highlight :hit="item" attribute="body"></ais-highlight>
                                                </p>
                                        </template>
                                    </ais-hits>

                                    <div class="pagination">
                                        <ais-pagination></ais-pagination>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header bg-transparent">
                                Search
                            </div>

                            <div class="card-body">
                                <ais-search-box placeholder="Find a thread..." class="" autofocus></ais-search-box>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-transparent">
                                Filter by Channel
                            </div>

                            <div class="card-body search-panel__filters">
                                <ais-refinement-list attribute="channel.name"></ais-refinement-list>
                            </div>
                        </div>

                        <div class="card">
                            @if (count($trending))
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
        </ais-instant-search>
    </div>
@endsection


