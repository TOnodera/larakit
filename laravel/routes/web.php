<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $request->session()->put('code_verifier', $code_verifier = Str::random(128));

    $codeChallenge = strtr(rtrim(
        base64_encode(hash('sha256', $code_verifier, true)),
        '='
    ), '+/', '-_');

    $query = http_build_query([
        'client_id' => '6',
        'redirect_uri' => 'http://localhost:8080/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256',
    ]);



    return redirect('http://localhost:8080/oauth/authorize?' . $query);
});

Route::get('/callback', function (Request $request) {
    $state = $request->session()->pull('state');

    $codeVerifier = $request->session()->pull('code_verifier');

    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class
    );

    $response = (new GuzzleHttp\Client)->post('http://host.docker.internal:8080/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '6',
            'redirect_uri' => 'http://localhost:8080/callback',
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});
