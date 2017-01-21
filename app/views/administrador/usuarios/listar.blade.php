@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        .white-background{
            background-color: rgba(255,255,255,1) !important;
        }
        .custom-info-box{
            box-shadow: 0 1px 3px rgba(0,0,0,0.3)
        }
        .sin-margen{
            margin:0;
        }
    </style>
@stop <!--Stop section 'styles'-->

@section('pre_scripts')
    @if(isset($pre_scripts))
        @foreach($pre_scripts as $script) 
            <script type="text/javascript" src="/{{ $script }}"></script>
        @endforeach
    @endif
@stop <!--Stop section 'pre_scripts'-->

@section('contenido')

    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i><b></b></a></li> <i class="fa fa-chevron-right" aria-hidden="true"></i> <li><a href="/usuarios"><b>Usuarios</b></a></li>
        </ol><br>
    </section>
    
    <section class="content" ng-controller="listar_usuarios_controller" ng-cloak>
        
        <div class="box">
            <div class="box-header with-border">
                <section class="content-header">
                    <ul class="nav pull-right navbar-nav">
                        <li style="margin-right: 5px;">
                            <a href="/usuarios/registrar" class="btn btn-primary" role="button">Registrar nuevo usuario</a>
                        </li>
                    </ul>
                    <h1>Usuarios</h1>
                </section>
            </div>
            <div class="box-body" id="contenedor_tabla_usuarios" >
                <div class="table-responsive custom-scrollbar">
                    <table datatable="ng" dt-options="dtOptions" class="table table-hover table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Identificación</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Habilitar / deshabilitar</th>
                                <th>Ver más</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--ng-show="visibilidad.mostrar_usuarios"-->
                            <tr  ng-repeat="usuario in data.usuarios">
                                <td>{$ usuario.nombres $}</td>
                                <td>{$ usuario.identificacion $}</td>
                                <td>{$ usuario.nombre_rol $}</td>
                                <td>{$ usuario.nombre_estado $}</td>
                                <td>
                                    <button ng-show="visibilidad.btn_cambiarEstado" type="button" class="btn btn-default">
                                            <a ng-show="usuario.id_estado == 1" ng-click="btn_cambiar_estado(usuario,2)" ><i class="fa fa-ban" aria-hidden="true"></i></a>
                                            <a ng-show="usuario.id_estado == 2" ng-click="btn_cambiar_estado(usuario,1)"><i class="fa fa-check" aria-hidden="true"></i></a>
                                    </button>
                                    
                                    <div class="overlay" ng-show="visibilidad.show_cargando_cambiarEstado">
                                        <i class="fa fa-circle-o-notch fa-spin"></i>
                                    </div>
                                    
                                </td>
                                <td><button type="button" class="btn btn-default" ng-click="btn_mas_info_usuario_click(usuario.id_usuario)"><i class="fa fa-info-circle" aria-hidden="true"></i></button></td>
                                <td><a href="/usuarios/editar/{$ usuario.id_usuario $}" type="button" class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                            </tr>
                            
 
                        </tbody>
                    </table>
                </div>

                <hr />
                
                <!--para ver cargando-->
                <div class="overlay" ng-show="visibilidad.show_tabla_usuario">
                    <i class="fa fa-circle-o-notch fa-spin"></i>
                </div>
          
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Más información del usuario</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body" id="mas_info_boxbody">
                <div class="row is-flex sin-margen">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="info-box custom-info-box">
                            <span class="info-box-icon">
                                <img ng-if="data.info_usuario.foto==null && data.info_usuario.sexo=='m'" class="profile-user-img img-responsive img-circle" src="{{url()}}/adminlte/img/male.jpg" class="img-circle" alt="User Image">
                                <img ng-if="data.info_usuario.foto==null && data.info_usuario.sexo=='f'" class="profile-user-img img-responsive img-circle" src="{{url()}}/adminlte/img/female.jpg" class="img-circle" alt="User Image">
                                <img ng-if="data.info_usuario.foto!=null" class="profile-user-img img-responsive img-circle" src="{{url()}}/adminlte/img/male.jpg" class="img-circle" alt="User Image">
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">{$ data.info_usuario.nombres $}</span>
                                <span class="info-box-text">{$ data.info_usuario.apellidos $}</span>
                                <span class="info-box-text">{$ data.info_usuario.acronimo_tipo_id $} <strong>{$ data.info_usuario.identificacion $}</strong></span>
                                <hr />
                                <p class="text-left">Edad: <strong>{$ data.info_usuario.edad $}</strong></p>
                            	<p class="text-left"><span ng-if="data.info_usuario.sexo=='m'">Hombre</span><span ng-if="data.info_usuario.sexo=='f'">Mujer</span></strong></p>
                            	<p class="text-left">Formación: <strong>{$ data.info_usuario.formacion $}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="info-box custom-info-box" style="height:100%;">
                            <div class="info-box-content sin-margen">
                            	<p class="text-left">Usuario: <strong>{$ data.info_usuario.username $}</strong></p>
                            	<p class="text-left">Estado del usuario: <strong>{$ data.info_usuario.nombre_estado $}</strong></p>
                            	<p class="text-left">Tipo de usuario: <strong>{$ data.info_usuario.nombre_rol $}</strong></p>
                            	<p class="text-left">Email: <span ng-if="data.info_usuario.email==null||data.info_usuario.email==''">Sin registro</span><span ng-if="data.info_usuario.email!=null&&data.info_usuario.email!=''"></span><strong>{$ data.info_usuario.email $}</strong></p>
                            	<p class="text-left">Miembro desde: <strong>{$ data.info_usuario.created_at $}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="visibilidad.show_col_detalles_usuario">
                        <div class="info-box custom-info-box" style="height:100%;">
                            <div class="info-box-content sin-margen" ng-bind-html="data.detalles_usuario">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overlay" ng-show="visibilidad.show_cargando_mas_info_usuario">
                <i class="fa fa-circle-o-notch fa-spin"></i>
            </div>
            <div class="overlay" ng-show="visibilidad.show_operacion_mas_info_usuario" ng-class="{'white-background': !data.btn_mas_info_seleccionado}">
                <h3 class="text-center">{$ data.msj_operacion_mas_info_usuario $}</h3>
            </div>
        </div>
</section>

@stop <!--Stop section 'contenido'-->

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
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
@stop <!--Stop section 'scripts'-->

@if(isset($angular_sgpi_app_extra_dependencies))
    @section('post_sgpi_app_dependencies')
        <script>
            @foreach($angular_sgpi_app_extra_dependencies as $dependencie) 
                sgpi_app.requires.push('{{ $dependencie }}');
            @endforeach
        </script>
    @stop
@endif <!--Stop section 'post_sgpi_app_dependencies'-->