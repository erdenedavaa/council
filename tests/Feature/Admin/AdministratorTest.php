<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    // function an_administrator_can_access_the_administration_section_version2()
    // {
    //     $administrator = create('App\User');
    //
    //     config(['council.administrators' => [ $administrator->email ]]);
    //
    //     // dd(config(['council.administrators']));
    //
    //     $this->actingAs($administrator)
    //         ->get(route('admin.dashboard.index'))
    //         ->assertStatus(Response::HTTP_OK);
    // }

    /** @test */
    public function an_administrator_can_access_the_administration_section()
    {
        $administrator = factory(User::class)->states('administrator')->create();

        $this->actingAs($administrator)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    function a_non_administrator_cannot_access_the_administration_section()
    {
        $regularUser = factory(User::class)->create();

        $this->actingAs($regularUser)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
