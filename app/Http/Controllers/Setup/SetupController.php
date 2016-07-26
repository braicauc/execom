<?php

namespace App\Http\Controllers\Setup;

use App\Director;
use App\SEO;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{


    /**
     * Take the file and try to load the categories for our director
     * @return string OK
     */
    public function setupDirector() {


        $handle = fopen(APP_PATH . "/resources/assets/txt/director.txt", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $lines[trim($line)] = 1;
            }
            fclose($handle);
        } else {
            echo 'Nu am reusit sa deschid fisierul';
        }

        $this->addSubdomains($lines);

        return 'OK';

    }


    /**
     * @param $lines
     * @return bool
     */
    private function addSubdomains($lines) {

        if ( empty($lines) ) {
            return false;
        }

        foreach ($lines as $line => $val ) {

            $sub = new Director();
            if ( substr($line, 0, 1 ) === "-" ) {
                $sub->categorie = trim(strtolower(substr($line,1)));
                $sub->back_to = $cat_prim;
                $sub->slug = SEO::seo($sub->categorie);
            } else {
                $sub->categorie = trim(strtolower($line));
                $sub->back_to = 0;
                $sub->slug = SEO::seo($sub->categorie);
            }
            $sub->save();
            if ( $sub->back_to == 0 ) {
                $cat_prim = $sub->id;
            }
        }


        return true;

    }





}
