<?php

namespace Tests\Unit;

use App\Models\Key;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class KeyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $user = User::find(1);
        $this->be($user);
    }

    public function test_key_index(): void
    {
        $response = $this->get('/key');

        $response->assertRedirect('/key/list');
    }

    public function test_key_create_get(): void
    {
        $response = $this->get('/key/create');

        $response->assertOk();
    }

    public function test_key_create_post_duplicate_key(): void
    {
        $data = [
            'name' => 'TestKey2',
            'api_key' => 'sfIvEcNueZtwKsTAIYOIYng1iuPAgavJsfIvEcNueZtwKsTAIYOIYng1iuPAgavJ',
        ];

        $response = $this->post('/key/create', $data);
        $response->assertRedirect('/key/create');
        $response->assertSessionHasErrors('api_key');
    }

    public function test_key_create_post_duplicate_name(): void
    {
        $data = [
            'name' => 'TestKey',
            'api_key' => 'abIvEcNueZtwKsTAIYOIYng1iuPAgavJsfIvEcNueZtwKsTAIYOIYng1iuPAgavJ',
        ];

        $response = $this->post('/key/create', $data);
        $response->assertRedirect('/key/create');
        $response->assertSessionHasErrors('name');
    }

    public function test_key_create_post(): void
    {
        $data = [
            'name' => 'TestKey2',
            'api_key' => 'abCvEcNueZtwKsTAIYOIYng1iuPAgavJsfIvEcNueZtwKsTAIYOIYng1iuPAgavJ',
        ];

        $response = $this->post('/key/create', $data);
        $response->assertRedirect('/key/list');
        $response->assertSessionHas('success');
    }

    public function test_key_delete_get(): void
    {
        $key = Key::find(1);

        $response = $this->get('/key/delete/'.$key->id);

        $response->assertOk();
    }

    public function test_key_delete_get_invalid_id(): void
    {
        $response = $this->get('/key/delete/100000');
        $response->assertRedirect('/key/list');
    }

    public function test_key_delete_post_invalid_id(): void
    {
        $response = $this->post('/key/delete/100000');
        $response->assertRedirect('/key/list');
    }

    public function test_key_delete_post(): void
    {
        $key = Key::firstOrFail();

        $response = $this->post('/key/delete/'.$key->id);
        $response->assertRedirect('/key/list');
        $response->assertSessionHas('success');
    }

    public function test_key_list(): void
    {
        $response = $this->get('/key/list');

        $response->assertOk();
    }
}
