<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{


    /**
     * Replace Diacritis from string
     * @param $s
     * @return string
     */
    public static function replaceDiacritics($s) {
        return iconv("UTF-8", "ASCII//TRANSLIT", $s);
    }

    /**
     * Primary SEO function that makes slugs ( Cars for sale => 'cars-for-sale' )
     * @param $s
     * @param null $cases
     * @return mixed
     */
    public static function seo($s,$cases = null) {
        $s = SEO::replaceDiacritics($s);
        if( is_null($cases) ) {
            $s = strtolower($s);
        }
        return preg_replace('/\-+/','-',preg_replace('/\s+/','-',preg_replace('/\s+/',' ',trim(preg_replace("/[^[:space:]a-zA-Z0-9\-]/", "", $s)))));
    }


    /**
     * Takes an Director instance and returns slug and link
     * @param Director $director
     * @return object
     */
    public static function linkDirector(Director $director) {
        $slugs = (object)array();
        $slugs->slug = SEO::seo($director->categorie);
        $slugs->link = route('foruchat',$slugs->slug) ;
        return $slugs;
    }


    /**
     * Simple slug function
     * @param $str
     * @return mixed
     */
    public static function simpleSlug($s) {
        return preg_replace('/\-+/','-',preg_replace('/\s+/','-',preg_replace('/\s+/',' ',trim(preg_replace("/[^[:space:]a-zA-Z0-9\-]/", "", strtolower($s))))));
    }




}
