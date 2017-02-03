<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
		    @if($tipo_destinatario=='investigador')
		        Sr(a). investigador(a)  {{ $destinatario->nombres.' '.$destinatario->apellidos }},
		    @elseif($tipo_destinatario=='coordinador')
		        Sr(a). coodinador(a) de proyectos de investigación {{ $destinatario->nombres.' '.$destinatario->apellidos }},
		    @elseif($tipo_destinatario=='administrador')
		        Sr(a). administrador(a)  {{ $destinatario->nombres.' '.$destinatario->apellidos }},
		    @endif
		    <br />
		    <br />
			El <b>Sistema de Gestión de Proyectos de Investigación (SGPI)</b> de la <b>Universidad Cooperativa de Colombia</b> 
			le informa que se aproxima la fecha de mitad de proyecto ({{ $fecha_mitad_proyecto }}) del proyecto de investigación titulado <em>{{ $proyecto->nombre }}</em>.
			<br />
			<br />
			Este mensaje automático tiene como propósito recordarle al investigador principal, coordinación y jefatura de dicho proyecto la carga pendiente del informe de avance.
			Puede revisar la carga del informe a travéz del sistema en: Gestion de proyectos &gt; informe de avance, ingresando al sistema a travéz de su usuario: {{ $destinatario->username }}
		</p>
		<p style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif">
			Enlace a SGPI:
		</p>
		<a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a>
		<br />
		<br />
		<div>
			<img style="height: 200px; width: 200px;" src="{{ url() }}/img/logo1.png" alt="Logo SGPI"/>
			<span style="font-size: 45px; margin-left: auto; margin-right: auto;"><b>SG</b><b style="color:gray;">P</b><b>I</b></span>
		</div>
	</body>
</html>
