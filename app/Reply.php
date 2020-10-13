<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected static function boot()
    {
        parent::boot();

        // doorhiin davuu tal ni jishee ni,
        // factory('App\Reply')->create(); hiihed
        // automataar tootsogddgo

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');

            // $reply->owner->increment('reputation', 2);
            Reputation::award($reply->owner, Reputation::REPLY_POSTED);
        });

        static::deleted(function ($reply) {
//            if ($reply->isBest()) {
//                $reply->thread->update(['best_reply_id' => null]);
//            }
            // Дээрх арга нь PHP level арга, гэхдээ database level is preferred by Jeffrey

            $reply->thread->decrement('replies_count');
//            dd($reply->thread->replies_count);
        });
    }


    protected $appends = ['favoritesCount', 'isFavorited', 'isUserLoggedIn', 'isBest'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    // deeshee json ruu append hiihiin tuld
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

}
