<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{


    /**
     * Index page of the website
     * @return view
     */
    public function index() {


        return view('Index.index');
    }



}
