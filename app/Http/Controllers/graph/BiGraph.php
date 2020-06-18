<?php

namespace App\Http\Controllers\graph;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiGraph extends Controller
{
    public function index(Request $request) {
        return view('graph.bi');
    }
} // class BiGraph extends Controller { ... }
