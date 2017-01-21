@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        .overlay-2{
            z-index: 50;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 3px;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;        
        }
        div.tab-pane{
            position:relative !important;
        }
        .contenedor-fechas-productos > div{
            border: .5px solid lightgray;
        }
        .icono-calendario-producto{
            background-color: #F5F5F5;
        }
        .row-fechas-producto{
            border: 1px solid #F5F5F5;
            margin-left: 0;
            margin-right: 0;
        }
        .panel-body-producto{
            padding: 0;
        }
        .tabla-documentos-producto{
            margin-bottom: 0;
        }
        .no-wrap{
            white-space: nowrap;
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
    
    {{--Presenta alertifys con mensaje flash de una operación previa--}}
    <?php $hay_notify_operacion_previa = Session::get('notify_operacion_previa') ?>
    @if(isset($hay_notify_operacion_previa))
        <span ng-hide="true" ng-init='notify_operacion_previa={{ json_encode(Session::get("notify_operacion_previa")) }}'></span>
        <span ng-hide="true" ng-init='mensaje_operacion_previa={{ json_encode(Session::get("mensaje_operacion_previa")) }}'></span>
    @endif        
    
    {{--migajas de pan--}}
    <section class="content-header">
        <ol class="breadcrumb">
            <li>
                <a href="/"><i class="fa fa-home" style="font-size:18px;"></i></a>
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </li> 
            <li>
                <a href="#"><b>Proyectos</b></a>
            </li>
        </ol>
        <br />
    </section>
    
    {{--contenido--}}
    <section class="content" ng-cloak ng-controller="listar_proyectos_controller">
        
        <!--tabla proyectos-->
        <div class="box">
            <div class="box-header with-border">
                <h3>Gestión de proyectos</h3>
            </div>
            <div class="box-body">

               {{--Tabs--}}
                <ul class="nav nav-tabs" id="tabs">
                    <li id="tab_info_general" role="presentation" class="active">
                        <a href="#contenido_tab_proyectos" disabled>Proyectos</a>
                    </li>
                    <li id="tab_productos" role="presentation" 
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_productos" disabled>Productos del proyecto</a>
                    </li>
                    <li id="tab_gastos" role="presentation" 
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_gastos" disabled>Gastos del proyecto</a>
                    </li>
                </ul> {{--Tabs--}}               
                
                {{--Contenedor del contenido de cada tab--}}
                <div class="tab-content">
                    
                    {{--Contenito tab selección de proyectos--}}
                    <div id="contenido_tab_proyectos" class="tab-pane fade active in">
                        <br />
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <a href="/proyectos/registrar" class="btn btn-primary btn-block">Registrar nuevo proyecto</a>
                            </div>
                        </div>
                        <br />
                        <div class="table-responsive custom-scrollbar">
                            <table datatable="ng" dt-options="dtOptions" class="table table-hover table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Código FMI</th>
                                        <th class="no-wrap">Subcentro de costo</th>
                                        <th class="no-wrap">Nombre de proyecto</th>
                                        <th class="no-wrap">Grupo de investigación ejecutor</th>
                                        <th>Duración (meses)</th>
                                        <th class="no-wrap">Estado de progreso</th>
                                        <th>Editar proyecto</th>
                                        <th class="no-wrap">Cargar documentos de productos</th>
                                        <th class="no-wrap">Desembolsos de gastos</th>
                                        <th class="no-wrap">Informe de avance</th>
                                        <th>Más información</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="proyecto in data.proyectos">
                                        <td>{$ proyecto.codigo_fmi $}</td>
                                        <td>{$ proyecto.subcentro_costo $}</td>
                                        <td>{$ proyecto.nombre_proyecto $}</td>
                                        <td>{$ proyecto.nombre_grupo_inv_principal $}</td>
                                        <td>{$ proyecto.duracion_meses $}</td>
                                        <td>Coming soon. Se mostrará: en desarrollo, en posterga o finalizado</td>
                                        
                                        <td>
                                            <div class="dropup">
                                              <button class="fa fa-pencil-square-o btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                              </button>
                                              
                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <li><a href="/proyectos/editar/1/{$ proyecto.id$}">Inf. general</a></li>
                                                <li><a href="/proyectos/editar/2/{$ proyecto.id$}">Participantes</a></li>
                                                <li><a href="/proyectos/editar/3/{$ proyecto.id$}">Productos</a></li>
                                                <li><a href="/proyectos/editar/4/{$ proyecto.id$}">Gastos</a></li>
                                                <li><a href="/proyectos/editar/5/{$ proyecto.id$}">Adjuntos</a></li>
                                              </ul>
                                            </div>
                                            
                                            <!--<button type="button" class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>-->
                                        </td>
                                        
                                        <td><button type="button" class="btn btn-default" ng-click="productos(proyecto.id)"><i class="fa fa-shopping-bag" aria-hidden="true"></i></button></td>
                                        <td><button type="button" class="btn btn-default" ng-click="gastos(proyecto.id)"><i class="fa fa-money" aria-hidden="true"></i></button></td>
                                        <td><button type="button" class="btn btn-default"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></button></td> 
                                        <td><button type="button" class="btn btn-default"><i class="fa fa-info-circle" aria-hidden="true"></i></button></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    {{--Contenito tab productos--}}
                    <div id="contenido_tab_productos" class="tab-pane fade" ng-controller="productos_controller">
                        <br />
                        <div class="container-sgpi">
                            {{--btn regreso a tab de seleccion de proyecto--}}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-default btn-block" ng-click="volver_a_proyectos()">
                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Proyectos
                                    </button>
                                </div>
                            </div>
                            <hr />                            
                            {{--Acordiones de productos--}}
                            <div ng-repeat="producto in productos" class="nga-fast nga-stagger-fast nga-fade">
                                <div class="panel panel-default">
                                	<div class="panel-heading" role="tab">
                                		<h4 class="panel-title">
                                    		<a role="button" data-toggle="collapse" href="#producto_{$ $index $}" aria-expanded="true" aria-controls="producto_{$ $index $}" ng-click="abre_cierra_acordion('producto_' + $index)">
                                    		    <span class="glyphicon glyphicon-minus" ng-if="$index==0"></span>
                                    		    <span class="glyphicon glyphicon-plus" ng-if="$index!=0"></span>
                                    			<p><strong>{$ producto.nombre $}</strong></p>
                                    			<p><em>{$ producto.nombre_tipo_producto_especifico $}</em></p>
                                    			<p><em>{$ producto.nombre_tipo_producto_general | capitalizeWords $}</em></p>
                                    		</a>
                                		</h4>
                                	</div>
                                	<div id="producto_{$ $index $}" class="panel-collapse collapse" ng-class="{'in': $index == 0}" role="tabpanel">
                                		<div class="panel-body panel-body-producto">
                                			<div class="row is-flex row-fechas-producto">
                                			    <div class="col-xs-12 col-md-2 icono-calendario-producto">
    			                                    <div style="display:table; width:100%; height:100%;">
                                                        <div style="display:table-cell; vertical-align: middle;">
                                                            <p class="text-center"><i style="font-size:60px;" class="fa fa-calendar" aria-hidden="true"></i></p>
                                                        </div>
                                                    </div>                                
                                			    </div>
                                			    <div class="col-xs-12 col-md-10">
                                			        <div class="row is-flex">
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha proyectada de radicación: </strong> {$ producto.fecha_proyectada_radicacion $}</p>
                                        			    </div>
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha de remisión: </strong> {$ producto.fecha_remision $}</p>
                                        			    </div>                            			    
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha confirmación editorial: </strong> {$ producto.fecha_confirmacion_editorial $}</p>
                                        			    </div>                            			                                			    
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha de recepción evaluación: </strong> {$ producto.fecha_recepcion_evaluacion $}</p>
                                        			    </div>                            			                                			   
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha de respuesta evaluación: </strong> {$ producto.fecha_respuesta_evaluacion $}</p>
                                        			    </div>                            			                                	
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha de aprobación: </strong> {$ producto.fecha_aprobacion_publicacion $}</p>
                                        			    </div>                            			                                	   
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <p><strong>Fecha de publicación: </strong> {$ producto.fecha_publicacion $}</p>
                                        			    </div>                            			                                	                               			    
                                			        </div>
                                			    </div>
                                			</div>
                                			<table class="table table-hover table-bordered tabla-documentos-producto">
                                			    <tbody>
                                			        {{--tr carga fecha proyectada de radicación--}}
                                			        <tr>
                                			            <td>Cargar (o descargar existente) archivo de producto relacionado con la <strong>Fecha proyectada de radicación</strong></td>
                                			            <td>
                                			                <button type="button" class="btn btn-default btn-block" ng-click="cargar_fecha_proyectada_radicacion(producto)">Cargar documento&nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                			            </td>
                                			        </tr>
                                			        {{--tr carga fecha proyectada de publicación--}}
                                			        <tr>
                                			            <td>Cargar (o descargar existente) archivo de producto relacionado con la <strong>Fecha de publicación</strong></td>
                                			            <td>
                                			                <button type="button" class="btn btn-default btn-block" ng-click="cargar_fecha_publicacion(producto)">Cargar documento&nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                                        </td>
                                			        </tr>
                                			    </tbody>
                                			</table>
                                		</div>
                                	</div>
                                </div>
                                <br />
                            </div>
                        </div>
                        
                        {{--Overlay interno de tab productos--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>
                    </div>{{--Contenito tab productos--}}
                    
                    {{--Contenido tab gastos--}}
                    <div id="contenido_tab_gastos" class="tab-pane fade" ng-controller="gastos_controller">
                        <br />
                        <div class="container-sgpi">
                            {{--btn regreso a tab de seleccion de proyecto--}}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-default btn-block" ng-click="volver_a_proyectos()">
                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Proyectos
                                    </button>
                                </div>
                            </div>
                            <hr />
                            
                            {{--Gastos personal--}}
                            <div class="panel panel-default" ng-controller="gastos_personal_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_personal" aria-expanded="true" aria-controls="contenido_gastos_personal" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-minus"></span>
                                            <p>Gastos personal</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_personal" class="panel-collapse collapse in" role="tabpanel">
                                    <div class="panel-body">
                                        <h4 class="text-center" style="margin-bottom:0;">Participantes</h4>
                                        <hr />
                                        <div class="row is-flex">
                                            <div ng-repeat="gasto_personal in data.gastos.gastos_personal" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                <p><strong>{$ gasto_personal.nombre_completo $}</strong></p>
                                                <p>{$ gasto_personal.acronimo_id $}. {$ gasto_personal.identificacion $}</p>
                                                <button class="btn btn-default btn-block" ng-click="detalles_participante(gasto_personal)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del participante</button>
                                                <button class="btn btn-primary btn-block" ng-click="desembolso(gasto_personal)">Cargar desembolso o ver revisión</button>
                                            </div>
                                        </div>
                                        <hr />
                                        <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                        <hr>
                                        <div class="table-reponsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_personal.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                        <th>Gran total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_personal.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                        <th>{$ data.gastos.totales_gastos_personal.gran_total | currency:$:2 $}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>{{--Gastos personal--}}
                            
                            {{--Gastos equipos--}}
                            <div class="panel panel-default" ng-controller="gastos_equipos_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_equipos" aria-expanded="true" aria-controls="contenido_gastos_equipos" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-minus"></span>
                                            <p>Gastos Equipos</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_equipos" class="panel-collapse collapse in" role="tabpanel">
                                    <div class="panel-body">
                                        <h4 class="text-center" style="margin-bottom:0;">Equipos</h4>
                                        <hr />
                                        <div class="row is-flex">
                                            <div ng-repeat="gasto_equipo in data.gastos.gastos_equipos" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                <p><strong>{$ gasto_personal.concepto $}</strong></p>
                                                <button class="btn btn-default btn-block" ng-click="detalles_equipo(gasto_equipo)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del equipo</button>
                                                <button class="btn btn-primary btn-block" ng-click="desembolso(gasto_equipo)">Cargar desembolso o ver revisión</button>
                                            </div>
                                        </div>
                                        <hr />
                                        <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                        <hr>
                                        <div class="table-reponsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_equipos.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                        <th>Gran total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_equipos.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                        <th>{$ data.gastos.totales_gastos_equipos.gran_total | currency:$:2 $}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                
                            </div>{{--Gastos equipos--}}
                            
                        </div>
                        
                        {{--Overlay interno de tab productos--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>             
                        
                    </div>{{--Contenido tab gastos--}}
                    
                </div>
                                   
           </div>
           
           {{--Overlay o velo general--}}
           <div class="overlay" ng-show="visibilidad.show_velo_msj_operacion">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>    
            </div>           
        </div>
        
        <!--mas info proyecto-->
        <div class="box box-primary" ng-show="show_mas_info_proyecto" class="nga-fast nga-stagger-fast nga-fade">
            <div class="box-header with-border">
                <h4>Más información del proyecto</h4>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">
                <br />
                Coming soon
            </div>
            <div class="overlay" ng-show="visibilidad.show_velo_mas_info_proy">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_mas_info_proy">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>    
            </div>                       
        </div>
    </section>
    
    @include('investigador.proyectos.listar.modales')
    
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
        
    <script>
        sgpi_app.value('id_usuario', {{ Auth::user()->id }});
    </script>
@stop <!--Stop section 'post_scripts'-->


@if(isset($angular_sgpi_app_extra_dependencies))
    @section('post_sgpi_app_dependencies')
        <script>
            @foreach($angular_sgpi_app_extra_dependencies as $dependencie) 
                sgpi_app.requires.push('{{ $dependencie }}');
            @endforeach
        </script>
    @stop
@endif
