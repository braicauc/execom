<?php
if ( @Request::header()['cf-connecting-ip'][0] == "86.123.55.12" ) {
    config(['app.debug' => true]);
} else {
    config(['app.debug' => false]);
}


setlocale(LC_CTYPE, 'ro_RO');
\Carbon\Carbon::setLocale('ro');
define('APP_PATH','');
define('APP_PUBLIC_PATH','');
define('APP_PUBLIC_URL','');
define('APP_NAME','');
define('APP_SHORT_LINK', '');
define('APP_EMAIL', '');
define('GOOGLE', '<span style="font-family: Georgia;"><span style="color:blue;">G</span><span style="color:red;">o</span><span style="color:orange;">o</span><span style="color:blue;">g</span><span style="color:green;">l</span><span style="color:red;">e</span></span>');




Route::get('/', function () {
    return view('Index.index');
});
