<?php

namespace App\Http\Controllers\request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListRequest extends Controller
{
    public function index(Request $request) {
        return view('request.ListRequest');
    } // public function index(Request $request) { ... }
}
