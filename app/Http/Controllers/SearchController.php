<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        // Хэрэв Json хайлтын хүсэлт ирвэл Server side search хийнэ гэсэн утгатай
        if (request()->expectsJson()) {
            return Thread::search(request('q'))->paginate(25);
        }

        // Бусад тохиолдолд Client side search хийнэ
        return view('threads.search', [
            'trending' => $trending->get()
        ]);
    }
}
