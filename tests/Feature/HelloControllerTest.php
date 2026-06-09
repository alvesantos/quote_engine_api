<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloControllerTest extends TestCase
{
    public function test_api_returns_json_with_string(): void
    {
        $response = $this->getJson('/api/hello');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Running API correctly'
            ]);
    }
}
