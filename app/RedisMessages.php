<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class RedisMessages extends Model
{


    public $channel;

    public $redisChannel;

    public $channelMessages;


    /**
     * RedisMessages constructor.
     * @param null $channel
     */
    public function __construct($channel = null) {
        if ( !is_null($channel) ) {
             $this->channel = $channel;
             $this->setRedisChannel();
        } 
    }


    /**
     * Read messages for a given channel
     * @param int $limit
     * @return mixed
     */
    public function readMessagesFromChannel($limit = 50) {
        $this->channelMessages = Redis::lrange($this->setRedisChannel(),0,$limit);
        return $this;
    }


    /**
     * Take messages from redis channel (ex: ch:AdeziviMobila) and transform them into an array
     * @param $messages
     * @return array|null
     */
    public function messagesToArray($krsort = true) {

        if ( empty($this->channelMessages) ) {
             return null;
        }

        $arrs=[];

        foreach ($this->channelMessages as $u) {
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
        $users = Redis::smembers('patterns:online:user:ch:AdeziviMobila');
        return str_replace("online:user:ch:AdeziviMobila:", "", $users);
        
    }






}
