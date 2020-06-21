<?php

namespace App\Http\Controllers\task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListTask extends Controller
{
    public function index(Request $request) {
        return view('task.ListTask');
    } // public function index(Request $request) { ... }
}
