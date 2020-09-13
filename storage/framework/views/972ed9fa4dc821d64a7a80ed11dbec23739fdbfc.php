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
		<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
		<!-- Site Title -->
        <title><?php echo $__env->yieldContent('titulo'); ?> - <?php echo e(nome_instancia()); ?></title>
		<!-- Arquivos principais -->
		<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

		<?php echo $__env->yieldContent('script_principal'); ?>
    </head>

    <body class="corpo-bg">
		<input type="hidden" value="<?php echo e(Auth::user()->id); ?>" name="idUsuarioBPMS" id="idUsuarioBPMS">
		<div class="container-fluid" id="app">
			<!-- Dados para topo - Navegação -->
			<header id="header" class="fixed-top shadow-sm">
				<nav class="navbar navbar-expand-lg navbar-light bg-white">
					<a class="navbar-brand" href="<?php echo e(route('raiz')); ?>">
						<img src="<?php echo e(logo_instancia()); ?>" class="d-inline-block align-top img-fluid" alt="" style="height: 1.5em">
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarText">
						<ul class="navbar-nav mr-auto font-weight-bold">
							<li class="nav-item">
								<a class="nav-link text-primary" href="<?php echo e(route('graph.principal')); ?>">Desempenho</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-primary" href="<?php echo e(route('request.list')); ?>">Solicitação</a>
							</li>
							<li class="nav-item">
								<a class="nav-link text-primary" href="<?php echo e(route('task.list')); ?>">Tarefa</a>
							</li>

							<li class="nav-item">
								<a class="nav-link text-primary" href="<?php echo e(route('file.list')); ?>">Compartilhar</a>
							</li>

							<li class="nav-item dropdown">
								<a class="nav-link text-primary dropdown-toggle" href="#" id="menuObjeto" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Objeto
								</a>
								<div class="dropdown-menu" aria-labelledby="menuObjeto">
									<?php if(usuario_lider_processo()): ?>
									<a href="<?php echo e(route('object.index')); ?>" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-file-signature mr-4"></i>
										<small>Registrar</small>
									</a>
									<?php endif; ?>
									<a href="<?php echo e(route('object.list')); ?>" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-list mr-4"></i>
										<small>Listar</small>
									</a>
								</div>
							</li>

							<?php if(Auth::user()->administrador): ?>
							<li class="nav-item dropdown">
								<a class="nav-link text-primary dropdown-toggle" href="#" id="menuAdministrador" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Administrador
								</a>
								<div class="dropdown-menu" aria-labelledby="menuAdministrador">
									<a href="<?php echo e(route('admin.empresa.listar')); ?>" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-business-time mr-4"></i>
										<small>Empresa</small>
									</a>
									<div class="dropdown-divider"></div>
									<a href="<?php echo e(route('admin.usuario.listar')); ?>" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-users mr-4"></i>
										<small>Usuário</small>
									</a>
									<div class="dropdown-divider"></div>
									<a href="<?php echo e(route('admin.perfil.listar')); ?>" class="dropdown-item text-primary font-italic font-weight-bolder d-flex justify-content-between">
										<i class="fas fa-chess mr-4"></i>
										<small>Perfil</small>
									</a>
								</div>
							</li>
							<?php endif; ?>

							<li class="nav-item">
								<a class="nav-link text-primary" target="_blank" href="//1wiki.1nesstech.com.br/Gestor_de_Nível_de_Serviço_(GNS)">Ajuda</a>
							</li>
						</ul>
						<div class="btn-group">
							<div class="nav-item dropdown">
								<a class="nav-link text-primary dropdown-toggle" href="#" id="menuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php echo e(Auth::user()->name); ?>

								</a>
								<div class="dropdown-menu" aria-labelledby="menuUsuario">
									<a class="dropdown-item text-center" href="#">
										<small><?php echo e(Auth::user()->email); ?></small>
									</a>
									<?php if(Auth::user()->administrador): ?>
									<a class="dropdown-item text-center" href="#">
										<small>Administrador</small>
									</a>
									<?php endif; ?>
									<div class="dropdown-divider"></div>
									<a type="button" class="dropdown-item text-white bg-primary d-flex justify-content-between" href="#" data-toggle="modal" data-target="#modalTrocaSenha">
										<i class="fas fa-key"></i>
										<small>Alterar senha</small>
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item text-white bg-danger d-flex justify-content-between" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i class="fas fa-sign-out-alt"></i>
										<small>Sair</small>
									</a>
									<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
								</div>
							</div>
						</div>
					</div>
				</nav>
			</header>
			<!-- Dados para topo - Navegação -->

			<section class="corpo pt-5">
				<div class="pt-3">
					<?php echo $__env->yieldContent('corpo'); ?>
				</div>
			</section>

			<div class="modal fade" id="modalTrocaSenha" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header bg-primary text-center text-white">
							<h5 class="modal-title" id="exampleModalCenterTitle">Alteração de senha:</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
								<i class="fas fa-times-circle  text-white"></i>
							</span>
							</button>
						</div>
						<div class="modal-body">
							<troca-senha url="<?php echo e(route('admin.password')); ?>" hash="<?php echo e(csrf_token()); ?>"></troca-senha>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			function noenter() {
				return !(window.event && window.event.keyCode == 13);
			}
		</script>
		<script type="text/javascript" src="<?php echo e(asset('js/app.js')); ?>"></script>
		<?php echo $__env->yieldContent('scripts'); ?>
    </body>
</html>
<?php /**PATH /var/www/subdominio/developer/resources/views/layouts/bpms.blade.php ENDPATH**/ ?>