<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class RedisMessages extends Model
{


    public $channel;

    public $redisChannel;


    /**
     * RedisMessages constructor.
     * @param null $channel
     */
    public function __construct($channel = null) {
        $this->channel = $channel;
    }


    /**
     * Read messages for a given channel
     * @param int $limit
     * @return mixed
     */
    public function readMessagesFromChannel($limit = 50) {
        return Redis::lrange($this->setRedisChannel(),0,$limit);
    }


    /**
     * Take messages from redis channel (ex: ch:AdeziviMobila) and transform them into an array
     * @param $messages
     * @return array|null
     */
    public function messagesToArray($messages, $krsort = true) {

        if ( empty($messages) ) {
             return null;
        }

        $arrs=[];

        foreach ($messages as $u) {
            $arrs[] = json_decode($u,true);
        }

        if ( $krsort == true ) {
             @krsort($arrs);
        }

        return $arrs;
    }


    /**
     * Transform a string to a redis string Ex: cars for sale -> CarsForSale
     * @param $str
     * @return mixed
     */
    public function setRedisChannel() {

        $this->redisChannel = 'ch:'.str_replace(" ","",ucwords($this->channel));

        return $this->redisChannel;
    }


    /**
     * Get online users from redis key 'patterns:online:user:' . $this->redisChannel
     * @return mixed
     */
    public function getUsersFromChannel() {
        $users = Redis::smembers('patterns:online:user:' . $this->redisChannel);
        if ( !empty($users ) ) {
            return str_replace("online:user:", "", $users);
        }
        return null;
    }






}
