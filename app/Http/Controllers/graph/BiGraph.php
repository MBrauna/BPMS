<?php

namespace App\Http\Controllers\graph;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

class BiGraph extends Controller
{
    private $iteracoes  =   15;

    public function index(Request $request) {
        $acessos        =   [];
        foreach(usuario_acesso(Auth::user()->id) as $acesso) {
            array_push($acessos,$acesso->id_empresa);
        }

        $empresa        =   DB::table('empresa')
                            ->whereIn('empresa.id_empresa',$acessos)
                            ->orderBy('empresa.descricao','asc')
                            ->get();

        return view('graph.bi',[
            'empresas'  =>  $empresa,
        ]);
    } // public function index(Request $request) { ... }
} // class BiGraph extends Controller { ... }
