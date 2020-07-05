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
		<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/css/fileinput.css" rel="stylesheet">
    </head>

    <body class="corpo-bg">
		<input type="hidden" value="{{ Auth::user()->id }}" name="idUsuarioBPMS" id="idUsuarioBPMS">
		<div id="app" class="container-fluid">
			<header class="vertical-nav bg-primary overflow-auto shadow-sm" id="sidebar">
				<div class="col-12 col-sm-12 col-md-12 bg-white py-4 px-3 mb-1">
					<div class="d-flex justify-content-center align-items-center">
						<a href="{{ route('raiz') }}">
							<img src="{{ logo_instancia() }}" class="img-fluid" width="60em">
						</a>
					</div>
				</div>
				<h6 class="text-white text-center">
					{{ Auth::user()->name }}
				</h6>
				<a href="{{ route('logout') }}" class="btn btn-light text-primary btn-sm text-center font-weight-bold col-12 col-sm-12 col-md-12 col-lg-12"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					<i class="fas fa-sign-out-alt mr-3"></i>
					Sair
				</a>
				<p></p>
				<p class="text-white font-weight-bold px-2">Principal</p>
				<ul class="nav flex-column mb-0">
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-chart-pie mr-4 text-white"></i>
							Desempenho
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('request.list') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-paper-plane mr-4 text-white"></i>
							Solicitação
					  	</a>
				  	</li>
				  	<li class="nav-item">
						<a href="{{ route('task.list') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-tasks mr-4 text-white"></i>
							Tarefa
						</a>
					</li>
				</ul>
				

				@if(Auth::user()->administrador)
				<p></p>
				<p class="text-white font-weight-bold px-2">Administração</p>
				<ul class="nav flex-column mb-0">
					<li class="nav-item">
						<a href="{{ route('admin.empresa.listar') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-business-time mr-4 text-white"></i>
							Empresa
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.usuario.listar') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-users mr-4 text-white"></i>
							Usuários
					  	</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('admin.perfil.listar') }}" class="nav-link text-white font-italic font-weight-bolder">
							<i class="fas fa-chess mr-4 text-white"></i>
							Perfil
						</a>
					</li>
				</ul>
				@endif

				

				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
			</header>
			<!-- End vertical navbar -->

			<!-- Page content holder -->
			<div class="page-content" id="content">
				<!-- Toggle button -->
				<button id="sidebarCollapse" type="button" class="btn btn-light btn-sm bg-white rounded shadow-sm mt-3 sticky-top">
					<i class="fa fa-bars"></i>
					<small class="text-uppercase font-weight-bold">Menu</small>
				</button>
				<div class="container-fluid">
					@yield('corpo')
				</div>
			</div>
		</div>
		<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.1/js/fileinput.js"></script>
		<script>
			window.$(document).ready(function(){
				$('#sidebarCollapse').on('click', function() {
					$('#sidebar, #content').toggleClass('active');
				});
			});
		</script>
		@yield('scripts')
    </body>
</html>