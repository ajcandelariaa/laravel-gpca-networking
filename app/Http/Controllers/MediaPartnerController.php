<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaPartnerController extends Controller
{
    public function getListOfMediaPartners() {
        return response()->json();
    }
}
