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
    }
