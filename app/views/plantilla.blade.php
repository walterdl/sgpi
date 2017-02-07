<!DOCTYPE html>
<html ng-app="sgpi_app">
	<head>
		<meta charset="UTF-8">
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		
		<title>SGPI</title>
		
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!--css precargador-->
		<link rel="stylesheet" href="/app/css/precargador.css">
		
		<!-- jQuery 2.2.0 -->
		<script src="/vendor/jQuery/jQuery-2.2.0.min.js"></script>
		<!--<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>-->
		
		<!--Esconde precargador-->
		<script type="text/javascript">
			$(window).load(function(){
				$(".se-pre-con").fadeOut("slow");
			});
		</script>
		
		<!--AngularJS 1.5.x-->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
		<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.min.js"></script>-->
		
		<!--AngularJS animate-->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-animate.js"></script>
		
		<!--AngularJS langguague-->
		<script src="https://code.angularjs.org/1.5.8/i18n/angular-locale_es-co.js"></script>
		
		<!--AngularJS touch-->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-touch.js"></script>
		
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		
		<!-- Magnific popup -->
		<link rel="stylesheet" href="/vendor/magnific-popup/magnific-popup.css">
		<script type="text/javascript" src="/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
		
		<!-- Theme style -->
		<link rel="stylesheet" href="/adminlte/css/AdminLTE.css">
		
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="/adminlte/css/skins/_all-skins.min.css">
		
		<!-- Bootstrap css 3.3.6 -->
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.css">
		
		<!-- Bootstrap js 3.3.6 -->
		<script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap's col with same height by flex property,
		source at: https://scotch.io/bar-talk/different-tricks-on-how-to-make-bootstrap-columns-all-the-same-height -->
		<link rel="stylesheet" href="/app/css/bootstrap-col-same-height-by-flex.css" type="text/css" />
		
		<!-- SlimScroll -->
		<script src="/vendor/slimScroll/jquery.slimscroll.min.js"></script>
		
		<!--Alertify. Ver referencia en: http://alertifyjs.com/ y en http://kensho.github.io/ng-alertify/-->
		<link rel="stylesheet" href="/vendor/alertify/alertify1/css/alertify.min.css">
		<link rel="stylesheet" href="/vendor/alertify/alertify1/css/themes/bootstrap.min.css">
		<script type="text/javascript" src="/vendor/alertify/alertify1/alertify.min.js"></script>
		
		<!--Alertify wrapper for angular-->
		<!--<link rel="stylesheet" href="{{ url() }}/vendor/alertify/ng-alertify.css">-->
		<script type="text/javascript" src="/vendor/alertify/ng-alertify.js"></script>
		
		<!--angular-bind-html-compile-->
		<script type="text/javascript" src="/vendor/angular-bind-html-compile/angular-bind-html-compile.min.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<link rel="stylesheet" href="/app/css/sgpi-styles.css">
		
		@yield('styles')
		@yield('pre_scripts')
		
		<!--Inicializacion de angular-->
		<script type="text/javascript" src="/app/js/general/sgpi_app.js"></script>
	</head>
	
	<body class="sidebar-mini skin-blue" ng-controller="base_controller">
		
		<!--div precargador-->
		<div class="se-pre-con"></div>
		
		<!-- Site wrapper -->
		<div class="wrapper">
			<header class="main-header">
				<!-- Logo -->
				<a href="/" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini">
						<img class="img-responsive" alt="Logo SGPI" src="/img/logo1.png">
					</span>
					<!-- logo for regular state and mobile devices -->
					<!--<span class="logo-lg" href=""><img class="img-responsive" alt="Responsive" src="{{url()}}/img/logoLetras3.png" /></span>-->
					<span class="logo-lg" style="height: inherit;">
						<img class="img-responsive" style="height: inherit; margin-left: auto; margin-right: auto;"
						alt="letras SGPI" src="/img/letrassgpi.png"/>
					</span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top">
					
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="pull-left info">
						<span style="padding-left:10px;">
							@if(Auth::user()->id_rol == 1)
								<!--<b style="font-size:35px;">Administrador</b>-->
								<b class="nombre_rol_admin">Administrador</b>
							@elseif(Auth::user()->id_rol == 2)
								<b style="font-size:35px;">Coordinador</b>
							@else
								<b class="nombre_rol_admin">Investigador</b>
							@endif
						</span>
					</div>
					
					<!-- Menú barra superior -->
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							{{-- User Account: style can be found in dropdown.less --}}
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<span class="hidden-xs">Perfil y sesión&nbsp;</span><img src="/img/logoUsuario3.png" class="user-image" alt="User Image"><br>
								</a>
								<ul class="dropdown-menu">
									<!-- Imagen de usuario -->
									<li class="user-header">
										@if(Auth::user()->persona->foto == "")
											@if(Auth::user()->persona->sexo == "m")
											<a href="/file/imagen_perfil/male.jpg" class="imagen_perfil_raiz">
												<img class="profile-user-img img-circle" src="/file/imagen_perfil/male.jpg" alt="User Image">
											</a>
											@else
											<a href="/file/imagen_perfil/female.jpg" class="imagen_perfil_raiz">
												<img class="profile-user-img img-circle" src="/file/imagen_perfil/female.jpg" alt="User Image">
											</a>
											@endif
										@else
											<a href="/file/imagen_perfil/{{Auth::user()->persona->foto}}" class="imagen_perfil_raiz">
												<img class="profile-user-img img-circle" src="/file/imagen_perfil/{{Auth::user()->persona->foto}}" alt="User Image">
											</a>
										@endif
										<p>
											{{ucwords(Auth::user()->persona->nombres)." ".ucwords(Auth::user()->persona->apellidos)}}
											<small>Miembro desde {{date("Y-m-d", strtotime(Auth::user()->created_at) )}}</small>
										</p>
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="/usuarios/propio_perfil" class="btn btn-default btn-flat">Perfil</a>
										</div>
										<div class="pull-right">
											<a href="/salir" class="btn btn-default btn-flat">Cerrar Sesión</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			
			<!-- Left side column. contains the sidebar -->
			<aside class="main-sidebar">
				{{-- sidebar: style can be found in sidebar.less --}}
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<!--<div class="pull-left image">-->
						@if(Auth::user()->persona->foto == "")
							@if(Auth::user()->persona->sexo == "m")
							<a href="/file/imagen_perfil/male.jpg" class="imagen_perfil_raiz pull-left image">
								<img src="/file/imagen_perfil/male.jpg" alt="User Image">
							</a>
							@else
							<a href="/file/imagen_perfil/female.jpg" class="imagen_perfil_raiz pull-left image">
								<img src="/file/imagen_perfil/female.jpg" alt="User Image">
							</a>
							@endif
						@else
							<a href="/file/imagen_perfil/{{Auth::user()->persona->foto}}" class="imagen_perfil_raiz pull-left image">
								<img src="/file/imagen_perfil/{{Auth::user()->persona->foto}}" alt="User Image">
							</a>
						@endif
						<div class="pull-left info">
							<p><?php
								$nombres=Auth::user()->persona->nombres;
								$nombre = preg_split( "/\s/ " , $nombres);
								
								$apellidos=Auth::user()->persona->apellidos;
								$apellido = preg_split( "/\s/ " , $apellidos);
								?>
							{{ ucwords($nombre[0].' '.$apellido[0]) }}</p>
						</div>
					</div>
					@if(Auth::user()->id_rol == 1)
						{{-- Menu de administrador --}}
						<ul class="sidebar-menu">
							<li class="treeview">
								<a href="">
									<i class="fa fa-group"></i> <span>Usuarios</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="/usuarios/registrar"><i class="fa fa-plus"></i> Registrar</a></li>
									<li><a href="/usuarios/listar"><i class="fa fa-list" aria-hidden="true"></i> Listar</a></li>
								</ul>
							</li>
							<li class="treeview">
								<a href="">
									<i class="fa fa-handshake-o" aria-hidden="true"></i> <span>Grupos</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="/grupos/registrar"><i class="fa fa-plus"></i> Registrar</a></li>
									<li><a href="/grupos/listar"><i class="fa fa-list"></i> Listar</a></li>
								</ul>
							</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-linode" aria-hidden="true"></i> <span>Líneas de investigación</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="/lineas_investigacion/listar"><i class="fa fa-list"></i> Listar / registrar</a></li>
								</ul>
							</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-briefcase" aria-hidden="true"></i> <span>Proyectos</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="wrap-text"><a href="/proyectos/listar"><i class="fa fa-list"></i> Gestion de proyectos</a></li>
								</ul>
							</li>
							<li class="treeview">
								<a href="#">
									<i class="fa fa-pie-chart" aria-hidden="true"></i> <span>Indicadores</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="wrap-text"><a href="/proyectos/indicadores"><i class="fa fa-list"></i> Indicadores de proyectos de inv.</a></li>
								</ul>
							</li>							
							<li class="treeview">
								<a href="#">
									<i class="fa fa-file-text" aria-hidden="true"></i> <span>Formatos documentos</span> <i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li class="wrap-text"><a href="/formatos_tipos_documentos/listar_editar"><i class="fa fa-list"></i> Gestionar formatos</a></li>
								</ul>
							</li>														
						</ul>
					@elseif(Auth::user()->id_rol == 2)
						{{--Menu de coordinador--}}
						<ul class="sidebar-menu">
							<li class="treeview active">
								<a href="#">
									<i class="fa fa-briefcase"></i> <span>Proyectos</span><i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="/proyectos/listar"><i class="fa fa-list"></i> Gestion de proyectos</a></li>
								</ul>
							</li>
						</ul>
					@else
						{{--Menu de investigador--}}
						<ul class="sidebar-menu">
							<li class="treeview active">
								<a href="#">
									<i class="fa fa-briefcase"></i> <span>Proyectos</span><i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">
									<li><a href="/proyectos/registrar"><i class="fa fa-plus"></i> Registrar</a></li>
									<li><a href="/proyectos/listar"><i class="fa fa-list"></i> Gestion de proyectos</a></li>
								</ul>
							</li>
						</ul>
					@endif
				</section>
			</aside>
			
			<!-- Contenido de la página -->
			<div class="content-wrapper">
				@yield('contenido')
			</div>
			
			<!--Footer-->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 1.0
				</div>
				<strong>2017</strong>. Universidad Cooperativa de Colombia
			</footer>
			
			<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
			
		</div>
		<!-- ./wrapper -->
		
		<!-- FastClick -->
		<script src="/vendor/fastclick/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="/adminlte/js/app.min.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="/adminlte/js/demo.js"></script>
		
		<script type="text/javascript">
		// window.history.forward(1);
		function deshabilitaRetroceso() {
			window.location.hash = "no-back-button";
			window.location.hash = "Again-No-back-button" //chrome
			window.onhashchange = function() {
				window.location.hash = "no-back-button";
			}
		}
		</script>
		
		@yield('post_scripts')
		
		{{--Presenta mensajes de alerta de la memoria flash de la operación previa--}}
		<?php $hay_notify_operacion_previa = Session::get('notify_operacion_previa') ?>
		@if(isset($hay_notify_operacion_previa))
			<script type="text/javascript">
				sgpi_app.value('notify_operacion_previa', {{ json_encode(Session::get("notify_operacion_previa")) }});
				sgpi_app.value('mensaje_operacion_previa', {{ json_encode(Session::get("mensaje_operacion_previa")) }});
			</script>
		@else
			<script type="text/javascript">
				sgpi_app.value('notify_operacion_previa', null);
				sgpi_app.value('mensaje_operacion_previa', null);
			</script>
		@endif
		@yield('post_sgpi_app_dependencies')
	</body>
</html>