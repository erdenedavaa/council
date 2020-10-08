<?php

    namespace Tests\Feature;

    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

    class UpdateThreadsTest extends TestCase
    {
        // use DatabaseMigrations;
        // Шинэ Laravel-д RefreshDatabase trait орж ирсэн. Дээрхийг орлох юм шиг байна.
        use RefreshDatabase;

        public function setUp():void
        {
            parent::setUp();

            $this->withExceptionHandling();

            $this->signIn();
        }

        /** @test */
        function a_thread_requires_a_title_and_body_to_be_updated()
        {
            $thread = create('App\Thread', ['user_id' => auth()->id()]);

            $this->patch($thread->path(), [
                'title' => 'Changed'
            ])->assertSessionHasErrors('body');

            $this->patch($thread->path(), [
                'body' => 'Changed'
            ])->assertSessionHasErrors('title');
        }

        /** @test */
        function unauthorized_users_may_not_update_threads()
        {
            // бусад хэрэглэгчдийг дараах байдлаар илэрхийлнэ
            $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

            $this->patch($thread->path(), [])->assertStatus(403);
        }

        /** @test */
        function a_thread_can_be_updated_by_its_creator()
        {
            // тухайн thread -ийн эзнийг дараах байдлаар илэрхийлнэ
            $thread = create('App\Thread', ['user_id' => auth()->id()]);

            // тухайн thread -ийн эзэн бишийг дараах байдлаар илэрхийлнэ. Тухайн thread-ыг үүсгээгүй user update хийх
            // боломжтой байсныг илрүүлэх зорилгоор түр доорх байдлаар хийв
            // $thread = create('App\Thread', ['user_id' => create('App\User')->id]);


            $this->patch($thread->path(), [
                'title' => 'Changed',
                'body'  => 'Changed body.'
            ]);

            // $thread = $thread->fresh();
            //
            // $this->assertEquals('Changed', $thread->title);
            // $this->assertEquals('Changed body.', $thread->body);

            // if you hate temporary variables, then tap is good
            tap($thread->fresh(), function($thread){
                $this->assertEquals('Changed', $thread->title);
                $this->assertEquals('Changed body.', $thread->body);
            });
        }
    }
