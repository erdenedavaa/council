{{--Editing the question--}}
<div class="card mb-md-5" v-if="editing">
    <div class="card-header ">

        <input type="text" class="form-control" v-model="form.title">

    </div>

    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
{{--            <textarea class="form-control" rows="10" v-model="form.body"></textarea>--}}
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <button class="btn btn-outline-primary btn-sm mr-2" @click="editing = true" v-show="! editing">Edit</button>
        <button class="btn btn-primary btn-sm mr-2" @click="update">Update</button>
        <button class="btn btn-outline-primary btn-sm" @click="resetForm">Cancel</button>

        @can ('update', $thread)
            <form action="{{ $thread->path() }}" method="POST" class="ml-auto">
                @csrf
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-sm">Delete Thread</button>
            </form>
        @endcan
    </div>
</div>

{{--Viewing the question--}}
<div class="card mb-md-5" v-else>
    <div class="card-header d-flex justify-content-between">

        <div class="d-flex align-items-center">
            <img src="{{ $thread->creator->avatar_path }}"
                 alt="{{ $thread->creator->name }}"
                 width="25"
                 height="25"
                 class="mr-1">

            <span>
                <a href="{{ route('profile', $thread->creator) }}" class="pr-1">
                    {{ $thread->creator->name }} ({{ $thread->creator->reputation }} XP)
                </a> posted:
                <span v-text="title"></span>
            </span>

        </div>

    </div>

    <div class="card-body" v-html="body"></div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-outline-primary btn-sm" @click="editing = true">Edit</button>
    </div>
</div>
