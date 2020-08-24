<?php

    namespace App\Http\Controllers\request;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class ObjetoTroca extends Controller
    {
        public function index(Request $request) {
            if(!usuario_lider_processo()) {
                return redirect()->route('raiz');
            } // if(usuario_lider_processo()) { ... }

            return view('request.ObjectRequest');
        } // public function index(Request $request) { .. }

        public function list(Request $request) {
            $lista  =   DB::table('entrada_solicitacao')
                        ->where('usr_cria',Auth::user()->id)
                        ->where('situacao',true)
                        ->orderBy('data_proximo_agendamento','asc')
                        ->get();

            return view('request.ListObject',[
                'list'  =>  $lista,
            ]);
        } // public function list(Request $request) { ... }
    }
