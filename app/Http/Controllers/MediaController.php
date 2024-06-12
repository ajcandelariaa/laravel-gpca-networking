<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function mediaView(){

        return view('admin.home.media', [
            "pageTitle" => "Media",
        ]);
    }
}
