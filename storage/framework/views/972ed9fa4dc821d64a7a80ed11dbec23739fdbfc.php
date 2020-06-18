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
        <title><?php echo $__env->yieldContent('title'); ?> <?php echo e(config('app.name')); ?></title>
		<!-- Arquivos principais -->
		<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
		<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    </head>

    <body class="corpo-bg">
		<div id="app" class="container-fluid">


			<!-- Vertical navbar -->
<div class="vertical-nav bg-white" id="sidebar">
	<div class="py-4 px-3 mb-4 bg-light">
	  <div class="media d-flex align-items-center"><img src="https://res.cloudinary.com/mhmd/image/upload/v1556074849/avatar-1_tcnd60.png" alt="..." width="65" class="mr-3 rounded-circle img-thumbnail shadow-sm">
		<div class="media-body">
		  <h4 class="m-0">Jason Doe</h4>
		  <p class="font-weight-light text-muted mb-0">Web developer</p>
		</div>
	  </div>
	</div>
  
	<p class="text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">Main</p>
  
	<ul class="nav flex-column bg-white mb-0">
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic bg-light">
				  <i class="fa fa-th-large mr-3 text-primary fa-fw"></i>
				  Home
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-address-card mr-3 text-primary fa-fw"></i>
				  About
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-cubes mr-3 text-primary fa-fw"></i>
				  Services
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-picture-o mr-3 text-primary fa-fw"></i>
				  Gallery
			  </a>
	  </li>
	</ul>
  
	<p class="text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">Charts</p>
  
	<ul class="nav flex-column bg-white mb-0">
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-area-chart mr-3 text-primary fa-fw"></i>
				  Area charts
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-bar-chart mr-3 text-primary fa-fw"></i>
				  Bar charts
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-pie-chart mr-3 text-primary fa-fw"></i>
				  Pie charts
			  </a>
	  </li>
	  <li class="nav-item">
		<a href="#" class="nav-link text-dark font-italic">
				  <i class="fa fa-line-chart mr-3 text-primary fa-fw"></i>
				  Line charts
			  </a>
	  </li>
	</ul>
  </div>
  <!-- End vertical navbar -->
  
  
  <!-- Page content holder -->
  <div class="page-content p-5" id="content">
	<!-- Toggle button -->
	<button id="sidebarCollapse" type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4"><i class="fa fa-bars mr-2"></i><small class="text-uppercase font-weight-bold">Toggle</small></button>
  
	<!-- Demo content -->
	<h2 class="display-4 text-white">Bootstrap vertical nav</h2>
	<p class="lead text-white mb-0">Build a fixed sidebar using Bootstrap 4 vertical navigation and media objects.</p>
	<p class="lead text-white">Snippet by <a href="https://bootstrapious.com/snippets" class="text-white">
		  <u>Bootstrapious</u></a>
	</p>
	<div class="separator"></div>
	<div class="row text-white">
	  <div class="col-lg-7">
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor.
		</p>
		<div class="bg-white p-5 rounded my-5 shadow-sm">
		  <p class="lead font-italic mb-0 text-muted">"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
			irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
		</div>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor.
		</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor.
		</p>
	  </div>
	  <div class="col-lg-5">
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor.
		</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
		  dolor.
		</p>
	  </div>
	</div>
  
  </div>
  <!-- End demo content -->


		</div>

		
		<script type="text/javascript">
			$(function() {
				// Sidebar toggle behavior
				$('#sidebarCollapse').on('click', function() {
					$('#sidebar, #content').toggleClass('active');
				});
			});
		</script>
    </body>
</html><?php /**PATH /var/www/subdominio/developer/resources/views/layouts/bpms.blade.php ENDPATH**/ ?>