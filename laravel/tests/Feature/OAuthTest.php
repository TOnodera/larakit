<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function getContentsFromAccessToken()
    {
        $http = new Client;

        $response = $http->post('http://localhost:8000/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '93759dfd-de77-46e1-8507-273701c75ed8',
                'client_secret' => 'kKDFvmpYiR8GGYHyl7pZYLO1jdAQmPu4XDaCb2xf',
                'username' => 't.onodera@gmail.com',
                'password' => 't.onodera@gmail.com',
                'scope' => '',
            ],
        ]);

        $responseArray = json_decode($response->getBody());
        $access_token = $responseArray->access_token;
        $this->assertNotNull($access_token);

        $response = $http->request('GET', 'http://localhost:8000/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer ${access_token}"
            ]
        ]);

        var_dump(json_decode($response->getBody()));
    }
}
