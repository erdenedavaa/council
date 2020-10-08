@extends('layouts.app')

@section('head')

    <script src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function onSubmit(token) {
            document.getElementById("input").submit();
        }
    </script>

{{--    --}}{{-- RECAPTCHA start --}}
{{--    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>--}}
{{--    <script>--}}
{{--        function onClick(e) {  // байгаагүй, гэхдээ сүүлд нэмсэн--}}
{{--            e.preventDefault(); // байгаагүй, гэхдээ сүүлд нэмсэн--}}
{{--            grecaptcha.ready(function () {--}}
{{--                grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'submit'}).then(function (token) {--}}
{{--                    if (token) {--}}
{{--                        document.getElementById('recaptcha').value = token;--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        } // байгаагүй, гэхдээ сүүлд нэмсэн--}}
{{--    </script>--}}
{{--    --}}{{-- RECAPTCHA end --}}

@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">
                        <form method="POST" action="/threads" id="input">
                            @csrf

                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose one...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="body">Body:</label>

                                <wysiwyg name="body"></wysiwyg>

{{--                                <textarea class="form-control" id="body" placeholder="" rows="8" name="body" required>{{ old('body') }}</textarea>--}}
                            </div>

                            <div class="form-group">
{{--                                <button type="submit" class="btn btn-primary">Publish</button>--}}

                                <button class="btn btn-primary g-recaptcha"
                                        data-sitekey="6LfMBNMZAAAAAOFtPemM5rHGryG-kXHjUVIzbKuK"
                                        data-callback='onSubmit'
                                        data-action='submit'>Publish</button>
                            </div>

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif


{{--                            --}}{{-- RECAPTCHA start --}}
{{--                            <input type="hidden" name="recaptcha" id="recaptcha">--}}
{{--                            --}}{{-- RECAPTCHA end --}}
                        </form>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
