<?php
if ( @Request::header()['cf-connecting-ip'][0] == "86.123.55.12" ) {
    config(['app.debug' => true]);
} else {
    config(['app.debug' => false]);
}


setlocale(LC_CTYPE, 'ro_RO');
\Carbon\Carbon::setLocale('ro');
define('APP_PATH','/home/execom/execom');
define('APP_PUBLIC_PATH',APP_PATH.'/public');
define('APP_PUBLIC_URL','http://execom.ro');
define('APP_NAME','eXecom');
define('APP_SHORT_LINK', 'execom.ro');
define('APP_EMAIL', 'admin@execom.ro');
define('GOOGLE', '<span style="font-family: Georgia;"><span style="color:blue;">G</span><span style="color:red;">o</span><span style="color:orange;">o</span><span style="color:blue;">g</span><span style="color:green;">l</span><span style="color:red;">e</span></span>');




Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');



Route::get('/', function () {
    return view('Index.index');
});
