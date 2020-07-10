<?php

    use Illuminate\Support\Facades\Route;

    # Determina que as rotas de autenticação não receberão registrador
    Auth::routes(['register' => true]);

    # Tudo dentro deste grupo estará sob a intervenção dos middlewares
    Route::middleware(['auth'])->group(function(){

        // Definirá qual será o endereço principal para o projeto
        Route::any('/',function(){
            return redirect()->route('graph.principal');
        })->name('raiz');




        // Para gráficos - Todas as rotas de analíticos e sintéticos
        Route::namespace('graph')->name('graph.')->group(function(){
            # [graph.principal]
            Route::any('/BI','BiGraph@index')->name('principal');
        }); // Route::namespace('Performance')->name('grafico.')->group(function(){ ... });
        



        // Para Tarefas - Todas as rotas de tarefas
        Route::namespace('task')->name('task.')->group(function(){
            # [task.list]
            Route::any('/tarefa','ListTask@index')->name('list');
        }); // Route::namespace('task')->name('task.')->group(function(){ ... });
        



        // Para Solicitações - Todas as rotas de solicitações de serviço
        Route::namespace('request')->name('request.')->group(function(){
            # [request.id]
            Route::any('/solicitacao/{idChamado}','IDRequest@index')->name('id');
            # [request.list]
            Route::any('/solicitacao','ListRequest@index')->name('list');
            # [request.index]
            Route::get('/abertura','CreateRequest@index')->name('index');
            Route::post('/abertura','CreateRequest@create')->name('create');
        }); // Route::namespace('request')->name('request.')->group(function(){ ... });
        



        Route::namespace('admin')->prefix('admin')->name('admin.')->group(function(){
            # [admin.user] - 
            Route::name('usuario.')->prefix('usuario')->group(function(){
                Route::any('/','Usuario@index')->name('listar');
                Route::post('/cadastrar','Usuario@cad')->name('cadastrar');
                Route::post('/editar','Usuario@edit')->name('editar');

                # [admin.usuario.perfil] -
                Route::name('perfil.')->prefix('perfil')->group(function(){
                    # [admin.usuario.perfil.listar] -
                    Route::any('/','Acesso@index')->name('listar');
                    # [admin.usuario.perfil.cadastrar] -
                    Route::post('/cadastrar','Acesso@cad')->name('cadastrar');
                    # [admin.usuario.perfil.remover] -
                    Route::post('/remover','Acesso@remove')->name('remover');
                });
            });

            # [admin.perfil] -
            Route::name('perfil.')->prefix('perfil')->group(function(){
                # [admin.perfil.listar]
                Route::any('/','Perfil@index')->name('listar');
                # [admin.perfil.cadastrar]
                Route::post('/cadastrar','Perfil@cad')->name('cadastrar');
                # [admin.perfil.cadastrar]
                Route::post('/editar','Perfil@edit')->name('editar');

                # [admin.perfil.acesso] -
                Route::name('acesso.')->prefix('acesso')->group(function(){
                    # [admin.perfil.acesso.listar] -
                    Route::any('/','Perfil@iddx')->name('listar');
                    # [admin.perfil.acesso.cadastrar] -
                    Route::any('/cadastrar','Perfil@caad')->name('cadastrar');
                    # [admin.perfil.acesso.listar] -
                    Route::any('/remover','Perfil@reem')->name('remover');
                });
            }); // Route::name('perfil.')->prefix('perfil')->group(function(){ ... }


            # [admin.empresa] - 
            Route::name('empresa.')->prefix('empresa')->group(function(){
                # [admin.empresa.listar]
                Route::any('/','Empresa@index')->name('listar');
                # [admin.empresa.inserir] - 
                Route::post('/inserir','Empresa@insert')->name('inserir');
                # [admin.empresa.editar]
                Route::post('/editar','Empresa@edit')->name('editar');
            });

            # [admin.processo] - 
            Route::name('processo.')->prefix('processo')->group(function(){
                # [admin.processo.listar] -
                Route::any('/','Processo@index')->name('listar');
                # [admin.processo.cadastrar] -
                Route::post('/cadastrar','Processo@cad')->name('cadastrar');
                # [admin.processo.editar] -
                Route::post('/editar','Processo@edit')->name('editar');
            });

            # [admin.situacao] -
            Route::name('situacao.')->prefix('situacao')->group(function(){
                # [admin.situacao.listar] -
                Route::any('/','Situacao@index')->name('listar');
                # [admin.situacao.cadastrar] -
                Route::post('/cadastrar','Situacao@cad')->name('cadastrar');
                # [admin.situacao.editar] -
                Route::post('/editar','Situacao@edit')->name('editar');
            }); // Route::name('situacao.')->prefix('situacao')->group(function(){ ... });

            # [admin.tipo] -
            Route::name('tipo.')->prefix('tipo')->group(function(){
                # [admin.tipo.listar] - 
                Route::any('/','TipoProcesso@index')->name('listar');
                # [admin.tipo.cadastrar] -
                Route::post('/cadastrar','TipoProcesso@cad')->name('cadastrar');
                # [admin.tipo.editar] -
                Route::post('/editar','TipoProcesso@edit')->name('editar');
            }); // Route::name('tipo.')->prefix('tipo')->group(function(){ ... });

            # [admin.questao] -
            Route::name('questao.')->prefix('questao')->group(function(){
                # [admin.questao.listar] -
                Route::any('/','Questao@index')->name('listar');
                # [admin.questao.cadastrar] -
                Route::post('/cadastrar','Questao@cad')->name('cadastrar');
                # [admin.questao.editar] -
                Route::post('/editar','Questao@edit')->name('editar');
            }); // Route::name('questao.')->prefix('questao')->group(function(){ ... });

            # [admin.arvore]
            Route::name('arvore.')->prefix('arvore')->group(function(){
                # [admin.arvore.listar]
                Route::any('/','Fluxo@index')->name('listar');
                # [admin.arvore.cadastrar]
                Route::post('/cadastrar','Fluxo@cad')->name('cadastrar');
                # [admin.arvore.remover]
                Route::post('/remover','Fluxo@remove')->name('remover');
            }); // Route::name('arvore.')->prefix('arvore')->group(function(){ ...});
        }); // Route::namespace('admin')->name('admin.')->group(function(){ ... });
    }); // Route::middleware(['auth'])->group(function(){ ... });

