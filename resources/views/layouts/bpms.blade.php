<!DOCTYPE html>
<html lang="pt-br" class="no-js">
    <head>
        <!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="/favico.ico">
		<!-- Author Meta -->
		<meta name="author" content="MBrauna - 1nesstech <michel.brauna@1nesstech.com.br>">
		<!-- Meta Description -->
		<meta name="description" content="BPMS - Sistema de controle de tarefas e BI">
		<!-- Meta Keyword -->
		<meta name="keywords" content="BPMS, BPMN, Tarefas, Tasks">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Token de sessão -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Site Title -->
        <title>@yield('titulo') - {{ nome_instancia() }}</title>
		<!-- Arquivos principais -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="corpo-bg">
		<input type="hidden" value="{{ Auth::user()->id }}" name="idUsuarioBPMS" id="idUsuarioBPMS">
		<div class="container-fluid" id="app">
			<!-- Dados para topo - Navegação -->
			<header id="header" class="fixed-top shadow">
				<nav class="navbar navbar-expand-lg navbar-light bg-white">
					<a class="navbar-brand" href="{{ route('raiz') }}">
						<img src="{{ logo_instancia() }}" class="d-inline-block align-top img-fluid" alt="" style="height: 1.5em">
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarText">
						<ul class="navbar-nav mr-auto font-weight-bold">
							<li class="nav-item">
								<a class="nav-link text-primary" href="{{ route('graph.principal') }}">Desempenho</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-primary" href="{{ route('request.list') }}">Solicitação</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-primary" href="{{ route('task.list') }}">Tarefas</a>
							</li>

							@if(Auth::user()->administrador)

							<li class="nav-item dropdown">
								<a class="nav-link text-primary dropdown-toggle" href="#" id="menuAdministrador" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Administrador
								</a>
								<div class="dropdown-menu" aria-labelledby="menuAdministrador">
									<a href="{{ route('admin.empresa.listar') }}" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-business-time mr-4"></i>
										<small>Empresa</small>
									</a>
									<div class="dropdown-divider"></div>
									<a href="{{ route('admin.usuario.listar') }}" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-users mr-4"></i>
										<small>Usuário</small>
									</a>
									<div class="dropdown-divider"></div>
									<a href="{{ route('admin.perfil.listar') }}" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-chess mr-4"></i>
										<small>Perfil</small>
									</a>
								</div>
							</li>

							@endif
						</ul>
						<div class="btn-group">
							<div class="nav-item dropdown">
								<a class="nav-link text-primary dropdown-toggle" href="#" id="menuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									{{ Auth::user()->name }}
								</a>
								<div class="dropdown-menu" aria-labelledby="menuUsuario">
									<a class="dropdown-item text-center" href="#">
										<small>{{ Auth::user()->email }}</small>
									</a>
									@if(Auth::user()->administrador)
									<a class="dropdown-item text-center" href="#">
										<small>Administrador</small>
									</a>
									@endif
									<div class="dropdown-divider"></div>
									<a class="dropdown-item text-white bg-danger d-flex justify-content-between" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i class="fas fa-sign-out-alt"></i>
										<small>Sair</small>
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
								</div>
							</div>
						</div>
					</div>
				</nav>
			</header>
			<!-- Dados para topo - Navegação -->

			<section class="corpo pt-5">
				<div class="pt-3">
					@yield('corpo')
				</div>
			</section>
		</div>
		<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
		@yield('scripts')
    </body>
</html>