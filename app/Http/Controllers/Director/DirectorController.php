<?php

namespace App\Http\Controllers\Director;

use App\Director;
use App\RedisMessages;
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

        return view('Foruchat.foruchat', compact('director','messages'));
    }


    /**
     * Get Messages for current category
     * @return mixed
     */
    private function getMessages() {
        return (new RedisMessages($this->director->categorie))->readMessagesFromChannel();
    }


    /**
     * Set $this->director;
     * @param $slug
     */
    private function setDirector($slug) {
        $this->director = Director::directorBySlug($slug);
    }
    
    
    




}
