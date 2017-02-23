@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        .nested-box{
            box-shadow: 0 5px 9px rgba(0, 0, 0, 0.1);
            border: 0;
        }
        button[uib-is-class].active{
            background-color: #337AB7 !important;
        }
        button[uib-is-class].active > span{
            color: white !important;            
        }
        .white-space-no-wrap{
            white-space: nowrap;
        }
        .tooltip.tooltip-invalid_control .tooltip-inner {
            color: white;
            background-color: #8B0000;
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }
        .h4_seleccion_categoria{
            border: 1px solid lightgray; 
            padding-top: 30px; 
            padding-bottom: 30px;            
        }
        .min-width-180{
            min-width: 180px;
        }
        .max-width-300{
            max-width: 300px;
        }
        
        #contenedor_productos
        {
          position: relative;
        }   
        .ps-container > .ps-scrollbar-x-rail{
            opacity: 0.7 !important;
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

    <!--migajas de pan-->
    <section class="content-header">
        <ol class="breadcrumb">
            <li>
                <a href="/"><i class="fa fa-home" style="font-size:18px;"></i></a>
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </li> 
            <li>
                <a href="/proyectos/listar"><b>Proyectos</b></a>
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </li>
            <li>Editar productos</li>
        </ol>
        <br />
    </section>
    
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_productos_controller">
        
        <form action="/proyectos/editar/productos" method="POST">
            
            <input type="hidden" name="id_proyecto" value="{{ $id_proyecto }}"/>
            <div id="contenedor_productos_a_eliminar"></div>

            <div class="box">
                
                <div class="box-header with-border">
                    <h3>Edición de productos de proyecto</h3>
                </div>
                
                <div class="box-body">    
                   
                    {{--Información del proyecto--}}
                    <div class="callout callout-info">
                    	<h4>{$ informacion_proyecto.nombre_proyecto $}</h4>
                    	<p>
                    		Grupo de investigación: <strong>{$ informacion_proyecto.grupo_investigacion_ejecutor $} - {$ informacion_proyecto.facultad $} - Sede {$ informacion_proyecto.sede $}</strong>
                    	</p>
                    	<p>
                    	    Investigador principal: <strong>{$ informacion_proyecto.nombre_completo_investigador_principal $}</strong>
                    	</p>
                    </div>    
            		
            		<div class="row">
            		    <div class="col-xs-12 col-sm-6 col-md-4">
            		        <button class="btn btn-primary btn-block" type="button" ng-click="agregar_producto()">Agregar producto <i class="fa fa-plus"></i></button>
            		    </div>
            		</div>
            		
            		<br />

            		<div class="table-responsive" id="contenedor_productos">
            		    <table class="table table-hover table-bordered">
            		        <thead id="table_head_productos">
            		            <tr>
            		                <th class="white-space-no-wrap">Categoría de producto</th>
            		                <th class="white-space-no-wrap">Tipo de producto</th>
            		                <th class="white-space-no-wrap">Nombre</th>
            		                <th class="white-space-no-wrap">Participante encargado</th>
            		                <th>Fecha proyectada para radicar</th>
            		                <th>Fecha de remisión</th>
            		                <th>Fecha de confirmación de editorial</th>
            		                <th>Fecha de recepción de evaluación</th>
            		                <th>Fecha de respuesta de evaluación</th>
            		                <th>Fecha de aprobación para publicación</th>
            		                <th>Fecha de publicación</th>
            		                <th>Remover</th>
            		            </tr>
            		        </thead>
            		        <tbody id="table_body_productos">
            		            <tr ng-if="productos.length==0"><td colspan="11"><p class="text-left">Productos no añadidos</p></td></tr>
            		            <tr ng-if="productos.length>0" ng-repeat="producto in productos" class="nga-fast nga-stagger-fast nga-fade">
            		                
            		                {{--tipo_producto_general--}}
            		                <td class="max-width-300">
            		                    <span class="error-text" ng-show="producto.tipo_producto_general_invalido">Campo requerido. Elegir una opción</span>
                    					<ui-select theme="bootstrap" append-to-body="true"
                    					ng-model="producto.tipo_producto_general" ng-change="cambia_tipo_producto_general(producto)"
                    					ng-required="true"
                    					ng-class="{'invalid_control': producto.tipo_producto_general_invalido}"
                    					uib-tooltip="{$ producto.tipo_producto_general.nombre | capitalizeWords $}" tooltip-append-to-body="true" tooltip-enable="producto.tipo_producto_general.nombre.length > 40">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre | capitalizeWords $}</ui-select-match>
                    						<ui-select-choices repeat="tipo_producto_general in tipos_productos_generales | filter: $select.search">
                    							<div ng-bind-html="tipo_producto_general.nombre | highlight: $select.search | capitalizeWords"></div>
                    						</ui-select-choices>
                    					</ui-select>      
            		                    <input type="hidden" name="tipo_producto_general_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.tipo_producto_general.id $}"/>
            		                </td>{{--./tipo_producto_general--}}
            		                
            		                {{--tipo_producto_especifico--}}
            		                <td class="max-width-300">
            		                    <span class="error-text" ng-show="producto.tipo_producto_especifico_invalido">Campo requerido. Elegir una opción</span>
                    					<ui-select theme="bootstrap" append-to-body="true"
                    					ng-model="producto.tipo_producto_especifico" ng-change="validar_tipo_producto_especifico(producto)"
                    					ng-required="true"
                    					ng-class="{'invalid_control': producto.tipo_producto_especifico_invalido}"
                    					uib-tooltip="{$ producto.tipo_producto_especifico.nombre $}" tooltip-append-to-body="true" tooltip-enable="producto.tipo_producto_especifico.nombre.length > 40">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="tipo_producto_especifico in producto.coleccion_tipos_productos_especificos | filter: $select.search">
                    							<div ng-bind-html="tipo_producto_especifico.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>      
            		                    <input type="hidden" name="tipo_producto_especifico_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.tipo_producto_especifico.id $}"/>
            		                </td>{{--./tipo_producto_especifico--}}
            		                
            		                {{--nombre--}}
            		                <td>
            		                    <span class="error-text" ng-show="producto.nombre_invalido">Longitud mínima de 5 caracteres y máxima de 200</span>
            		                    <input type="text" name="nombre_producto_{$ producto.id_producto $}_{$ $index $}" 
            		                    ng-model="producto.nombre" ng-change="validar_nombre_producto(producto)"
            		                    class="form-control" ng-class="{'invalid_control': producto.nombre_invalido}" style="min-width: 170px;"/>
            		                </td>{{--./nombre--}}
            		                
            		                {{--participante encargado--}}
            		                <td>
            		                    <span class="error-text" ng-show="producto.participante_invalido">Campo requerido. Elegir un encargado</span>
                    					<ui-select theme="bootstrap" append-to-body="true"
                    					ng-model="producto.participante" ng-change="validar_participante_encargado(producto)"
                    					ng-required="true" 
                    					ng-class="{'invalid_control': producto.participante_invalido}">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombres + ' ' + $select.selected.apellidos $}</ui-select-match>
                    						<ui-select-choices repeat="participante in participantes | filter: $select.search">
                    							<div ng-bind-html="participante.nombres + ' ' + participante.apellidos | highlight: $select.search"></div>
                    							<span ng-bind-html="'Identificación: ' + participante.identificacion | highlight: $select.search">Identificación: </span>
                    						</ui-select-choices>
                    					</ui-select>
            		                    <input type="hidden" name="participante_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.participante.id_investigador $}"/>                		                    
            		                </td>{{--./participante encargado--}}
            		                
            		                {{--fecha_proyectada_radicar--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_proyectada_radicacion_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text"
                                                ng-model="producto.fecha_proyectada_radicacion" ng-change="validar_fecha('fecha_proyectada_radicacion', producto)"
                                                is-open="producto.show_popup_fecha_proyectada_radicar"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_proyectada_radicar=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_proyectada_radicacion_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_proyectada_radicar=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
            		                    </div>
            		                    <input type="hidden" name="fecha_proyectada_radicacion_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_proyectada_radicacion | date_to_string $}">
            		                </td>{{--./fecha_proyectada_radicar--}}
            		                
            		                {{--fecha_remision--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_remision_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text"
                                                ng-model="producto.fecha_remision" ng-change="validar_fecha('fecha_remision', producto)"
                                                is-open="producto.show_popup_fecha_remision"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_remision=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_remision_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_remision=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_remision_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_remision | date_to_string $}">
            		                </td>{{--./fecha_remision--}}
            		                
            		                {{--fecha_confirmacion_editorial--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_confirmacion_editorial_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text"
                                                ng-model="producto.fecha_confirmacion_editorial" ng-change="validar_fecha('fecha_confirmacion_editorial', producto)"
                                                is-open="producto.show_popup_fecha_confirmacion_editorial"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_confirmacion_editorial=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_confirmacion_editorial_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_confirmacion_editorial=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_confirmacion_editorial_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_confirmacion_editorial | date_to_string $}">
            		                </td>{{--./fecha_confirmacion_editorial--}}
            		                
            		                {{--fecha_recepcion_evaluacion--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_recepcion_evaluacion_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text"
                                                ng-model="producto.fecha_recepcion_evaluacion" ng-change="validar_fecha('fecha_recepcion_evaluacion', producto)"
                                                is-open="producto.show_popup_fecha_recepcion_evaluacion"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_recepcion_evaluacion=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_recepcion_evaluacion_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_recepcion_evaluacion=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_recepcion_evaluacion_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_recepcion_evaluacion | date_to_string $}">
            		                </td>{{--./fecha_recepcion_evaluacion--}}
            		                
            		                {{--fecha_respuesta_evaluacion--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_respuesta_evaluacion_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text" 
                                                ng-model="producto.fecha_respuesta_evaluacion" ng-change="validar_fecha('fecha_respuesta_evaluacion', producto)"
                                                is-open="producto.show_popup_fecha_respuesta_evaluacion"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_respuesta_evaluacion=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_respuesta_evaluacion_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_respuesta_evaluacion=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_respuesta_evaluacion_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_respuesta_evaluacion | date_to_string $}">
            		                </td>{{--./fecha_respuesta_evaluacion--}}
            		                
            		                {{--fecha_aprobacion_publicacion--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_aprobacion_publicacion_invalido">Ingresar fecha</span>
            		                    <div class="input-group">
                                            <input type="text" 
                                                ng-model="producto.fecha_aprobacion_publicacion" ng-change="validar_fecha('fecha_aprobacion_publicacion', producto)"
                                                is-open="producto.show_popup_fecha_aprobacion_publicacion"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_aprobacion_publicacion=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_aprobacion_publicacion_invalido}" 
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_aprobacion_publicacion=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_aprobacion_publicacion_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_aprobacion_publicacion | date_to_string $}">
            		                </td>{{--./fecha_aprobacion_publicacion--}}
            		                
            		                {{--fecha_publicacion--}}
            		                <td class="min-width-180">
            		                    <span class="error-text" ng-show="producto.fecha_publicacion_invalido">Ingresar fecha. Debe ser mayor a la fecha proyectada de radicación</span>
            		                    <div class="input-group">
                                            <input type="text" 
                                                ng-model="producto.fecha_publicacion" ng-change="validar_fecha('fecha_publicacion', producto)"
                                                is-open="producto.show_popup_fecha_publicacion"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="producto.show_popup_fecha_publicacion=true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_publicacion_invalido}"
                                                ng-readonly="true"/>
                                            <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_publicacion=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="fecha_publicacion_{$ producto.id_producto $}_{$ $index $}" value="{$ producto.fecha_publicacion | date_to_string $}">
            		                </td>{{--./fecha_publicacion--}}
            		                
            		                {{--btn eliminar--}}
            		                <td>
            		                    <button type="button" class="btn btn-default" ng-click="remover_producto(producto)"><i class="fa fa-times" aria-hidden="true"></i></button>
            		                </td>{{--./btn eliminar--}}
            		            </tr>
            		        </tbody>
            		    </table>
            		    <br />
            		    <br />
            		</div>
            		<p>Cantidad total de productos: <strong>{$ productos.length $}</strong></p>
        		    <hr />
        		    <div class="row">
        		        <div class="col-xs-12 col-sm-6 col-md-4">
        		            <button type="button" class="btn btn-primary btn-block" ng-click="guardar()">
        		                Guardar Cambios <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
        		            <input type="submit" id="input_submit_form" ng-hide="true"/>
        		        </div>
        		        <div class="col-xs-12 col-sm-6 col-md-4">
        		            <a href="/proyectos/listar" type="button" class="btn btn-default btn-block">Cancelar</a>
        		        </div>
        		    </div>                    		
                	
                </div>
               
        		<div class="overlay" ng-show="visibilidad.show_velo_general">
                    <div style="display:table; width:100%; height:100%;">
                        <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion_general">
                			<!--Contenido generado desde crear_usuarios_controller-->
                        </div>
                    </div>    		    
        		</div>
            </div> 
        
        </form>
        
    </section>
    
  
@stop <!--Stop section 'contenido'-->

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
    <script type="text/javascript">
        sgpi_app.value('id_proyecto', {{ $id_proyecto }});
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
