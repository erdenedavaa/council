<?php

    namespace Tests\Unit;

    use App\Notifications\ThreadWasUpdated;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Facades\Redis;
    use Tests\TestCase;

    class ThreadTest extends TestCase
    {
        use DatabaseMigrations;

        protected $thread;

        public function setUp(): void
        {
            parent::setUp();

            $this->thread = create('App\Thread');
        }

        /** @test */
        function a_thread_has_a_path()
        {
            $thread = create('App\Thread');

            $this->assertEquals(
                "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
            );
        }

        /** @test */
        function a_thread_has_replies()
        {
            $this->assertInstanceOf(
                'Illuminate\Database\Eloquent\Collection', $this->thread->replies
            );
        }

        /** @test */
        function a_thread_has_a_creator()
        {
            $this->assertInstanceOf('App\User', $this->thread->creator);
        }

        /** @test */
        function a_thread_can_add_a_reply()
        {
            $this->thread->addReply([
                'body'    => 'Foobar',
                'user_id' => 1
            ]);

            $this->assertCount(1, $this->thread->replies);
        }

        /** @test */
        function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
        {
            Notification::fake();

            // $this->signIn();
            // eniig mun chaining hiij boldog.

            // $this->thread->subscribe();
            // subscribe() ni collection butsaadag. So doorh bdlaar hiivel deer.

            $this->signIn()
                ->thread
                ->subscribe()
                ->addReply([
                    'body'    => 'Foobar',
                    'user_id' => 999
            ]);

            Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
        }

        /** @test */
        function a_thread_belongs_to_a_channel()
        {
            $thread = create('App\Thread');

            $this->assertInstanceOf('App\Channel', $thread->channel);
        }

        /** @test */
        function a_thread_can_be_subscribed_to()
        {
            // Given we have a thread
            $thread = create('App\Thread');

            // And an authenticated user
//            $this->signIn();
            // When the user subscribes to the thread
//            $thread->subscribe();
            // ene uildliig tovchloh uudnees daraab baidlaar shinechillee

            $thread->subscribe($userId = 1);

            // Then we should be able to fetch all threads that the user has subscribed to.
            $this->assertEquals(
                1,
//                $thread->subscription()->where('user_id', auth()->id())->count()
                // deerh uurchlulttei holbootoigoor daraah baidlaar hiisen
                $thread->subscriptions()->where('user_id',$userId)->count()
                // ene uurchlultiig hiisneer bid never bother signIn user
            );
        }

        /** @test */
        function a_thread_can_be_unsubscribed_from()
        {
            // Given we have a thread
            $thread = create('App\Thread');

            // And a user who is subscribed to the thread.
            $thread->subscribe($userId = 1);

            $thread->unsubscribe($userId);

            $this->assertCount(0, $thread->subscriptions);
        }

        /** @test */
        function it_knows_if_authenticated_user_is_subscribe_to_it()
        {
            // Given we have a thread
            $thread = create('App\Thread');

            // And a user who is subscribed to the thread.
            $this->signIn();

            $this->assertFalse($thread->isSubscribedTo);

            $thread->subscribe();

            $this->assertTrue($thread->isSubscribedTo);
        }

        /** @test */
        function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
        {
            $this->signIn();

            $thread = create('App\Thread');

            tap(auth()->user(), function($user) use ($thread) {
                $this->assertTrue($thread->hasUpdatesFor($user));

                $user->read($thread);

                $this->assertFalse($thread->hasUpdatesFor($user));
            });
        }

        /** @test */
        function a_thread_body_is_sanitized_automatically()
        {
            $thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is ok.</p>']);  // Databased
            // store hiihgui tul make() hiij bn

            // dd($thread->body);

            $this->assertEquals("<p>This is ok.</p>", $thread->body);
        }


//        /** @test */
//        function a_thread_records_each_visit()
//        {
//            $thread = make('App\Thread', ['id' => 1]);
//
//            $thread->visits()->reset();
//
//            $this->assertSame(0, $thread->visits()->count());
//
//            $thread->visits()->record();
////
//            $this->assertEquals(1, $thread->visits()->count());
//
//            $thread->visits()->record();
////
//            $this->assertEquals(2, $thread->visits()->count());
//        }

    }
