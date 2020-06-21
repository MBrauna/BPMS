<?php

namespace App\Http\Controllers\graph;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiGraph extends Controller
{
    private $iteracoes  =   15;

    public function index(Request $request) {
        return view('graph.bi');
    } // public function index(Request $request) { ... }

    // Funções de 15 iterações
    private function coletaSaldo($periodo) {
        if(is_null($periodo) || $periodo <= 0) {
            $vPeriodo   =   1; // Percurso diário
        }
        else {
            $vPeriodo   =   $periodo;
        }
    } // private function coletaSaldo($periodo) { ... }
} // class BiGraph extends Controller { ... }
