<!DOCTYPE html>
<html lang="pt-br" class="no-js">
    <head>
        <!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="MBrauna - 1nesstech <michel.brauna@1nesstech.com.br>">
		<!-- Meta Description -->
		<meta name="description" content="BPMS - Sistema de controle de tarefas e BI">
		<!-- Meta Keyword -->
		<meta name="keywords" content="BPMS, BPMN, Tarefas, Tasks">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
        <title>Acesso - {{ nome_instancia() }}</title>
        <!-- Arquivos principais -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="corpo-bg">
		<div class="container-fluid">
            <section class="row align-items-center justify-content-center corpo-conteudo">
                <div class="col-6">
                    <div class="card shadow-sm">
                        <div class="card-body rounded-lg rounded-left login-card">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <center><img src="{{ logo_instancia() }}" class="img-fluid" width="100vw"></center>
                                </div>
                                <form method="POST" class="col-12 col-sm-12 col-md-12">
                                    {{ csrf_field() }}
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input id="email" type="email" placeholder="E-mail" class="form-control " name="email" value="" required autocomplete="email" autofocus>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-key"></i>
                                            </span>
                                        </div>
        
                                        <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="Senha">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">Acessar BPMS</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
		</div>

		<!-- Scripts Javascript -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </body>
</html>
