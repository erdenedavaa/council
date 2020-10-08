<?php

    namespace App\Http\Controllers;

    use App\Channel;
    use App\Filters\ThreadFilters;
    use App\Rules\Recaptcha;
    use App\Thread;
    use App\Trending;
    use Carbon\Carbon;
    use Illuminate\Http\Request;

    class ThreadsController extends Controller
    {
        /**
         * Create a new ThreadsController instance
         */
        public function __construct()
        {
            //        $this->middleware('auth')->only(['create', 'store']);
            $this->middleware('auth')->except(['index', 'show']);
        }


        /**
         * Display a listing of the resource.
         *
         * @param \App\Channel $channel
         * @param \App\Filters\ThreadFilters $filters
         * @param \App\Trending $trending
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
        {
            $threads = $this->getThreads($channel, $filters);

            if (request()->wantsJson()) {
                return $threads;
            }

            return view('threads.index', [
                'threads'  => $threads,
                'trending' => $trending->get()
            ]);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function create()
        {
            return view('threads.create');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Rules\Recaptcha $recaptcha
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
         */
        public function store(Recaptcha $recaptcha)
        {
            // dd(request()->all());
            // Үүгээр google recaptcha гийн frontside -аас код ирсэн нь харагдана (g-recaptcha-response)

            request()->validate([
                'title'                => 'required|spamfree',
                'body'                 => 'required|spamfree',
                'channel_id'           => 'required|exists:channels,id',
                'g-recaptcha-response' => ['required', $recaptcha]
            ]);

            $thread = Thread::create([
                'user_id'    => auth()->id(),
                'channel_id' => request('channel_id'),
                'title'      => request('title'),
                'body'       => request('body'),
                //            'slug' => request('title')
                // Thread model iin boot()-d zaaj ugsun bolhoor
                // automataar tsaashdaa yyseed yawna
            ]);

            if (request()->wantsJson()) {
                return response($thread, 201);
            }

            return redirect($thread->path())
                ->with('flash', 'Your thread has been published!');
        }

        /**
         * Display the specified resource.
         *
         * @param $channel
         * @param \App\Thread $thread
         * @param \App\Trending $trending
         * @return \App\Thread|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function show($channel, Thread $thread, Trending $trending)
        {
            // return $thread;  // Laravel cast that to json

            if (auth()->check()) {
                auth()->user()->read($thread);
            }

            $trending->push($thread);

            $thread->increment('visits');

            // increment() ni laravel-d tsaanaasaa bdag

            return view('threads.show', compact('thread'));
        }

        public function update($channel, Thread $thread)
        {
            // this authorize-ийн update policy-г $thread дээр хэрэгжүүлнэ гэсэн утгатай
            $this->authorize('update', $thread);

            // $thread->update(request(['body', 'title'])); // thread update hiihed 'title', 'body' хоёул хэрэгтэй
            // гэдгийг батлах зорилгоор оруулж үзсэн

            $thread->update(request()->validate([
                'title' => 'required|spamfree',
                'body'  => 'required|spamfree'
            ]));

            return $thread;
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param \App\Thread $thread
         * @return \Illuminate\Http\Response
         */
        public function edit(Thread $thread)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Thread $thread
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
         */
        public function destroy($channel, Thread $thread)
        {
            $this->authorize('update', $thread);

            $thread->delete();

            if (request()->wantsJson()) {
                return response([], 204);
            }

            return redirect('/threads');

        }

        /**
         * @param \App\Filters\ThreadFilters $filters
         * @param \App\Channel $channel
         * @return mixed
         */
        protected function getThreads(Channel $channel, ThreadFilters $filters)
        {
            $threads = Thread::latest()->filter($filters);

            if ($channel->exists) {
                $threads->where('channel_id', $channel->id);
            }

            //        dd($threads->toSql());
            // testlehed, dan sql query haruulahad ashiglasan

            //        $threads = $threads->get();

            return $threads->paginate(25);
        }

    }
