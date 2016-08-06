"use strict";
var Redis = require( 'ioredis' );    // ioredis library from https://github.com/luin/ioredis used to work with our redis database

// a small library from https://www.npmjs.com/package/dotenv
// it`s used to read our env variable because we do not want to expose them here
require('dotenv').config({path: 'E:/www/execom.ro/execom/.env'}); // do not forget to change that /home/execom/execom.env


// a redis instance; the enviroment variable comes from dotenv that took them from laravel .env file
var redis = new Redis({
    port: process.env.REDIS_PORT,          // Redis port
    host: process.env.REDIS_HOST,          // Redis host
    family: 4,                             // 4 (IPv4) or 6 (IPv6)
    password: process.env.REDIS_PASSWORD,
    db: 0
});



class User {

    
    constructor(data) {
        console.log('Constructor: ' + data.user_id);
        redis.get( "user:det:" + data.user_id ).then(function (result) {

            var udet = JSON.parse(result);

            console.log('user_det from Redis:' + JSON.stringify(udet));
            console.log('ID din redis: ' + udet.id);

            User.det = udet;

        });
    }

}
module.exports = User;