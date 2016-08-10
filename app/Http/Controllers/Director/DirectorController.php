<?php

namespace App\Http\Controllers\Director;

use App\Director;
use App\RedisMessages;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DirectorController extends Controller
{


    public $director;


    /**
     * Display the category page if the slug is found
     * @param $slug
     * @return mixed
     */
    public function index($slug) {

        $this->setDirector($slug);
        
        if ( empty($this->director) ) {
             return view('errors.error', ['titlu' => 'No category', 'mesaj' => 'The requested category was not found!']);
        }

        $director = $this->director;

        $messages = $this->getMessages();

        $channel = $this->getRedisChannel($director->categorie);

        $user_ids = $this->getOnlineUsers();

        if ( !empty($user_ids) ) {
             $online_users = User::whereIn('id',$user_ids)->orderBy('username')->get();
        }

        return view('Foruchat.foruchat', compact('director','messages','channel','online_users'));
    }


    /**
     * Get online users
     * @return mixed
     */
    private function getOnlineUsers() {

        $redis = new RedisMessages($this->director->categorie);

        $users = $redis->getUsersFromChannel();

        return $users;
    }



    /**
     * Get Messages for current category
     * @return mixed
     */
    private function getMessages() {

        $redis = new RedisMessages($this->director->categorie);

        $messages = $redis->readMessagesFromChannel();

        return $redis->messagesToArray($messages);
    }


    /**
     * Set $this->director;
     * @param $slug
     */
    private function setDirector($slug) {
        $this->director = Director::directorBySlug($slug);
    }


    /**
     * Get the Redis channel
     * @param $channel
     * @return mixed
     */
    private function getRedisChannel($channel) {
        return (new RedisMessages($channel))->setRedisChannel();
    }
    
    
    




}
