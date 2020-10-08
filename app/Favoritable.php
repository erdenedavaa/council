<?php


    namespace App;


    trait Favoritable
    {
        protected static function bootFavoritable()
        {
            static::deleting(function ($model) {
                $model->favorites->each->delete();
                // When a deleting associated model
                // also delete favorite.
                // sql query bj bolohgui, uchir ni
                // recordsActivity deer favorite activity delete hiisen bga
                // So, sql query don't find any Favorite instances...



                // hervee favorites() bval sql query boldog
                // () bhgui bol COLLECTION boldiin bn
                // Collection duudaj bga shaltgaan ni
                // RecordsActivity iin BOOT deer ali hediin
                //activity deleting hiisen bga. Iimees end
                // collection oruulsan
                // FIRE THE MODEL EVENTS FOR EACH
            });
        }

        public function getFavoritesCountAttribute()
        {
            return $this->favorites->count();
        }

        public function isFavorited()
        {
            return !! $this->favorites->where('user_id', auth()->id())->count();
        }

        public function getIsFavoritedAttribute() // $reply->isFavorited
        {
            return $this->isFavorited();
        }

        // minii zohioson
        public function getIsUserLoggedInAttribute()  // $reply->isUserLoggedIn
        {
            return auth()->check();
        }

        public function favorite()
        {
            $attributes = ['user_id' => auth()->id()];

            if (! $this->favorites()->where($attributes)->exists()) {
                return $this->favorites()->create($attributes);
            }
        }

        public function unfavorite()
        {
            $attributes = ['user_id' => auth()->id()];

            $this->favorites()->where($attributes)->get()->each->delete();

            // Doorh deer syntactic shugar deer hiilee
//            $this->favorites()->where($attributes)->get()->each(function ($favorite) {
//                $favorite->delete();
//            });

            // If you want to model events to fire
            // You have to delete a model (if you can't perform with sql query with query builder)
            // 1. get the collection of favorites
            // 2. filter over that collection
            // 3. for each one you gonna delete favorite
        }

        public function favorites()
        {
            return $this->morphMany(Favorite::class, 'favorited');
        }
    }
