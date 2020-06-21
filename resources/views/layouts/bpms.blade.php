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
        <title>@yield('titulo') - {{ nome_instancia() }}</title>
		<!-- Arquivos principais -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="corpo-bg">
		<div id="app" class="container-fluid">

			<header class="vertical-nav bg-white overflow-auto shadow-sm" id="sidebar">
				<div class="col-12 col-sm-12 col-md-12 bg-light py-4 px-3 mb-4">
					<div class="d-flex justify-content-center align-items-center">
						<a href="{{ route('raiz') }}">
							<img src="{{ logo_instancia() }}" class="img-fluid" width="60em">
						</a>
					</div>
				</div>
				<p class="text-gray font-weight-bold text-uppercase px-2 small mt-3">Principal</p>
				<ul class="nav flex-column mb-0">
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-chart-pie mr-3 text-primary"></i>
							Desempenho
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('request.list') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-paper-plane mr-3 text-primary"></i>
							Solicitação
					  	</a>
				  	</li>
				  	<li class="nav-item">
						<a href="{{ route('task.list') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-tasks mr-3 text-primary"></i>
							Tarefa
						</a>
					</li>
				</ul>
				

				@if(!Auth::user()->administrador)
				<p></p>
				<p class="text-gray font-weight-bold text-uppercase px-2 small mt-3">Administração</p>
				<ul class="nav flex-column mb-0">
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-business-time mr-3 text-primary"></i>
							Empresa
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-users mr-3 text-primary"></i>
							Usuários
					  	</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-chart-area mr-3 text-primary"></i>
							Gráficos
					  	</a>
				  	</li>
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-chess mr-3 text-primary"></i>
							Monitor de job
						</a>
					</li>
				</ul>
				@endif

				<p></p>
				<p class="text-gray font-weight-bold text-uppercase px-2 small mt-3">Usuário</p>
				<ul class="nav flex-column mb-0">
					<li class="nav-item">
						<a href="{{ route('graph.principal') }}" class="nav-link text-dark font-italic">
							<i class="fas fa-user-circle mr-3 text-primary"></i>
							Dados cadastrais
						</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('logout') }}" class="nav-link text-danger font-weight-bold"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<i class="fas fa-sign-out-alt mr-3 text-danger"></i>
							Sair
						</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
					</li>
				</ul>
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
		<script>
			window.$(document).ready(function(){
				$('#sidebarCollapse').on('click', function() {
					$('#sidebar, #content').toggleClass('active');
				});
			});
		</script>
    </body>
</html>