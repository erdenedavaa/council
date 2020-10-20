<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $user = $user?: create('App\User');

        $this->actingAs($user);

        return $this;
    }

    protected function signInAdmin($admin = null)
    {
        $admin = factory(User::class)
            ->states('administrator')
            ->create();

        $this->actingAs($admin);

        return $this;
    }


}
