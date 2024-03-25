<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    public function testAuthenticate()
    {
        $user = User::factory()->anakin()->create(['password' => Hash::make('123456')]);
        $role = Role::factory()->editor()->create();
        $user->roles()->save($role);

        $res = $this->json('POST', '/api/v1/authenticate', [
            'email' => 'admin@gmail.com',
            'password' => '123456'
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'provider',
                    'provider_id',
                    'registered_at',
                    'comments_count',
                    'posts_count',
                    'roles' => [[
                        'id',
                        'name'
                    ]]
                ],
                'meta' => [
                    'access_token'
                ]
            ]);
    }

    public function testAuthenticateFail()
    {
        $user = User::factory()->anakin()->create(['password' => Hash::make('123456')]);
        $user->roles()->save(
            Role::factory()->editor()->create()
        );

        $this->json('POST', '/api/v1/authenticate', [
            'email' => 'anakin@skywalker.st',
            'password' => 'Luk3'
        ])
            ->assertUnauthorized()
            ->assertJson([
                'message' => 'This action is unauthorized.'
            ]);
    }
}
