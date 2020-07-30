<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Hash;
    use Carbon\Carbon;

    class Senha extends Controller
    {
        public function alterPassword(Request $request){
            try {
                $senha =    $request->input('novaSenha');
                if(is_null($senha)) return back();

                DB::beginTransaction();
                DB::table('users')
                ->where('id',Auth::user()->id)
                ->update([
                    'password'  =>  Hash::make(trim($senha)),
                ]);
                DB::commit();

                return back();
            } // try { ... }
            catch(Exception $erro) {
                return back();
            } // catch(Exception $erro) { ... }
        } // public function alterPassword(Request $request){ ... }
    }
