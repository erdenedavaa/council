<?php

    namespace Tests\Feature;

    use App\Mail\PleaseConfirmYourEmail;
    use App\User;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Facades\Mail;
    use Tests\TestCase;

    class RegistrationTest extends TestCase
    {
        use RefreshDatabase;

        /** @test */
        function a_confirmation_email_is_sent_upon_registration()
        {
            Mail::fake();

            $this->post(route('register'), [
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'mongolia',
                'password_confirmation' => 'mongolia'
            ]);

            Mail::assertQueued(PleaseConfirmYourEmail::class);
            //        Event::assertDispatched(Registered::class);
        }

        /** @test */
        function user_can_fully_confirm_their_email_addresses()
        {
            Mail::fake();

            $this->post(route('register'), [
                'name'                  => 'John',
                'email'                 => 'john@example.com',
                'password'              => 'mongolia',
                'password_confirmation' => 'mongolia'
            ]);

            $user = User::whereName('John')->first();

            $this->assertFalse($user->confirmed);

            $this->assertNotNull($user->confirmation_token);

            // Let the user confirm their account.
            // route('foo', ['one' => 'thing'])       // foo?one=thing
            $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
                ->assertRedirect(route('threads'));

            tap($user->fresh(), function ($user) {
                $this->assertTrue($user->confirmed);
                $this->assertNull($user->confirmation_token);
            });
        }

        /** @test */
        function confirming_an_invalid_token()
        {
            $this->get(route('register.confirm', ['token' => 'invalid']))
                ->assertRedirect(route('threads'))
                ->assertSessionHas('flash', 'Unknown token.');

        }
    }
