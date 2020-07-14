<?php

    namespace App\Http\Controllers\request;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Auth;
    use Carbon\Carbon;

    class IDRequest extends Controller
    {
        public function index(Request $request) {
            $chamado    =   DB::table('chamado')
                            ->where('chamado.id_chamado',intval($request->idChamado))
                            ->first();

            $usuacesso  =   DB::table('perfil_usuario')
                            ->join('perfil_acesso','perfil_acesso.id_perfil','perfil_usuario.id_perfil')
                            ->join('processo','processo.id_processo','perfil_acesso.id_processo')
                            ->where('perfil_usuario.id_usuario',Auth::user()->id)
                            ->select('processo.id_empresa')
                            ->distinct()
                            ->get();
            $validador  =   0;
            foreach($usuacesso as $acesso) {
                if($acesso->id_empresa = $chamado->id_empresa) {
                    $validador += 1;
                } // if($acesso->id_processo = $chamado->id_processo) { ... }
            } // foreach($usuacesso as $acesso) { ... }

            if($validador <= 0) return redirect()->route('raiz');

            if(is_null($chamado->id_chamado) || !isset($chamado->id_chamado)) return redirect()->route('raiz');

            $chamadoItem=   DB::table('chamado_item')
                            ->where('id_chamado',$chamado->id_chamado)
                            ->orderBy('chamado_item.id_chamado_item','asc')
                            ->get();

            $arquivo    =   DB::table('arquivo')
                            ->where('id_chamado',$chamado->id_chamado)
                            ->orderBy('arquivo.id_arquivo','asc')
                            ->get();

            $tarefas    =   DB::table('tarefa')
                            ->where('id_chamado',$chamado->id_chamado)
                            ->orderBy('tarefa.id_tarefa','asc')
                            ->get();
            
            $classeCor  =   is_null($chamado->data_conclusao) ? (Carbon::now()->gt(Carbon::parse($chamado->data_vencimento)) ? 'text-danger font-weight-bold' : 'text-success font-weight-bold') : 'text-primary font-weight-bold';

            return view('request.IDRequest',[
                'chamado'       =>  $chamado,
                'chamadoItem'   =>  $chamadoItem,
                'arquivos'      =>  $arquivo,
                'tarefas'       =>  $tarefas,
                'classeCor'     =>  $classeCor,
            ]);
        }
    }
