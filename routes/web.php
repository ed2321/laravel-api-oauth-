<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/redirect2', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '9467ad51-267e-41b8-89ff-12df47ac406b',
        'redirect_uri' => 'http://docker-laravel-passport-oauth.com',
        'response_type' => 'token',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect('http://docker-laravel-passport-oauth.com/oauth/authorize?'.$query);
})->middleware(['auth:sanctum', 'verified']);

// Route::get('/redirect', function (Request $request) {
//     $request->session()->put('state', $state = Str::random(40));

//     $request->session()->put(
//         'code_verifier', $code_verifier = Str::random(128)
//     );

//     $codeChallenge = strtr(rtrim(
//         base64_encode(hash('sha256', $code_verifier, true))
//     , '='), '+/', '-_');

//     $query = http_build_query([
//         'client_id' => '9467c02b-79ee-4762-939e-18f55895e1f3',
//         'redirect_uri' => 'http://docker-laravel-passport-oauth.com/callback',
//         'response_type' => 'code',
//         'scope' => '',
//         'state' => $state,
//         'code_challenge' => $codeChallenge,
//         'code_challenge_method' => 'S256',
//     ]);

//     return redirect('http://docker-laravel-passport-oauth.com/oauth/authorize?'.$query);
// })->middleware(['auth:sanctum', 'verified']);

// Route::get('/callback', function (Request $request) {
//     $state = $request->session()->pull('state');

//     $codeVerifier = $request->session()->pull('code_verifier');

//     throw_unless(
//         strlen($state) > 0 && $state === $request->state,
//         InvalidArgumentException::class
//     );

//     $response = Http::asForm()->post('http://docker-laravel-passport-oauth.com/oauth/token', [
//         'grant_type' => 'authorization_code',
//         'client_id' => '9467c02b-79ee-4762-939e-18f55895e1f3',
//         'redirect_uri' => 'http://docker-laravel-passport-oauth.com/callback',
//         'code_verifier' => $codeVerifier,
//         'code' => $request->code,
//     ]);

//     return $response->json();
// });
