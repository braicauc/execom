<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{

    
    public $director;


    /**
     * Set categori
     * @param Director $director
     */
    public function setDirector(Director $director) {
        $this->director = $director;
    }


    /**
     * A list with subcats
     * @param Director $director
     * @return mixed
     */
    public static function subcategorii(Director $director) {
        return Director::where('back_to',$director->id)->get();
    }


    /**
     * Return a director category by slug
     * @param $slug
     * @return mixed
     */
    public static function directorBySlug($slug) {
        return Director::where('slug', $slug)->first();
    }
    
    
}
