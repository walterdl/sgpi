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
        .foto_perfil{
            height: 120px;
            width: 120px;
        }
        .foto_perfil > img{
            height: 100%;
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
                    <!--<ul class="nav pull-right navbar-nav">-->
                    <!--    <li style="margin-right: 5px;">-->
                    <!--        <a href="/usuarios/registrar" class="btn btn-primary" role="button">Registrar nuevo usuario</a>-->
                    <!--    </li>-->
                    <!--</ul>-->
                    <h1>Usuarios</h1>
                </section>
            </div>
            <br />
            <div class="box-body" id="contenedor_tabla_usuarios" >
                <a href="/usuarios/registrar" class="btn btn-primary" role="button">Registrar nuevo usuario</a>
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
                            <tr ng-repeat="usuario in data.usuarios">
                                <td>{$ usuario.nombres $}</td>
                                <td>{$ usuario.identificacion $}</td>
                                <td>{$ usuario.nombre_rol $}</td>
                                <td>{$ usuario.nombre_estado $}</td>
                                <td>
                                    <button ng-show="visibilidad.btn_cambiarEstado" type="button" class="btn btn-default" ng-click="btn_cambiar_estado(usuario)">
                                        <a ng-show="usuario.id_estado==1" ><i class="fa fa-ban" aria-hidden="true"></i></a>
                                        <a ng-show="usuario.id_estado==2"><i class="fa fa-check" aria-hidden="true"></i></a>
                                        <!--ng-click="btn_cambiar_estado(usuario,2)"-->
                                        <!--ng-click="btn_cambiar_estado(usuario,1)"-->
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
        
        {{--Más información del usuario--}}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Más información del usuario</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body" id="mas_info_boxbody">
                <h4 class="text-center" ng-show="data.mas_info_usuario==null">No se ha solicitado más información de un usuario, seleccionar botón más información</h4>
                <div class="row is-flex sin-margen" ng-show="data.mas_info_usuario!=null">
                    <div class="col-xs-12 text-left">
                        <a ng-show="data.mas_info_usuario.foto==null && data.mas_info_usuario.sexo=='m'" href="/file/imagen_perfil/male.jpg" class="foto_perfil">
                            <img class="img-responsive img-thumbnail" src="/file/imagen_perfil/male.jpg" alt="Foto de perfil del usuario">
                        </a>
                        <a ng-show="data.mas_info_usuario.foto==null && data.mas_info_usuario.sexo=='f'" href="/file/imagen_perfil/female.jpg" class="foto_perfil">
                            <img class="img-responsive img-thumbnail" src="/file/imagen_perfil/female.jpg" alt="Foto de perfil del usuario">
                        </a>                        
                        <a ng-show="data.mas_info_usuario.foto!=null" href="/file/imagen_perfil/{$ data.mas_info_usuario.foto $}" class="foto_perfil">
                            <img class="img-responsive img-thumbnail" src="/file/imagen_perfil/{$ data.mas_info_usuario.foto $}" alt="Foto de perfil del usuario">
                        </a>                         
                        <br />
                        <span><strong>{$ data.mas_info_usuario.nombres + ' ' + data.mas_info_usuario.apellidos$}</strong> - {$ data.mas_info_usuario.acronimo_tipo_id + '.' + data.mas_info_usuario.identificacion $}</span>
                        <br />
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Tipo de usuario</label>
                        <p>{$ data.mas_info_usuario.nombre_rol $}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Nombre de usuario</label>
                        <p>{$ data.mas_info_usuario.username $}</p>
                    </div>                                  
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Sexo</label>
                        <p ng-if="data.mas_info_usuario.sexo=='m'">Hombre</p>
                        <p ng-if="data.mas_info_usuario.sexo=='f'">Mujer</p>
                    </div>                                                   
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Edad</label>
                        <p>{$ data.mas_info_usuario.edad $}</p>
                    </div>                              
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Email</label>
                        <p>{$ data.mas_info_usuario.email $}</p>
                    </div>                               
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <label>Formación</label>
                        <p>{$ data.mas_info_usuario.formacion $}</p>
                    </div>                                   
                    <div class="col-xs-12 col-sm-6 col-md-3" ng-if="data.mas_info_usuario.id_rol==2 || data.mas_info_usuario.id_rol==3">
                        <label>Grupo de investigación</label>
                        <p>{$ data.mas_info_usuario.nombre_grupo_inv $}</p>
                    </div>                  
                    <div class="col-xs-12 col-sm-6 col-md-3" ng-if="data.mas_info_usuario.id_rol==2 || data.mas_info_usuario.id_rol==3">
                        <label>Grupo de investigación</label>
                        <p>{$ data.mas_info_usuario.nombre_facultad $}</p>
                    </div>                                      
                    <div class="col-xs-12 col-sm-6 col-md-3" ng-if="data.mas_info_usuario.id_rol==2 || data.mas_info_usuario.id_rol==3">
                        <label>Facultad / dependencia</label>
                        <p>{$ data.mas_info_usuario.nombre_facultad $}</p>
                    </div>                                                
                    <div class="col-xs-12 col-sm-6 col-md-3" ng-if="data.mas_info_usuario.id_rol==2 || data.mas_info_usuario.id_rol==3">
                        <label>Sede</label>
                        <p>{$ data.mas_info_usuario.nombre_sede $}</p>
                    </div>                                                                    
                </div>
            </div>
            <div class="overlay" ng-show="visibilidad.show_velo_mas_info_usuario">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_mas_info_usuario">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>                                        
            </div>
        </div>{{--Más información del usuario--}}
</section>

@stop <!--Stop section 'contenido'-->

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
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