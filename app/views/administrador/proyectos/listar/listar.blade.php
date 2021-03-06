@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        li.current a{
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }
        li.current a:hover{
            color: #fff;
            background-color: #286090;
            border-color: #204d74;
        }        
        .overlay-2{
            z-index: 50;
            background: rgb(255, 255, 255);
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
        .icono-descripcion-producto{
            background-color: #F5F5F5;
            border-bottom: 1px solid #D2D6DE;
        }
        .row-descripcion-producto{
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
        .wrap{
            white-space: normal;
        }
        .btn-producto{
            white-space: normal; 
            padding-top: 20px; 
            padding-bottom: 20px;
            height: 100%;
        }
        .sin-margin{
            margin: 0;
        }
        .big-checkbox{
            height: 20px;
            width: 20px;
        }
    </style>    
@stop {{--Stop section 'styles'--}}

@section('pre_scripts')
    @if(isset($pre_scripts))
        @foreach($pre_scripts as $script) 
            <script type="text/javascript" src="/{{ $script }}"></script>
        @endforeach
    @endif
@stop {{--Stop section 'pre_scripts'--}}

@section('contenido')

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
        
        {{--Contenedor general de tabs--}}
        <div class="box">
            <div class="box-header with-border">
                <h3>Gestión de proyectos</h3>
            </div>
            <div class="box-body">
                
               {{--Tabs--}}
                <ul class="nav nav-tabs" id="tabs">
                    <li id="tab_proyectos" role="presentation" class="active">
                        <a href="#contenido_tab_proyectos" disabled>Proyectos</a>
                    </li>
                    <li id="tab_productos" role="presentation" ng-show="data.pestania_actual=='productos'"
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_productos" disabled>Productos del proyecto</a>
                    </li>
                    <li id="tab_gastos" role="presentation" ng-show="data.pestania_actual=='gastos'"
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_gastos" disabled>Ejecución presupuestal</a>
                    </li>
                    <li id="tab_informe_avance" role="presentation" ng-show="data.pestania_actual=='informe_avance'"
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_informe_avance" disabled>Informe de avance</a>
                    </li>                
                    <li id="tab_final_proyecto" role="presentation" ng-show="data.pestania_actual=='final_proyecto'"
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_final_proyecto" disabled>Final de proyecto</a>
                    </li>                          
                    <li id="tab_prorroga" role="presentation" ng-show="data.pestania_actual=='prorroga_proyecto'"
                    uib-tooltip="Navegar entre pestañas por los botones que presenta la pestaña Proyectos" tooltip-enable="true">
                        <a href="#contenido_tab_prorroga" disabled>Prórroga de final de proyecto</a>
                    </li>                                              
                </ul> {{--Tabs--}}        
                
                {{--Contenedor del contenido de cada tab--}}
                <div class="tab-content">
                    
                    {{--Contenito tab selección de proyectos--}}
                    <div id="contenido_tab_proyectos" class="tab-pane fade active in">
                        <br />
                        <div class="table-responsive" id="contenedor_proyectos">
                            <table datatable="ng" dt-options="dtOptions" class="table table-hover table-stripped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Código FMI</th>
                                        <th class="no-wrap">Subcentro de costo</th>
                                        <th class="no-wrap">Nombre de proyecto</th>
                                        <th class="no-wrap">Grupo de investigación ejecutor</th>
                                        <th>Duración (meses)</th>
                                        <th>Gestión de proyecto</th>
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
                                        <td>
                                            <div class="btn-group" uib-dropdown dropdown-append-to-body>
                                                <button type="button" class="fa fa-check btn btn-default" uib-dropdown-toggle>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" uib-dropdown-menu role="menu">
                                                    <li role="menuitem"><a href="#" ng-click="productos(proyecto.id)"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Productos</a></li>
                                                    <li role="menuitem"><a href="#" ng-click="gastos(proyecto.id)"><i class="fa fa-money" aria-hidden="true"></i>Ejecución presupuestal</a></li>
                                                    <li role="menuitem"><a href="#" ng-click="informe_avance(proyecto.id)"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>Informe de avance</a></li>
                                                    <li role="menuitem"><a href="#" ng-click="final_proyecto(proyecto.id)"><i class="fa fa-stop" aria-hidden="true"></i>Final de proyecto</a></li>
                                                    <li role="menuitem"><a href="#" ng-click="prorroga(proyecto.id)"><i class="fa fa-clock-o" aria-hidden="true"></i>Prórroga de proyecto</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td><button type="button" class="btn btn-default" ng-click="mas_info(proyecto.id)"><i class="fa fa-info-circle" aria-hidden="true"></i></button></td>
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
                                			<div class="row is-flex row-descripcion-producto">
                                			    <div class="col-xs-12 col-md-2 icono-descripcion-producto">
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
                                            <div class="row is-flex row-descripcion-producto">
                                			    <div class="col-xs-12 col-md-2 icono-descripcion-producto">
    			                                    <div style="display:table; width:100%; height:100%;">
                                                        <div style="display:table-cell; vertical-align: middle;">
                                                            <p class="text-center"><i style="font-size:60px;" class="fa fa-user" aria-hidden="true"></i></p>
                                                        </div>
                                                    </div>                                
                                			    </div>
                                			    <div class="col-xs-12 col-md-10">
                                			        <h4><small>Participante encargado: </small>{$ producto.investigador.nombres + ' ' + producto.investigador.apellidos $}</h4>
                                			        <div class="row is-flex">
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Identificación</label>
                                        			        {$ producto.investigador.acronimo_tipo_identificacion $}. {$ producto.investigador.identificacion $}
                                        			    </div>               
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Formación</label>
                                        			        {$ producto.investigador.formacion $}
                                        			    </div>         
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Edad</label>
                                        			        {$ producto.investigador.edad $}
                                        			    </div>
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Sexo</label>
                                        			        <span ng-if="producto.investigador.sexo=='m'">Hombre</span>
                                        			        <span ng-if="producto.investigador.sexo=='f'">Mujer</span>
                                        			    </div>         
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Email</label>
                                        			        {$ producto.investigador.email $}
                                        			    </div>    
                                        			    <div class="col-xs-12 col-sm-6 col-md-4">
                                        			        <label>Rol en el proyecto</label>
                                        			        <span ng-if="producto.investigador.id_rol==3">Investigador principal</span>
                                        			        <span ng-if="producto.investigador.id_rol==4">Investigador interno</span>
                                        			        <span ng-if="producto.investigador.id_rol==5">Investigador externo</span>
                                        			        <span ng-if="producto.investigador.id_rol==6">Estudiante</span>
                                        			    </div>                                            			    
                                        			    <div class="col-xs-12 col-sm-6 col-md-4" ng-if="producto.investigador.id_rol==3 || producto.investigador.id_rol==4">
                                        			        <label>Grupo de investigación</label>
                                        			        {$ producto.investigador.grupo_investigacion $}
                                        			    </div>                                         
                                        			    <div class="col-xs-12 col-sm-6 col-md-4" ng-if="producto.investigador.id_rol==3 || producto.investigador.id_rol==4">
                                        			        <label>Facultad</label>
                                        			        {$ producto.investigador.facultad $}
                                        			    </div>                                 
                                        			    <div class="col-xs-12 col-sm-6 col-md-4" ng-if="producto.investigador.id_rol==3 || producto.investigador.id_rol==4">
                                        			        <label>Sede</label>
                                        			        {$ producto.investigador.sede $}
                                        			    </div>                          
                                        			    <div class="col-xs-12 col-sm-6 col-md-4" ng-if="producto.investigador.id_rol==5 || producto.investigador.id_rol==6">
                                        			        <label>Entidad / grupo de investigación</label>
                                        			        {$ producto.investigador.entidad_grupo_investigacion $}
                                        			    </div>                                                 
                                        			    <div class="col-xs-12 col-sm-6 col-md-4" ng-if="producto.investigador.id_rol==6">
                                        			        <label>Programa académico</label>
                                        			        {$ producto.investigador.programa_academico $}
                                        			    </div>                                                                                         			    
                                        			</div>
                                        	    </div>
                                			</div>
                                			<div class="row is-flex sin-margin">
                                			    <div class="col-xs-12 col-sm-6" style="padding:0;">
                                			        <button type="button" class="btn btn-default btn-block btn-producto" ng-click="detalles_producto(producto, 'fecha_proyectada_radicacion')">
                                			            Producto - <strong>Fecha proyectada a radicar</strong>
                                			         </button>
                                			    </div>
                                			    <div class="col-xs-12 col-sm-6" style="padding:0;">
                                			        <button type="button" class="btn btn-default btn-block btn-producto" ng-click="detalles_producto(producto, 'fecha_publicacion')">
                                			            Producto - <strong>Fecha de publicación</strong>
                                			         </button>
                                			    </div>                                        			    
                                			</div>
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
                                        <div class="row is-flex">
                                            <div ng-repeat="gasto_personal in data.gastos.gastos_personal" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                <p><strong>{$ gasto_personal.nombre_completo $}</strong></p>
                                                <p>{$ gasto_personal.acronimo_id $}. {$ gasto_personal.identificacion $}</p>
                                                <button class="btn btn-default btn-block" ng-click="detalles_participante(gasto_personal)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del participante</button>
                                                <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_personal)">Revisión de desembolso <i class="fa fa-check-square-o" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                        <hr />
                                        <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                        <hr>
                                        <div class="table-responsive">
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
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos Equipos</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_equipos" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_equipos.length == 0">
                                            <h4 class="text-center">Sin gastos de equipos</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_equipos.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_equipo in data.gastos.gastos_equipos" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_equipo.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_equipo(gasto_equipo)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del equipo</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_equipo)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
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
                                </div>                            
                            </div>{{--Gastos equipos--}}
                            
                            {{--Gastos software--}}
                            <div class="panel panel-default" ng-controller="gastos_software_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_software" aria-expanded="true" aria-controls="contenido_gastos_software" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos software</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_software" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_software.length == 0">
                                            <h4 class="text-center">Sin gastos de software</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_software.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_software in data.gastos.gastos_software" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_software.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_software(gasto_software)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del software</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_software)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_software.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_software.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_software.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>{{--Gastos software--}}                            
                            
                            {{--Gastos salidas de campo--}}
                            <div class="panel panel-default" ng-controller="gastos_salidas_campo_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_salidas_campo" aria-expanded="true" aria-controls="contenido_gastos_salidas_campo" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos salidas de campo</p>
                                        </a>
                                    </h4>
                                </div> 
                                <div id="contenido_gastos_salidas_campo" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_salidas_campo.length == 0">
                                            <h4 class="text-center">Sin gastos de salidas de campo</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_salidas_campo.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_salida_campo in data.gastos.gastos_salidas_campo" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_salida_campo.justificacion $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_salida_campo(gasto_salida_campo)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles de la salida de campo</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_salida_campo)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_salidas_campo.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_salidas_campo.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_salidas_campo.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                 
                            </div>{{--Gastos salidas de campo--}}                            
                            
                            {{--Gastos materiales y suministros--}}
                            <div class="panel panel-default" ng-controller="gastos_materiales_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_materiales" aria-expanded="true" aria-controls="contenido_gastos_materiales" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos de materiales y suministros</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_materiales" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_materiales.length == 0">
                                            <h4 class="text-center">Sin gastos de materiales y suministros</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_materiales.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_material in data.gastos.gastos_materiales" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_material.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_material(gasto_material)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del material / suministro</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_material)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_materiales.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_materiales.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_materiales.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>{{--Gastos materiales y suministros--}}                                       
                            
                            {{--Gastos servicios técnicos--}}
                            <div class="panel panel-default" ng-controller="gastos_servicios_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_servicios" aria-expanded="true" aria-controls="contenido_gastos_servicios" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos de servicios técnicos</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_servicios" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_servicios.length == 0">
                                            <h4 class="text-center">Sin gastos de servicios técnicos</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_servicios.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_servicio in data.gastos.gastos_servicios" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_servicio.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_servicio(gasto_servicio)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del servicio técnico</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_servicio)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_servicios.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_servicios.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_servicios.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                   
                            </div>{{--Gastos servicios técnicos--}}
                            
                            {{--Gastos recursos bibliográficos--}}
                            <div class="panel panel-default" ng-controller="gastos_bibliograficos_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_bibliograficos" aria-expanded="true" aria-controls="contenido_gastos_bibliograficos" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos de recursos bibliográficos</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_bibliograficos" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_bibliograficos.length == 0">
                                            <h4 class="text-center">Sin gastos de recursos bibliográficos</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_bibliograficos.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_bibliografico in data.gastos.gastos_bibliograficos" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_bibliografico.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_bibliografico(gasto_bibliografico)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del recurso bibliográfico</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_bibliografico)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_bibliograficos.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_bibliograficos.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_bibliograficos.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                  
                            </div>{{--Gastos recursos bibliográficos--}}                            
                            
                            {{--Gastos recursos digitales--}}
                            <div class="panel panel-default" ng-controller="gastos_digitales_controller">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#contenido_gastos_digitales" aria-expanded="true" aria-controls="contenido_gastos_digitales" ng-click="abre_cierra_acordion()">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                            <p>Gastos de recursos educativos digitales</p>
                                        </a>
                                    </h4>
                                </div>
                                <div id="contenido_gastos_digitales" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <div ng-if="data.gastos.gastos_digitales.length == 0">
                                            <h4 class="text-center">Sin gastos de recursos educativos digitales</h4>
                                        </div>
                                        <div ng-if="data.gastos.gastos_digitales.length > 0">
                                            <div class="row is-flex">
                                                <div ng-repeat="gasto_digital in data.gastos.gastos_digitales" class="col-xs-12 col-sm-6 col-md-4 nga-fast nga-stagger-fast nga-fade">
                                                    <p><strong>{$ gasto_digital.concepto $}</strong></p>
                                                    <button class="btn btn-default btn-block" ng-click="detalles_digital(gasto_digital)"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Detalles del recurso educativo digital</button>
                                                    <button class="btn btn-primary btn-block" ng-click="revision_desembolso(gasto_digital)">Revisión de desembolso</button>
                                                </div>
                                            </div>
                                            <hr />
                                            <h4 class="text-center" style="margin-bottom:0;">Total de gastos</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_digitales.totales_por_entidad">{$ total_gasto_entidad.nombre_entidad $}</th>
                                                            <th>Gran total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td ng-repeat="total_gasto_entidad in data.gastos.totales_gastos_digitales.totales_por_entidad">{$ total_gasto_entidad.total_entidad | currency:$:2 $}</td>
                                                            <th>{$ data.gastos.totales_gastos_digitales.gran_total | currency:$:2 $}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                       
                            </div>                            
                            
                        </div>
                        
                        {{--Overlay interno de tab gastos--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>                           
                    </div>
                    
                    {{--Contenido tab informe avance--}}
                    <div id="contenido_tab_informe_avance" class="tab-pane fade" ng-controller="informe_avance_controller">
                        <br />
                        <div class="container-sgpi">
                            
                            {{--btn regreso a tab de seleccion de proyecto--}}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-default btn-block" ng-click="volver_a_proyectos()" ng-disabled="data.deshabilitar_btn_retorno_proyectos">
                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Proyectos
                                    </button>
                                </div>
                            </div>
                            
                            <hr />
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <p>Informe de avance</p>
                                        <p><em>Fecha proyectada para la carga del informe de avance (fecha mitad de duración del proyecto):</em> <strong>{$ fecha_mitad_proyecto $}</strong></p>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <h4 class="text-center" ng-if="informe_avance==null" class="nga-fast nga-stagger-fast nga-fade">No se ha cargado informe de avance aún</h4>
                                    <div class="row" ng-if="informe_avance!=null" class="nga-slow nga-stagger-fast nga-fade">
                                        <div class="col-xs-12">
                                            Informe de avance cargado en la fecha: {$ informe_avance.updated_at $}
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <a href="/file/informe_avance/{$ informe_avance.archivo $}" class="btn btn-primary">Descargar informe de avance <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                            <br />
                                            <br />
                                        </div>
                                        <div class="col-xs-12">
                                            <label>Comentario del investigador</label>
                                            <textarea rows="3" ng-model="informe_avance.comentario_investigador" class="form-control white-readonly" ng-readonly="true"></textarea>
                                        </div>            
                                        <div class="col-xs-12"><hr /></div>
                                        <div class="col-xs-12">
                                            <label>Seleccione para aprobar el informe de avance</label>
                                            <br />
                                            <label>
                                                <input ng-model="informe_avance.aprobado" type="checkbox" class="big-checkbox"> Aprobado / no aprobado
                                            </label>
                                            <br />
                                            <br />
                                        </div>
                                        <div class="col-xs-12">
                                            <label>Comentario de revisión (opcional)</label>
                                            <textarea rows="3" ng-model="informe_avance.comentario_revision" class="form-control"
                                            uib-tooltip="Ingresar un comentario de revisión" tooltip-enable="true"></textarea>
                                            <br />
                                        </div>                        
                                        <div class="col-xs-12">
                                            <br />
                                            <button class="btn btn-primary btn-block" ng-click="cargar_revision()" ng-disabled="guardando_revision">Guardar revisión <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                        </div>            
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{--Overlay interno de tab informe avance--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>                        
                    </div>{{--Contenido tab informe avance--}}                    
                    
                    {{--Contenido tab final proyecto--}}
                    <div id="contenido_tab_final_proyecto" class="tab-pane fade" ng-controller="final_proyecto_controller">
                        <br />
                        <div class="container-sgpi">
                            
                            {{--btn regreso a tab de seleccion de proyecto--}}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-default btn-block" ng-click="volver_a_proyectos()" ng-disabled="cargando_revision">
                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Proyectos
                                    </button>
                                </div>
                            </div>
                            
                            <hr />
                            
                            {{--Panel contenido--}}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <p>Final de proyecto</p>
                                        <p><em>Fecha relativa:</em> <strong>{$ fecha_final_proyecto $}</strong></p>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <h4 class="text-center" ng-if="final_proyecto==null" class="nga-fast nga-stagger-fast nga-fade">No se ha cargado final de proyecto aún</h4>
                                    <div class="row" ng-show="final_proyecto!=null" class="nga-fast nga-stagger-fast nga-fade">
                                        <div class="col-xs-12 col-sm-6">
                                            <a class="btn btn-primary btn-block wrap" href="/file/memoria_academica/{$ final_proyecto.archivo_memoria_academica $}">Descargar memoria académica&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <a class="btn btn-primary btn-block wrap" href="/file/acta_finalizacion/{$ final_proyecto.archivo_acta_finalizacion $}">Descargar acta de finalizacion&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>                 
                                        <div class="col-xs-12">
                                            <br />
                                            <label>Comentario del investigador</label>
                                            <textarea rows="3" class="form-control white-readonly" ng-model="final_proyecto.comentario_investigador" ng-readonly="true"></textarea>
                                        </div>                                        
                                        <div class="col-xs-12"><hr /></div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Seleccionar para aprobar final de proyecto</label>
                                                <br />
                                                <label>
                                                    <input ng-model="final_proyecto.aprobado" ng-change="cambia_estado_aprobado()" type="checkbox" class="big-checkbox"> Aprobado / no aprobado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <label>Cargar archivo de aprobación de final de proyecto</label>
                                            <span class="error-text" ng-show="documento_aprobacion_invalido">Archivo inválido. Cargar un archivo de máximo 20 MB</span>
                                            <input type="file"
                                            	ngf-select ng-model="documento_aprobacion" ngf-max-size="20MB"
                                            	ngf-change="validar_documento($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	ng-disabled="cargando_revision"
                                            	class="form-control" ng-class="{'invalid_control': documento_aprobacion_invalido}"
                                            	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB " tooltip-enable="true"/>
                                        </div>
                                        <div class="col-xs-12" ng-if="final_proyecto.archivo_aprobacion_final_proyecto!=null || final_proyecto.archivo_aprobacion_final_proyecto!=undefinded">
                                            <br />
                                            <a href="/file/aprobacion_final_proyecto/{$ final_proyecto.archivo_aprobacion_final_proyecto $}" class="btn btn-primary btn-block">Descargar archivo de aprobación de revisión <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-xs-12">
                                            <br />
                                            <label>Comentario de revisión (opcional)</label>
                                            <textarea rows="3" class="form-control" ng-model="final_proyecto.comentario_revision" ng-disabled="cargando_revision"
                                            uib-tooltip="Ingresar un comentario de revisión" tooltip-enable="true"></textarea>
                                        </div>
                                        <div class="col-xs-12">
                                            <br />
                                            <button class="btn btn-primary btn-block" ng-click="cargar_revision()" ng-disabled="cargando_revision">Cargar revisión <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </div>            
                                    </div>                                
                                    <br />
                                    <div ng-show="cargando_revision" class="nga-fast nga-stagger-fast nga-fade">
                                        <label for="progreso">Progreso de carga</label>&nbsp;&nbsp;<span ng-show="casi_terminado">Casi terminado, por favor esperar</span>
                                        <uib-progressbar class="progress-striped active" max="total_archivo" value="carga_actual" type="info"><i>{$ porcentaje_carga $}%</i></uib-progressbar>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        {{--Overlay interno de tab final proyecto--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>                                    
                    </div>{{--Contenido tab final proyecto--}}
                    
                    {{--Contenido tab prórroga--}}
                    <div id="contenido_tab_prorroga" class="tab-pane fade" ng-controller="prorroga_controller">
                        <br />
                        <div class="container-sgpi">
                            
                            {{--btn regreso a tab de seleccion de proyecto--}}
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-default btn-block" ng-click="volver_a_proyectos()" ng-disabled="cargando_revision">
                                        <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Proyectos
                                    </button>
                                </div>
                            </div>
                            
                            <hr />
                            
                            {{--Panel contenido--}}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <p>Aprobación de solicitud de prórroga de proyecto</p>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <h4 class="text-center" ng-if="prorroga==null" class="nga-fast nga-stagger-fast nga-fade">No se ha cargado solicitud de prórroga de final de proyecto</h4>
                                    <div class="row" ng-show="prorroga!=null" class="nga-fast nga-stagger-fast nga-fade">
                                        <div class="col-xs-12 col-sm-6">
                                            <a class="btn btn-primary btn-block wrap" href="/file/prorroga/{$ prorroga.archivo $}">Descargar archivo de solicitud de prórroga&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-xs-12">
                                            <br />
                                            <label>Comentario del investigador</label>
                                            <textarea rows="3" class="form-control white-readonly" ng-model="prorroga.comentario_investigador" ng-readonly="true"></textarea>
                                        </div>                                        
                                        <div class="col-xs-12"><hr /></div>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Seleccionar para aprobar prorroga</label>
                                                <br />
                                                <label>
                                                    <input ng-model="prorroga.aprobado" ng-change="cambia_estado_aprobado()" type="checkbox" class="big-checkbox"> Aprobado / no aprobado
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <label>Cargar archivo de aprobación de prorroga</label>
                                            <span class="error-text" ng-show="documento_aprobacion_invalido">Archivo inválido. Cargar un archivo de máximo 20 MB</span>
                                            <input type="file"
                                            	ngf-select ng-model="documento_aprobacion" ngf-max-size="20MB"
                                            	ngf-change="validar_documento($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	ng-disabled="cargando_revision"
                                            	class="form-control" ng-class="{'invalid_control': documento_aprobacion_invalido}"
                                            	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB " tooltip-enable="true"/>
                                        </div>
                                        <div class="col-xs-12" ng-if="prorroga.archivo_aprobacion!=null || prorroga.archivo_aprobacion!=undefinded">
                                            <br />
                                            <a href="/file/aprobacion_prorroga/{$ prorroga.archivo_aprobacion $}" class="btn btn-primary btn-block">Descargar archivo de aprobación de prorroga <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-xs-12">
                                            <br />
                                            <label>Comentario de revisión (opcional)</label>
                                            <textarea rows="3" class="form-control" ng-model="prorroga.comentario_revision" ng-disabled="cargando_revision"
                                            uib-tooltip="Ingresar un comentario de revisión" tooltip-enable="true"></textarea>
                                        </div>
                                        <div class="col-xs-12">
                                            <br />
                                            <button class="btn btn-primary btn-block" ng-click="cargar_revision()" ng-disabled="cargando_revision">Cargar revisión <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                        </div>            
                                    </div>                                
                                    <br />
                                    <div ng-show="cargando_revision" class="nga-fast nga-stagger-fast nga-fade">
                                        <label for="progreso">Progreso de carga</label>&nbsp;&nbsp;<span ng-show="casi_terminado">Casi terminado, por favor esperar</span>
                                        <uib-progressbar class="progress-striped active" max="total_archivo" value="carga_actual" type="info"><i>{$ porcentaje_carga $}%</i></uib-progressbar>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        {{--Overlay interno de tab final proyecto--}}
                        <div class="overlay-2" ng-show="show_velo_msj_operacion">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>                                    
                    </div>{{--Contenido tab prórroga--}}                    

                </div> {{--Contenedor del contenido de cada tab--}}
                
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

        {{--mas info proyecto--}}
        <div class="box box-primary" ng-show="visibilidad.show_mas_info_proyecto" ng-controller="mas_info_proyecto_controller" class="nga-fast nga-stagger-fast nga-fade">
            <div class="box-header with-border">
                <h4>Más información del proyecto</h4>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>                
            </div>
            <div class="box-body">
                <p class="text-center" ng-hide="mas_info_proyecto_consultada">Seleccionar más información de un proyecto</p>
                <div ng-show="mas_info_proyecto_consultada">
                    <h4 class="text-left">{$ datos_generales_proyecto.nombre $}</h4>
                    <br />
                    <div class="row is-flex">
                        <div class="col-xs-12">
                            <div id="desembolsos_aprobados_mas_info" style="width: 100%; height:370px;"></div>
                        </div>                                             
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <label>Inicio del proyecto</label>
                            <p>{$ datos_generales_proyecto.fecha_inicio $}</p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <label>Duración del proyecto (meses)</label>
                            <p>{$ datos_generales_proyecto.duracion_meses $}</p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <label for="progreso">Tiempo del proyecto transcurrido</label>
                            <p>({$ dias_proyecto_transcurridos + ' / ' + total_dias_proyecto + ' días' $})</p>
                            <uib-progressbar max="total_dias_proyecto" value="dias_proyecto_transcurridos" type="info"><i>{$ porcentaje_tiempo $}%</i></uib-progressbar>                            
                        </div>                                                
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <label>Fecha final calculada</label>
                            <p>{$ datos_generales_proyecto.fecha_fin $}</p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4" ng-if="datos_generales_proyecto.convocatoria!=null">
                            <label>Convocatoria</label>
                            <p>{$ datos_generales_proyecto.convocatoria $}</p>
                        </div>                                                
                        <div class="col-xs-12 col-sm-6 col-md-4" ng-if="datos_generales_proyecto.anio_convocatoria!=null">
                            <label>Año de convocatoria</label>
                            <p>{$ datos_generales_proyecto.anio_convocatoria $}</p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4" ng-if="datos_generales_proyecto.anio_convocatoria!=null">
                            <label>Objetivo general</label>
                            <p>{$ datos_generales_proyecto.objetivo_general $}</p>
                        </div>
                        <div class="col-xs-12">
                            <label>Objetivos específicos</label>
                            <div class="table-responsive" id="contenedor_objetivos_especificos">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                           <td ng-repeat="objetivo_especifico in objetivos_especificos">
                                               {$ objetivo_especifico.nombre $}
                                           </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <a href="/file/presupuesto/{$ documentos_iniciales.presupuesto.archivo $}" class="btn btn-primary btn-block">Descargar presupuesto&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                            <br />
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <a href="/file/presentacion_proyecto/{$ documentos_iniciales.presentacion_proyecto.archivo $}" class="btn btn-primary btn-block">Descargar presentación proy.&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                            <br />
                        </div>                        
                        <div class="col-xs-12 col-md-4">
                            <a href="/file/acta_inicio/{$ documentos_iniciales.acta_inicio.archivo $}" class="btn btn-primary btn-block">Descargar acta de inicio&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                            <br />
                        </div>                                                
                        <div class="col-xs-12"><div style="border-bottom: 1px solid #eee;margin-bottom:20px;">&nbsp;</div></div>
                        <div class="col-xs-12">
                            <label>Entidades / grupos investigación que participan en el proyecto</label>
                            <div class="table-responsive" id="contenedor_entidades_grupos_inv" style="max-height:300px;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Entidad / grupo de investigación</th>
                                            <th>Rol en el proyecto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="entidad_grupo_inv in entidades_grupos_investigacion">
                                            <td>{$ entidad_grupo_inv.entidad_grupo_investigacion $}</td>
                                            <td>{$ entidad_grupo_inv.rol_en_el_proyecto $}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                        <div class="col-xs-12"><div style="border-bottom: 1px solid #eee;margin-bottom:20px;">&nbsp;</div></div>
                        <div class="col-xs-12">
                            <label>Cantidad de productos del proyecto por tipo</label>
                            <div class="table-responsive" id="contenedor_productos_x_tipo" style="max-height:300px;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>           
                                    <tbody>
                                        <tr ng-repeat="prod in cantidad_productos">
                                            <td>{$ prod.nombre_tipo_producto_especifico + ' / ' + prod.nombre_tipo_producto_general | lowercase | capitalizeWords $}</td>
                                            <td>{$ prod.cantidad_productos $}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12"><div style="border-bottom: 1px solid #eee;margin-bottom:20px;">&nbsp;</div></div>
                        <div class="col-xs-12">
                            <label>Entidades fuente de presupuesto</label>
                            <div id="contenedor_entidades_fuente_pres" style="max-height:380px;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Entidad</th>
                                            <th>Total presupuesto</th>
                                        </tr>
                                    </thead>           
                                    <tbody>
                                        <tr ng-repeat="entidad in entidades_fuente_presupuesto.entidades">
                                            <td>{$ entidad.nombre_entidad_fuente_presupuesto $}</td>
                                            <td>{$ entidad.total | currency:$:2 $}</td>
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                        </div>
                        <div class="col-xs-12"><div style="border-bottom: 1px solid #eee;margin-bottom:20px;">&nbsp;</div></div>
                        <div class="col-xs-12">
                            <label>Participantes del proyecto</label>
                            <div id="contenedor_participantes" style="max-height:380px;">
                                <br />
                                <div class="panel panel-default" ng-repeat="investigador in investigadores">
                                	<div class="panel-heading" role="tab">
                                		<a class="panel-title" role="button" data-toggle="collapse" href="#investigador_{$ $index $}" 
                                		aria-expanded="true" aria-controls="investigador_{$ $index $}" ng-click="abre_cierra_acordion('investigador_' + $index)">
                                		    <span class="glyphicon glyphicon-plus"></span>
                                			{$ investigador.nombres $}&nbsp;{$ investigador.apellidos $}
                                		</a>
                                	</div>
                                	<div id="investigador_{$ $index $}" class="panel-collapse collapse" role="tabpanel">
                                		<div class="panel-body">
                                			<div class="row is-flex">
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Rol en el proyecto</label>
                                			        <p ng-if="investigador.id_rol==3">Investigador principal</p>
                                			        <p ng-if="investigador.id_rol==4">Investigador interno</p>
                                			        <p ng-if="investigador.id_rol==5">Investigador externo</p>
                                			        <p ng-if="investigador.id_rol==6">Estudiante</p>
                                			    </div>                                			    
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Identificación</label>
                                			        <p>{$ investigador.identificacion $}</p>
                                			    </div>
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Edad</label>
                                			        <p>{$ investigador.edad $}</p>
                                			    </div>                                			    
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Sexo</label>
                                			        <span ng-if="investigador.sexo=='m'">Hombre</span>
                                			        <span ng-if="investigador.sexo=='f'">Mujer</span>
                                			    </div>    
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Email</label>
                                			        <p>{$ investigador.email $}</p>
                                			    </div>                   
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Formación</label>
                                			        <p>{$ investigador.formacion $}</p>
                                			    </div>                       
                                			    <div class="col-xs-12 col-sm-6" ng-if="investigador.id_rol==3 || investigador.id_rol==4">
                                			        <label>Grupo de investigación UCC</label>
                                			        <p>{$ investigador.nombre_grupo_investigacion_ucc $}</p>
                                			    </div>                                            
                                			    <div class="col-xs-12 col-sm-6" ng-if="investigador.id_rol==3 || investigador.id_rol==4">
                                			        <label>Facultad</label>
                                			        <p>{$ investigador.nombre_facultad_dependencia_ucc $}</p>
                                			    </div>                                             
                                			    <div class="col-xs-12 col-sm-6" ng-if="investigador.id_rol==3 || investigador.id_rol==4">
                                			        <label>Sede</label>
                                			        <p>{$ investigador.nombre_sede_ucc $}</p>
                                			    </div> 
                                			    <div class="col-xs-12 col-sm-6" ng-if="investigador.id_rol==5 || investigador.id_rol==6">
                                			        <label>Entidad / grupo de investigación</label>
                                			        <p>{$ investigador.entidad_o_grupo_investigacion $}</p>
                                			    </div>                                			 
                                			    <div class="col-xs-12 col-sm-6" ng-if="investigador.id_rol==6">
                                			        <label>Programa académico</label>
                                			        <p>{$ investigador.programa_academico $}</p>
                                			    </div>                                			                                 			    
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Dedicación horas semanales</label>
                                			        <p>{$ investigador.dedicacion_horas_semanales $}</p>
                                			    </div>       
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Total semanas</label>
                                			        <p>{$ investigador.total_semanas $}</p>
                                			    </div>    
                                			    <div class="col-xs-12 col-sm-6">
                                			        <label>Valor hora</label>
                                			        <p>{$ investigador.valor_hora | currency:$:2 $}</p>
                                			    </div>                                    			    
                                			    
                                			</div>
                                		</div>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    
    @include('administrador.proyectos.listar.modales')

@stop {{--Stop section 'contenido'--}}

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
    <script>
        sgpi_app.value('id_usuario', {{ Auth::user()->id }});
    </script>
@stop {{--Stop section 'post_scripts'--}}


@if(isset($angular_sgpi_app_extra_dependencies))
    @section('post_sgpi_app_dependencies')
        <script>
            @foreach($angular_sgpi_app_extra_dependencies as $dependencie) 
                sgpi_app.requires.push('{{ $dependencie }}');
            @endforeach
        </script>
    @stop
@endif
