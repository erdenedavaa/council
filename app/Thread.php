<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
// use Facades\App\Reputation;

class Thread extends Model
{
    use RecordsActivity, Searchable;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // doorh ni bainga duudagddag, harin uuniig
        // DBiin columnd oruulbal nemelt query hiigdehgui
//        static::addGlobalScope('replyCount', function($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        // When this thread created first time...
        // ene ni setSlugAttribute($value) -ruu auto pass hiigdene
        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);

            Reputation::award($thread->creator, Reputation::THREAD_WAS_PUBLISHED);

            // $thread->creator->increment('reputation', Reputation::THREAD_WAS_PUBLISHED);
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
//
//    public function getReplyCountAttribute()
//    {
//        return $this->replies()->count();
//    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
//        (new \App\Spam)->detect($reply->body);
//        if ($this->locked) {
//            throw new \Exception('Thread is locked');
//        }

        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

//        $this->notifySubscribers($reply); // listener

//        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    // LockThreadsController дээр хялбарчилал хийсэн тул устгав
//    public function lock()
//    {
//        $this->update(['locked' => true]);
//    }
//
//    public function unlock()
//    {
//        $this->update(['locked' => false]);
//    }

   // public function notifySubscribers($reply)
   // {
//        $this->subscriptions
//            ->where('user_id', '!=', $reply->user_id)
//            ->each
//            ->notify($reply);
//    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this; // return this instance
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user = null)
    {
        $user = $user ?: auth()->user();

        // Look in the cache for the proper key.
        // compare that carbon instance with the $thread->updated_at
        $key = auth()->user()->visitedThreadCacheKey($this);
        // users.50.visits.1

        return $this->updated_at > cache($key);
    }

    // id bish slug aar url zaagdahiin tul
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // This is triggered automatically when assign, when set a value to this SLUG
    // BELOW IS NOT PERSISTING
    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

//        var_dump($slug);

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);

        // $reply->owner->increment('reputation', 50);
        Reputation::award($reply->owner, Reputation::BEST_REPLY_AWARDED);
    }

    public function toSearchableArray()
    {
        // Үүгээр зөвхөн title -ууд pass
        // return ['title' => $this->title];

        return $this->toArray() + ['path' => $this->path()];
    }

    // Yyniig accessor gedeg ium bn
    // Ba
    public function getBodyAttribute($body)
    {
        // return $body;
        return \Purify::clean($body);
    }


}
