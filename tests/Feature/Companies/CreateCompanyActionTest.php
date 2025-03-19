<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Actions\Companies;

use Tests\Feature\ApiTest;
use Symfony\Component\HttpFoundation\Response;

class CreateCompanyActionTest extends ApiTest
{
    public function test_it_creates_company_successfully(): void
    {
        $data = [
            'name'      => 'Test Company',
            'nip'       => '1234567890',
            'address'   => 'Test Street',
            'city'      => 'Test City',
            'post_code' => '12-345',
        ];

        $response = $this->postd(
            $this->getUser(),
            '/api/companies',
            $data,
            Response::HTTP_CREATED
        )->json('data');

        $this->assertEquals($data['name'], $response['name']);
        $this->assertEquals($data['nip'], $response['nip']);
        $this->assertEquals($data['address'], $response['address']);
        $this->assertEquals($data['city'], $response['city']);
        $this->assertEquals($data['post_code'], $response['post_code']);

        $this->assertDatabaseHas('companies', [
            'name'      => $data['name'],
            'nip'       => $data['nip'],
            'address'   => $data['address'],
            'city'      => $data['city'],
            'post_code' => $data['post_code'],
        ]);
    }

    public function test_it_returns_error_when_name_is_missing(): void
    {
        $data = [
            'nip'       => '1234567890',
            'address'   => '123 Test Street',
            'city'      => 'Test City',
            'post_code' => '12-345',
        ];

        $response = $this->postd(
            $this->getUser(),
            '/api/companies',
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'name' => __('validation.required', ['attribute' => 'nazwa']),
        ]);

        $this->assertDatabaseMissing('companies', [
            'nip'       => $data['nip'],
            'address'   => $data['address'],
            'city'      => $data['city'],
            'post_code' => $data['post_code'],
        ]);
    }

    public function test_it_returns_error_when_nip_is_invalid(): void
    {
        $data = [
            'name'      => 'Test Company',
            'nip'       => '1234',
            'address'   => '123 Test Street',
            'city'      => 'Test City',
            'post_code' => '12-345',
        ];

        $response = $this->postd(
            $this->getUser(),
            '/api/companies',
            $data,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors([
            'nip' => __('validation.custom.nip_invalid'),
        ]);

        $this->assertDatabaseMissing('companies', [
            'name'      => $data['name'],
            'nip'       => $data['nip'],
            'address'   => $data['address'],
            'city'      => $data['city'],
            'post_code' => $data['post_code'],
        ]);
    }
}