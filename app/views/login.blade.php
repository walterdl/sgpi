<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SGPI - Inciar sesión</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="/vendor/ionicons/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="adminlte/css/AdminLTE.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="vendor/iCheck/square/blue.css">
		<style>
		    .borde-derecho-negro{
		        border-right-style: solid; 
		        border-right-width: 1px;
		        border-right-color: #808080;
		    }
		    .img_logo_md{
		        height: 325px;
		        width: 325;
		    }
		    .img_logo_sm{
		        height: 270px; 
		        width: 270px;
		    }
		    #img_logo{
                -webkit-transition: all .2s ease-in-out; 
                transition: all .2s ease-in-out; 
		    }
		    #img_logo:hover{
		        -webkit-transform: scale(1.1);
		        transform: scale(1.1);
		    }
		</style>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="hold-transition login-page" style="background: #EEEEEE;">

		<div class="row">
		    <div class="col-xs-12 visible-sm-block visible-md-block visible-lg-block">
        		<br>
        		<br>
        		<br>		    
		    </div>
			<div class="col-sm-6" id="contenedor_logo">
				<div class="login-box">
					<div class="login-logo">
					    <div class="hidden-xs">
    						<br>
    						<br>
					    </div>
						<img src="/img/logo1.png" id="img_logo"/>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="login-box">
					<div class="login-logo">
						<span style=" font-size:30px;">Sistema de gestión de proyectos de investigación</span>
						<br />
						<span style=" font-size:45px;"><b>SG</b><b style="color:gray;">P</b><b>I</b></span>
					</div>
					<!-- /.login-logo -->
					<div class="login-box-body" style="border-left-style: double; border-right-style: double;">
						<!--style="border-right-width: 1px; "-->
						<!--border-left-style: double;-->
						@if (Session::has('login_errors'))
						<div class="alert alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
							</button>
							<strong>Credenciales incorrectos o usuario deshabilitado</strong>
						</div>
						@endif
						<p class="login-box-msg">Autenticarse para iniciar la sesión</p>
                		<?php $hay_notify_operacion_previa = Session::get('notify_operacion_previa') ?>
                		@if(isset($hay_notify_operacion_previa))
                		    @if(Session::get('notify_operacion_previa')=='success')
                		        <div class="alert alert-success" role="alert">{{ Session::get('mensaje_operacion_previa') }}</div>
                            @else
                                <div class="alert alert-danger" role="alert">{{ Session::get('mensaje_operacion_previa') }}</div>
                            @endif
                		@endif
						<form action="check" method="post">
							<div class="form-group has-feedback">
								<input type="text" name="username" class="form-control" placeholder="Usuario" required="true">
								<span class="glyphicon glyphicon-user form-control-feedback"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="password" name="password" class="form-control" placeholder="Contraseña" required="true">
								<span class="glyphicon glyphicon-lock form-control-feedback"></span>
							</div>
							<div class="row">
								<!--<div class="col-xs-8">-->
								<!--  <div class="checkbox icheck">-->
								<!--    <label>-->
								<!--      <input type="checkbox"> Remember Me-->
								<!--    </label>-->
								<!--  </div>-->
								<!--</div>-->
								<!-- /.col -->
								<div class="col-xs-12">
									<button type="submit" class="btn btn-primary btn-block btn-flat">Inciar sesión</button>
								</div>
								
								<div class="col-xs-12"><br>
									<a style="cursor: pointer" data-toggle="modal" data-target="#myModal">Recuperar contraseña</a>
								</div>
								
								<!-- /.col -->
							</div>
						</form>
						<!--<a href="#">I forgot my password</a><br>-->
						<!--<a href="register.html" class="text-center">Register a new membership</a>-->
					</div>
					<!-- /.login-box-body -->
				</div>
			</div>
		</div>
		
		<!-- Modal de recuperación de contraseña -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Recuperar Contraseña</h4>
					</div>
					<div class="modal-body">
						<form action="postRemind" method="POST">
							
							<div class="row">
								<div class="form-group">
									<div class="col-lg-8">
										<input class="form-control" type="email" name="email" placeholder="Ingrese su correo electronico">
									</div>
									<div class="col-lg-4">
										<input class="form-control btn btn-success" type="submit" value="Enviar recuperación">
									</div>
								</div>
							</div>
						</form>
						<br>
						<div class="alert alert-info" role="alert"><strong>Señor usuario, </strong>ingrese el correo electrónico asociado al usuario que desea recuperar y presione el botón "Enviar recuperación". Una vez diligenciado correctamente el formulario revisar el correo electronico y seguir los pasos</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- jQuery 2.2.0 -->
		<script src="vendor/jQuery/jQuery-2.2.0.min.js"></script>
		
		<!-- Bootstrap JS 3.3.6 -->
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- iCheck -->
		<script src="vendor/iCheck/icheck.min.js"></script>
		
		<script>
			function aplicar_estilos_responsivos() {
			    if(window.innerWidth >= 992){
			        if(!$('#contenedor_logo').hasClass('borde-derecho-negro'))
			            $('#contenedor_logo').addClass('borde-derecho-negro');
                    
                    if($('#img_logo').hasClass('img_logo_sm'))
                        $('#img_logo').removeClass('img_logo_sm');
                    
                    if(!$('#img_logo').hasClass('img_logo_md'))
                        $('#img_logo').addClass('img_logo_md');
			    }
			    else{
			        if($('#contenedor_logo').hasClass('borde-derecho-negro'))
			            $('#contenedor_logo').removeClass('borde-derecho-negro');
			            
	                if(!$('#img_logo').hasClass('img_logo_sm'))
	                    $('#img_logo').addClass('img_logo_sm');
	                    
	                if($('#img_logo').hasClass('img_logo_md'))
	                    $('#img_logo').removeClass('img_logo_md');
			    }
			};
			$(document).ready(function() {
			    $(window).bind('resize', function() {
			        aplicar_estilos_responsivos();
			    });
			    aplicar_estilos_responsivos();
			});
			$(function() {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
		</script>
	</body>
</html>