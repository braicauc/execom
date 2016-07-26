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
    
    
}
