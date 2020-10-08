<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        // /replies/id/favorites
        $reply = create('App\Reply');

        // If I post to a "favorite" endpoint
        $this->post('replies/' . $reply->id . '/favorites');

//        dd(\App\Favorite::all());

        // It should be recorded in the database
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        // Bidend unfovirite hiih ni chuhal bga tul doorhig comment hiigeed
        // togch bdlaar shiidlee.
//        $this->post('replies/' . $reply->id . '/favorites');
//
//        $this->assertCount(1, $reply->favorites);

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');

        // Deerh neg hesgiin comment hiisen tul door bga fresh() instance
        // hereggui
        // $this->assertCount(0, $reply->fresh()->favorites);
         $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch(\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
