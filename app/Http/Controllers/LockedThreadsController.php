<?php

    namespace App\Http\Controllers;

    use App\Thread;
    use Illuminate\Http\Request;

    class LockedThreadsController extends Controller
    {
        public function store(Thread $thread)
        {
            // Доорх хэрэгггүй болсон. Учир нь, Administrator middleware үүсгээд
            // Түүн дээр заагаад өгсөн
//            if (! auth()->user()->isAdmin()) {
//                return response('You do not have permission to lock this thread.', 403);
//            }

//            $thread->lock();
            // Laravel uuriin lock methodtoi tul ergelzee yysch bolno
            // дээрхийг дараах байдлаар шууд хийж болно
            $thread->update(['locked' => true]);
        }

        public function destroy(Thread $thread)
        {
//            $thread->unlock();
            $thread->update(['locked' => false]);

        }
    }
