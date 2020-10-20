@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-2">
                <ul class="nav flex-column nav-pills">
                    <li class="nav-item"><a href="{{ route('admin.dashboard.index') }} " class="nav-link active">Dashboard</a></li>
                    <li class="nav-item"><a href="{{ route('admin.channels.index') }}" class="nav-link">Channels</a></li>
                </ul>
            </div>

            <div class="col-md-10">
                <div class="tab-content">
                    <div class="tab-pane fade show active">
                        @yield('administration-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
