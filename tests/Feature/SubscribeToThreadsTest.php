<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        // Given we have a thread ...
        $thread = create('App\Thread');

        // And the user subscribe to the thread...
        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        // Given we have a thread ...
        $thread = create('App\Thread');

        $thread->subscribe();

        // And the user subscribe to the thread...
        $this->delete($thread->path() . '/subscriptions');

//        dd($thread->subscription);
        $this->assertCount(0, $thread->subscriptions);
    }
}
