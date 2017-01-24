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
        #table_head_productos tr th{
            white-space: nowrap;
        }
        #table_body_productos tr td{
            white-space: nowrap;
        }
        .tooltip.tooltip-invalid_control .tooltip-inner {
            color: white;
            background-color: #8B0000;
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
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
                <a href="#"><b>Editar proyecto</b></a>
            </li>
        </ol>
        <br />
    </section>
    
    
     
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="crear_proyecto_controller">
        
        <form action="/proyectos/registrar_nuevo_proyecto" method="POST" enctype='multipart/form-data'>
        
            <div class="box">
                <div class="box-header with-border">
                    <h3>Editar proyecto</h3>
                </div>
               <div class="box-body">    
               
                  
                    <div class="tab-content">
 
                        {{--Contenido tab productos--}}
                        <div id="contenido_productos" class="tab-pane fade active in"
                        ng-controller="editar_productos_proyecto_controller">
                            <input type="hidden" name="cantidad_productos" value="{$ data.productos.length $}"/>
                            <br />
                            <div class="container-sgpi">
                        		<br />
                        		<div class="row">
                        		    <div class="col-xs-12">
                        		        <label for="tipos_productos_generales">Categoría del producto <span class="error-text" ng-show="visibilidad.tipos_productos_generales_invalido">Campo requerido. Elegir una opción.</span></label>
                        		        <ui-select id="tipos_productos_generales" theme="bootstrap"
                    					ng-model="data.tipo_producto_general" ng-change="cambia_tipo_prod_general()"
                    					ng-class="{'invalid_control': visibilidad.tipos_productos_generales_invalido}">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.tipos_productos_generales | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                        		    </div>
                        		    <div class="col-xs-12">&nbsp;</div>
                        		    <div class="col-xs-12">
                        		        <label for="tipos_productos_especificos">Tipo de producto <span class="error-text" ng-show="visibilidad.tipo_producto_especifico_invalido">Campo requerido. Elegir una opción.</span></label>
                        		        <h4 class="text-center" ng-if="data.tipos_productos_especificos.length==0" 
                        		        style="border: 1px solid lightgray; padding-top: 30px; padding-bottom: 30px;">Seleccione una categoría de producto</h4>
                        		        
                        		        <select id="tipos_productos_especificos" ng-model="data.tipo_producto_especifico" ng-change="validar_tipo_producto_especifico()" ng-if="data.tipos_productos_especificos.length > 0"
                        		        class="form-control"
                        		        ng-class="{'invalid_control': visibilidad.tipo_producto_especifico_invalido}"
                        		        size="5">
                        		            <option ng-repeat="tipo_producto_especifico in data.tipos_productos_especificos" 
                        		            class="nga-fast nga-stagger-fast nga-fade"
                        		            value="{$ tipo_producto_especifico.id $}">{$ tipo_producto_especifico.nombre $}</option>
                        		        </select>
                        		    </div>
                        		    <div class="col-xs-12">&nbsp;</div>
                        		    <div class="col-xs-12 col-sm-6 col-md-4">
                        		        <button type="button" class="btn btn-default btn-block" ng-click="agregar_producto()">Agregar nuevo tipo de producto <i class="fa fa-plus" aria-hidden="true"></i></button>
                        		    </div>
                        		</div>
                        		<hr />
                        		<div class="table-responsive" id="contenedor_productos">
                        		    <!--id="contenedor_productos"-->
                        		    <table class="table table-hover">
                        		        <thead id="table_head_productos">
                        		            <tr>
                        		                <!--<th>item</th>-->
                        		                <th>Categoría de producto</th>
                        		                <th>Tipo de producto</th>
                        		                <th>Nombre</th>
                        		                <th>Participante encargado</th>
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
                        		            <tr ng-if="data.info_productos.length==0"><td colspan="11"><p class="text-left">Productos no añadidos</p></td></tr>
                        		            <tr ng-if="data.info_productos.length>0" ng-repeat="item in data.info_productos" class="nga-fast nga-stagger-fast nga-fade">
                        		                <!--<td>{$ item.producto.investigador $}</td>-->
                        		                <td>
                        		                    <input type="text" ng-readonly="true"
                        		                    ng-model="item.producto.tipo_producto_e.tipo_producto_g.nombre"
                        		                    class="white-readonly form-control"/>
                        		                    <input type="hidden" name="id_tipo_producto_general_{$ $index $}" value="{$ producto.tipo_producto_general.id $}"/>
                        		                </td>
                        		                <td>
                        		                    <input type="text" ng-readonly="true"
                        		                    ng-model="item.producto.tipo_producto_e.nombre"
                        		                    class="white-readonly form-control"/>
                        		                    <input type="hidden" name="id_tipo_producto_especifico_{$ $index $}" value="{$ producto.tipo_producto_especifico.id $}"/>
                        		                </td>
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.nombre_invalido">Longitud mínima de 5 caracteres y máxima de 200</span>
                        		                    <input type="text" name="nombre_producto_{$ $index $}" ng-model="item.producto.nombre" ng-change="validar_nombre_producto(producto)"
                        		                    class="form-control" ng-class="{'invalid_control': producto.nombre_invalido}" style="min-width: 170px;"/>
                        		                </td>
                        		                
                        		                <!--<td>{$ item.producto.investigador.persona  $}</td>-->
                        		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.participante_invalido">Campo requerido. Elegir un participante</span>
                                					<ui-select theme="bootstrap" append-to-body="true"
                                					ng-model="item.producto.investigador.persona" ng-change="validar_participante_producto($select.selected,item.producto.investigador.persona)"
                                					ng-required="true" ng-class="{'invalid_control': producto.participante_invalido}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.info_investigador.nombres + ' ' + $select.selected.info_investigador.apellidos $}</ui-select-match>
                                						<ui-select-choices repeat="participante in data.info_investigadores_usuario | filter: $select.search">
                                							<div ng-bind-html="participante.info_investigador.nombres + ' ' + participante.info_investigador.apellidos | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>                                                
                                					
                                					<!--{$ data.info_investigadores_usuario $}-->
                                					<input type="hidden" name="encargado_producto_{$ $index $}" value="{$ item.producto.investigador.persona.identificacion $}"/>
                        		                </td>
                        		                {{----}}
                        		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_proyectada_radicar_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_proyectada_radicar_{$ $index $}"
                                                            ng-model="item.producto.fecha_proyectada_radicacion" ng-change="validar_fecha_proyectada_radicar(producto)"
                                                            is-open="producto.show_popup_fecha_proyectada_radicar"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_proyectada_radicar=true"
                                                            class="form-control white-readonly" ng-class="{'invalid_control': producto.fecha_proyectada_radicar_invalido}"
                                                            ng-readonly="true"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_proyectada_radicar=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                        		                    </div>
                        		                </td>
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_remision_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_remision_{$ $index $}"
                                                            ng-model="item.producto.fecha_remision" ng-change="validar_fecha_remision(producto)"
                                                            is-open="producto.show_popup_fecha_remision"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_remision=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_remision_invalido}"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_remision=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>                    		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_confirmacion_editorial_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_confirmacion_editorial_{$ $index $}"
                                                            ng-model="item.producto.fecha_confirmacion_editorial" ng-change="validar_fecha_confirmacion_editorial(producto)"
                                                            is-open="producto.show_popup_fecha_confirmacion_editorial"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_confirmacion_editorial=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_confirmacion_editorial_invalido}"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_confirmacion_editorial=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>                    		                                    		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_recepcion_evaluacion_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_recepcion_evaluacion_{$ $index $}"
                                                            ng-model="item.producto.fecha_recepcion_evaluacion" ng-change="validar_fecha_recepcion_evaluacion(producto)"
                                                            is-open="producto.show_popup_fecha_recepcion_evaluacion"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_recepcion_evaluacion=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_recepcion_evaluacion_invalido}"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_recepcion_evaluacion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>                    		                                    		                
                        		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_respuesta_evaluacion_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_respuesta_evaluacion_{$ $index $}"
                                                            ng-model="item.producto.fecha_respuesta_evaluacion" ng-change="validar_fecha_respuesta_evaluacion(producto)"
                                                            is-open="producto.show_popup_fecha_respuesta_evaluacion"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_respuesta_evaluacion=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_respuesta_evaluacion_invalido}"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_respuesta_evaluacion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>                    		                                    		                                        		                
                        		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_aprobacion_publicacion_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_aprobacion_publicacion_{$ $index $}"
                                                            ng-model="item.producto.fecha_aprobacion_publicacion" ng-change="validar_fecha_aprobacion_publicacion(producto)"
                                                            is-open="producto.show_popup_fecha_aprobacion_publicacion"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_aprobacion_publicacion=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_aprobacion_publicacion_invalido}" />
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_aprobacion_publicacion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>                    		                        		                
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_publicacion_invalido">{$ producto.msj_fecha_publicacion_invalido $}</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_publicacion_{$ $index $}"
                                                            ng-model="item.producto.fecha_publicacion" ng-change="validar_fecha_publicacion(producto)"
                                                            is-open="producto.show_popup_fecha_publicacion"
                                                            datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                            clear-text="Borrar" close-text="Seleccionar" current-text="Seleccionar"
                                                            ng-click="producto.show_popup_fecha_publicacion=true"
                                                            class="form-control" ng-class="{'invalid_control': producto.fecha_publicacion_invalido}"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="producto.show_popup_fecha_publicacion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                        		                </td>    
                        		                <td>
                        		                    <button type="button" class="btn btn-default" ng-click="remover_producto(producto)"><i class="fa fa-times" aria-hidden="true"></i></button>
                        		                </td>
                        		            </tr>
                        		        </tbody>
                        		    </table>
                        		</div>
                    		    <hr />
                    		    <div class="row">
                                   		        
                    		        <div class="col-xs-12 col-sm-6 col-md-4">
                    		            <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_gastos()">Guardar Cambios</button>
                    		        </div>
                    		    </div>                    		
                        	</div>
                        </div> {{--Contenido tab productos--}}
  
                    </div> {{--Contenedor del contenido de cada tab--}}
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
        sgpi_app.value('id_usuario', {{ Auth::user()->id }});
        id_proyecto="{{$proyecto_id}}";
        pagina="{{$pagina}}";
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
