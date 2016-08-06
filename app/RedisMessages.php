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
        return Redis::lrange($this->strToRedisChannel(),0,$limit);
    }


    /**
     * Transform a string to a redis string Ex: cars for sale -> CarsForSale
     * @param $str
     * @return mixed
     */
    public function strToRedisChannel() {
        $this->redisChannel = 'ch:'.str_replace(" ","",ucwords($this->channel));
    }





}
