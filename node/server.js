"use strict"
var socket = require( 'socket.io' ); // socket.io library
var express = require( 'express' );  // express library
var http = require( 'http' );        // http library
var Redis = require( 'ioredis' );    // ioredis library from https://github.com/luin/ioredis used to work with our redis database
// a small library from https://www.npmjs.com/package/dotenv
// it`s used to read our env variable because we do not want to expose them here
require('dotenv').config({path: '/home/execom/execom/.env'});



// a redis instance; the enviroment variable comes from dotenv that took them from laravel .env file
var redis = new Redis({
    port: process.env.REDIS_PORT,          // Redis port
    host: process.env.REDIS_HOST,          // Redis host
    family: 4,                             // 4 (IPv4) or 6 (IPv6)
    password: process.env.REDIS_PASSWORD,
    db: 0
});




var app = express();
var server = http.createServer( app );

var io = socket.listen( server );
// io.set('origins', 'http://execom.ro:80');


// PHP in_array javascript version
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


// check if the user is indeed the user that send the message
function checkPrivateKey( privateKey, redisKeys) {
    if ( redisKeys == '' ) {
        return false;
    }
    return inArray(privateKey,redisKeys.split(","));
}

// display a message to a logged user
function warnUser(mesaj,id_user) {
    io.sockets.emit( "actiuni:user:" + id_user, { act: 'warning', mesaj: mesaj });
}


io.sockets.on( 'connection', function( client ) {

     // console.log(JSON.stringify(client.request.headers));
     // console.log(client.request.headers.origin);





});

server.listen(process.env.SOCKETPORT);
console.log('Server start on ' + process.env.SOCKETPORT);
// to view all node connections use in terminal: ps aux | grep node
