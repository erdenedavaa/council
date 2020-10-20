<?php

    namespace Tests\Feature;

    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Tests\TestCase;
    use Illuminate\Foundation\Testing\DatabaseMigrations;

    class BestReplyTest extends TestCase
    {
        use RefreshDatabase;

        /** @test */
        function a_thread_creator_may_mark_any_reply_as_the_best_reply()
        {
            $this->signIn();

            $thread = create('App\Thread', ['user_id' => auth()->id()]);

            $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

            $this->assertFalse($replies[1]->isBest());

            // Hit from frontEnd gesen utgatei
            $this->postJson(route('best-replies.store', [$replies[1]->id]));

            $this->assertTrue($replies[1]->fresh()->isBest());
        }

        /** @test */
        function only_the_thread_creator_may_mark_a_reply_as_best()
        {
            $this->withExceptionHandling();

            $this->signIn();

            $thread = create('App\Thread', ['user_id' => auth()->id()]);

            $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

            // Create different user
            $this->signIn(create('App\User'));

            $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);

            $this->assertFalse($replies[1]->fresh()->isBest());
        }

        /** @test */
        function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
        {
            $this->signIn();

            $reply = create('App\Reply', ['user_id' => auth()->id()]);
            // create a reply, thread is created behind scenes

            $reply->thread->markBestReply($reply);

            // When we delete the reply
            $this->deleteJson(route('replies.destroy', $reply)); // $reply->id  gej bolno. gehdee laravel ni
            // automataar collection-oos id-g fetch hiij chaddag

            // Then the thread should be updated.
            $this->assertNull($reply->thread->fresh()->best_reply_id);


            // Хоёр янзын арга зам байгаа. Нэгд, PHP side, нөгөө нь DATABASE level
        }
    }
