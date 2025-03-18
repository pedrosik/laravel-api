<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\ValueObjects\UserRole;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

abstract class ApiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    protected User $user;

    protected static ?User $admin = null;

    protected $defaultHeaders = [
        'Accept' => 'application/json',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user outside of transaction if it doesn't exist
        if (!isset(static::$admin)) {
            static::$admin = $this->createAdminUser();
        }

        // Verify admin exists in database, recreate if needed
        if (!User::find(static::$admin->id)) {
            static::$admin = $this->createAdminUser();
        }
    }

    protected function getUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::USER);

        return $user;
    }

    protected static function getAdmin(): User
    {
        return static::$admin;
    }

    protected function createAdminUser(): User
    {
        // Manually commit admin user creation to prevent transaction rollback
        $admin = DB::transaction(function () {
            $admin = User::factory()->create();
            $admin->assignRole(UserRole::ADMIN);
            return $admin;
        });

        return $admin;
    }

    /**
     * Replacement for standard dump($value) helper as it causes strange side-effects and errors during tests
     */
    protected function dump(...$values): void
    {
        foreach ($values as $value) {
            fwrite(STDERR, print_r($value, true)."\n");
        }
    }

    /**
     * Helper method that dumps unexpected response data
     */
    protected function getd(?Authenticatable $user, string $url, int $expectedStatus = 200): TestResponse
    {
        $response = $user
            ? $this->actingAs($user)->get($url)
            : $this->get($url);

        if ($expectedStatus !== $response->getStatusCode()) {
            $this->dump($response->json());
        }

        $response->assertStatus($expectedStatus);

        return $response;
    }

    /**
     * Helper method that dumps unexpected response data
     */
    protected function postd(?Authenticatable $user, string $url, array $data, int $expectedStatus = 200): TestResponse
    {
        $response = $user
            ? $this->actingAs($user)->post($url, $data)
            : $this->post($url, $data);

        if ($expectedStatus !== $response->getStatusCode()) {
            $this->dump($response->json());
        }

        $response->assertStatus($expectedStatus);

        return $response;
    }

    /**
     * Helper method that dumps unexpected response data
     */
    protected function patchd(?Authenticatable $user, string $url, array $data, int $expectedStatus = 200): TestResponse
    {
        $response = $user
            ? $this->actingAs($user)->patch($url, $data)
            : $this->patch($url, $data);

        if ($expectedStatus !== $response->getStatusCode()) {
            $this->dump($response->json());
        }

        $response->assertStatus($expectedStatus);

        return $response;
    }

    /**
     * Helper method that dumps unexpected response data
     */
    protected function deld(?Authenticatable $user, string $url, int $expectedStatus = 204): TestResponse
    {
        $response = $user
            ? $this->actingAs($user)->delete($url)
            : $this->delete($url);

        if ($expectedStatus !== $response->getStatusCode()) {
            $this->dump($response->json());
        }

        $response->assertStatus($expectedStatus);

        return $response;
    }
}
