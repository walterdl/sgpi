<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
			Se ha enviado este correo con motivo de recuperar contraseña en el <b>Sistema de Gestión de Proyectos de Investigación</b> de la <b>Universidad Cooperativa de Colombia.</b>
			Si no ha generado este correo intencionalmente ignorar este correo.
		</p>
		<p style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
			Para recuperar la contraseña, completar el formulario del siguiente enlace:
		</p>
		<a href="{{ URL::to('password/reset', array($token)) }}">{{ URL::to('password/reset', array($token)) }}</a>
		<p style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
			Este enlace expirará en {{ Config::get('auth.reminder.expire', 60) }} minutos.
		</p>
		<div>
			<img style="height: 200px; width: 200px;" src="{{ url() }}/img/logo1.png" alt="Logo SGPI"/>
			<span style="font-size: 45px; margin-left: auto; margin-right: auto;"><b>SG</b><b style="color:gray;">P</b><b>I</b></span>
		</div>
	</body>
</html>
