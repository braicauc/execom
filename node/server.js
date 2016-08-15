"use strict"
var ENV_PATH = require("./env.js");
var socket = require( 'socket.io' ); // socket.io library
var express = require( 'express' );  // express library
var http = require( 'http' );        // http library
var Redis = require( 'ioredis' );    // ioredis library from https://github.com/luin/ioredis used to work with our redis database
var striptags = require( 'striptags' ); // when users send messages filter html tags with striptags https://www.npmjs.com/package/striptags

// a small library from https://www.npmjs.com/package/dotenv
// it`s used to read our env variable because we do not want to expose them here
require('dotenv').config({path: ENV_PATH}); // do not forget to change that /home/execom/execom.env

// var User = require("./class/User.js");



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

// isEmpty string function
function isEmpty(str) {
    return (!str || 0 === str.length);
}

// check if the user is indeed the user that send the message
function checkPrivateKey( privateKey, redisKeys) {
    if ( isEmpty(redisKeys) ) {
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

    // messages on foruchat
    client.on( 'channel', function( data ) {

        console.log('Data from channel: ' + JSON.stringify(data) );

        redis.get( "user:det:" + data.user_id ).then(function (result) {

               var udet = JSON.parse(result);

               console.log('Datele utilizatorului: ' + JSON.stringify(udet) );

               redis.get( "user:keys:" + data.user_id ).then(function (pkeys) {

                   console.log('Cheile utilizatorului: ' + pkeys);

                if (checkPrivateKey(data.pkey, pkeys) == true) {

                    console.log('Name utilizator: ' + udet.name );

                    var emitMes = {
                        user_id    : data.user_id,
                        name       : udet.name,
                        username   : udet.username,
                        avatar     : udet.avatar,
                        message    : striptags(data.message),
                        created_at : Math.round(new Date().getTime() / 1000)
                    };

                    console.log('emitMes: ' + JSON.stringify(emitMes));
                    console.log(emitMes.created_at);

                    redis.lpush( data.channel, JSON.stringify(emitMes));
                    redis.ltrim( data.channel, 0, 5000);

                    io.sockets.emit( data.channel, emitMes );
                    console.log('----->Emit');

                } else {
                    warnUser('Ooops! Ceva nu merge. Incercati sa va autentificati din nou!', data.id);
                }

            });

        });

    });




    // handle online users on specific channel
    client.on ( 'online:users', function(data) {

        console.log('Data from channel: ' + JSON.stringify(data) );

        redis.get( "user:det:" + data.user_id ).then(function (result) {

            var udet = JSON.parse(result);

            console.log('Datele utilizatorului: ' + JSON.stringify(udet) );

            redis.get( "user:keys:" + data.user_id ).then(function (pkeys) {

                console.log('Cheile utilizatorului: ' + pkeys);

                if (checkPrivateKey(data.pkey, pkeys) == true) {

                    console.log('Name utilizator: ' + udet.name );

                    var $key = 'online:user:' + data.channel + ":" + data.user_id;
                    var d = new Date();
                    var created_at = Math.floor(( d.getTime() ) / 1000);
                    console.log(created_at);

                    redis.exists($key, function (err, result) {
                        if ( result == 1 ) {

                            /** Check online users */
                            redis.smembers('patterns:online:user:' + data.channel, function(err, vonline){

                                for (var index = 0, len = vonline.length; index < len; ++index) {

                                    redis.hgetall(vonline[index], function(err, vdet) {

                                        var _now = Math.floor(( new Date().getTime() ) / 1000);
                                        var _created_at = vdet.created_at;
                                        var seconds = _now - _created_at;

                                        console.log('Seconds for ' + vdet.user_id + ' : ' + seconds);

                                        if ( seconds > 60 ) {
                                            redis.srem('patterns:online:user:' + data.channel, 'online:user:' + data.channel + ':' + vdet.user_id);
                                            redis.del('online:user:' + data.channel + ':' + vdet.user_id);
                                            io.sockets.emit( "disc_user_" + data.channel, { user_id: vdet.user_id });
                                        }
                                    });
                                }

                            });
                            
                        } else {
                            io.sockets.emit( data.channel + ":online", { user: udet });

                            /** Check online users */
                            redis.smembers('patterns:online:user:' + data.channel, function(err, vonline){

                                for (var index = 0, len = vonline.length; index < len; ++index) {

                                    redis.hgetall(vonline[index], function(err, vdet) {

                                        var _now = Math.floor(( new Date().getTime() ) / 1000);
                                        var _created_at = vdet.created_at;
                                        var seconds = _now - _created_at;

                                        console.log('Seconds: ' + seconds);

                                        if ( seconds > 60 ) {
                                            redis.srem('patterns:online:user:' + data.channel, 'online:user:' + data.channel + ':' + vdet.user_id);
                                            redis.del('online:user:' + data.channel + ':' + vdet.user_id);
                                            io.sockets.emit( "disc_user_", { user_id: vdet.user_id });
                                        }
                                    });
                                }

                            });

                        }
                    });

                    redis.hmset($key, { user_id: data.user_id, created_at: created_at });
                    redis.sadd('patterns:online:user:' + data.channel, 'online:user:' + data.channel + ":" + data.user_id );

                } else {
                    // warnUser('Ooops! Ceva nu merge. Incercati sa va autentificati din nou!', data.id);
                }

            });

        });

    });






});

server.listen(process.env.SOCKETPORT);
console.log('Server start on ' + process.env.SOCKETPORT);
// to view all node connections use in terminal: ps aux | grep node
