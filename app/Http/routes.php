<?php
if ( @Request::header()['cf-connecting-ip'][0] == "86.123.55.12" ) {
    config(['app.debug' => true]);
} else {
    config(['app.debug' => true]);
}


setlocale(LC_CTYPE, 'ro_RO');
\Carbon\Carbon::setLocale('ro');
define('APP_PATH',env('APP_PATH'));
define('APP_PUBLIC_PATH',env('APP_PUBLIC_PATH'));
define('APP_PUBLIC_URL',env('APP_PUBLIC_URL'));
define('APP_AVATARS_PATH',env('APP_AVATARS_PATH'));
define('APP_AVATARS_URL',env('APP_AVATARS_URL'));
define('APP_NAME',env('APP_NAME'));
define('APP_SHORT_LINK',env('APP_SHORT_LINK'));
define('APP_EMAIL',env('APP_EMAIL'));
define('GOOGLE', '<span style="font-family: Georgia;"><span style="color:blue;">G</span><span style="color:red;">o</span><span style="color:orange;">o</span><span style="color:blue;">g</span><span style="color:green;">l</span><span style="color:red;">e</span></span>');





Route::get('foruchat/{categorie}', ['as' => 'foruchat', 'uses' => 'Director\DirectorController@index']);



Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);


// Route::get('/setup_director', ['uses' => 'Setup\SetupController@setupDirector']); // !!!! we must comment this line on production



Route::get('/', ['as' => 'index_path', 'uses' => 'Index\IndexController@index']); //
