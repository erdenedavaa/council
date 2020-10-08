<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;

class RepliesController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param $channeld
     * @param \App\Thread $thread
     * @param \App\Http\Forms\CreatePostRequest $form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function store($channeld, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }
        // дээрхийг доор байгаа addReply руу pushed down хийе

        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');

        // Энгийн байлгах үүднээс хуучинд нь дээрхээр буцаав
//        try {
//            return $thread->addReply([
//                'body' => request('body'),
//                'user_id' => auth()->id()
//            ])->load('owner');
//
//        } catch(\Exception $e) {
//            return response('Locked', 422);
//        }

    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => ['required', new SpamFree]
        ]);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
//        if ($reply->user_id != auth()->id()) {
//            return response([], 403);
//        }
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
        // butsaah utga ni 302 bdag.
    }
}

