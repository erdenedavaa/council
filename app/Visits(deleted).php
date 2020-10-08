<?php

    namespace App;

    use Illuminate\Support\Facades\Redis;

    class Visits
    {
        protected $thread;

        public function __construct($thread)
        {
            $this->thread = $thread;
        }

        public function record()
        {
            Redis::incr($this->CacheKey());

            return $this;
        }

        public function reset()
        {
            Redis::del($this->CacheKey());

            return $this;
            // chaining hiihiin tuld return $this hiij bn
        }

        public function count()
        {
            return Redis::get($this->CacheKey()) ?? 0;
            // hervee iu ch butsaahgui bol default ni 0
        }

        protected function CacheKey()
        {
            return "threads.{$this->thread->id}.visits";
        }
    }
