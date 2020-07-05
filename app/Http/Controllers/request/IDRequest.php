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

            return view('request.IDRequest',[
                'chamado'       =>  $chamado,
                'chamadoItem'   =>  $chamadoItem,
                'arquivos'      =>  $arquivo,
                'tarefas'       =>  $tarefas
            ]);
        }
    }
