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
                <a href="/proyectos/listar"><b>Proyectos</b></a>
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </li>             
            <li>
                <a>Editar inf. general</a>
            </li>
        </ol>
        <br />
    </section>
    
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_datos_basicos_controller">
        
        <form action="/proyectos/post_edicion_datos_basicos" method="POST">

            <!--//// este iput hideen manda el id del proyecto-->
            <input type="hidden" name="id_proyecto" value="{{ $id_proyecto }}" />
            
            <div class="box">
                
                <div class="box-header with-border">
                    <h3>Edición de información general de proyecto</h3>
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
                    
                    <div id="contenido_info_general">
                        <br />
                        <div class="container-sgpi">
                            
                            <!--datos básicos-->
                            <fieldset>
                    			<legend>Datos básicos</legend>
                                <div class="row is-flex">
                                    
                                    {{--Cidigo FMI--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="codigo_fmi">Código FMI <span class="error-text" ng-show="codigo_fmi_invalido">Longitud mínima 2 caracteres y máxima de 50</span></label>
                                            <input type="text" name="codigo_fmi" id="codigo_fmi" ng-model="proyecto.codigo_fmi" ng-change="validar_codigo_fmi()" class="form-control" ng-class="{'invalid_control': codigo_fmi_invalido}"/>
                                        </div>
                                    </div> 
                                    
                                    {{--Subcentro de costo--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="subcentro_costo">Subcentro de costo <span class="error-text" ng-show="subcentro_costo_invalido">Longitud mínima 2 caracteres y máxima de 50</span></label>
                                            <input type="text" name="subcentro_costo" id="subcentro_costo" ng-model="proyecto.subcentro_costo" ng-change="validar_subcentro_costo()" class="form-control" ng-class="{'invalid_control': subcentro_costo_invalido}"/>
                                        </div>
                                    </div> 
                                    
                                    {{--Nombre del proyecto--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="nombre_proyecto">Nombre del proyecto <span class="error-text" ng-show="nombre_proyecto_invalido">Longitud mínima 5 caracteres y máxima 200</span></label>
                                            <input type="text" name="nombre_proyecto" id="nombre_proyecto" ng-model="proyecto.nombre" ng-change="validar_nombre_proyecto()" class="form-control" ng-class="{'invalid_control': nombre_proyecto_invalido}"/>
                                        </div>
                                    </div> 
                                    
                                    {{--Fecha de inicio del proyecto--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <label for="fecha_inicio">Fecha de inicio del proyecto <span class="error-text" ng-show="fecha_inicio_invalido">Campo incorrecto. Seleccionar fecha</span></label>
                                        <div class="input-group">
                                            <input type="text" name="fecha_inicio" id="fecha_inicio" 
                                                ng-model="proyecto.fecha_inicio" ng-change="calcular_fecha_final()"
                                                is-open="show_datepicker_fecha_inicio"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-click="show_datepicker_fecha_inicio=true"
                                                ng-readonly="true"
                                                class="form-control white-readonly" ng-class="{'invalid_control': fecha_inicio_invalido}"/>
                                            <span class="input-group-addon btn btn-default" ng-click="show_datepicker_fecha_inicio=true">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                    </div> 
                                    
                                    {{--Duración en meses del proyecto--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="duracion_meses">Duración en meses del proyecto <span class="error-text" ng-show="duracion_meses_invalido">Minimo debe ser 12 meses</span></label>
                                            <input type="number" min="12" name="duracion_meses" id="duracion_meses" ng-model="proyecto.duracion_meses" ng-change="calcular_fecha_final()" 
                                            class="form-control" ng-class="{'invalid_control': duracion_meses_invalido}"/>
                                        </div>
                                    </div> 
                                    
                                    {{--Fecha final del proyecto aproximada--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <label for="fecha_final">Fecha final del proyecto</label>
                                        <div class="input-group">
                                            <input type="text" name="fecha_final" id="fecha_final" class="form-control white-readonly" 
                                                ng-model="proyecto.fecha_fin" is-open="false"
                                                datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd"
                                                clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                ng-readonly="true"
                                                data-toggle="tooltip" data-placement="top" title="Calculado automáticamente con duración y fecha de inicio"/>
                                            <span class="input-group-addon btn btn-default">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </div>
                                    </div> 
                                    
                                    {{--Convocatoria--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="convocatoria">Convocatoria <span class="error-text" ng-show="convocatoria_invalido">Longitud mínima 5 caracteres y máxima 200</span></label>
                                            <input type="text" name="convocatoria" id="convocatoria" ng-model="proyecto.convocatoria" ng-change="validar_convocatoria()"
                                            class="form-control" ng-class="{'invalid_control': convocatoria_invalido}"/>
                                        </div>
                                    </div> 
                                    
                                    {{--Año de la convocatoria--}}
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="anio_convocatoria">Año de la convocatoria <span class="error-text" ng-show="anio_convocatoria_invalido">Valor inválido</span></label>
                                            <input type="number" name="anio_convocatoria" id="anio_convocatoria" ng-model="proyecto.anio_convocatoria" ng-change="validar_anio_convocatoria()" 
                                            class="form-control" ng-class="{'invalid_control': anio_convocatoria_invalido}"/>
                                        </div>
                                    </div> 
                                </div>
                    		</fieldset> {{--datos básicos--}}
                    		
                    		<br />
                    		
                    		<!--objetivos-->
                    		<fieldset>
                    			<legend>Objetivos</legend>
                    			<div class="row">
                    			    
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="objetivo_general">Objetivo general <span class="error-text" ng-show="objetivo_general_invalido">Longitud mínima de 5 caracteres y máxima de 200</span></label>
                                            <input type="text" name="objetivo_general" id="objetivo_general" 
                                            ng-model="proyecto.objetivo_general" ng-change="validar_objetivo_general()"
                                            class="form-control" ng-class="{'invalid_control': objetivo_general_invalido}"/>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12">&nbsp;</div>
                                    
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <label>Objetivos específicos</label>
                                            </div>
                                            
                                            <div class="col-xs-12 col-sm-6">
                                                <p class="text-right">
                                                    <button type="button" class="btn btn-primary" ng-class="{'btn-block': windowInnerWidth < 992}" ng-click="add_objetivo_especifico()">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar objetivo específico
                                                    </button>
                                                </p>
                                            </div>
                                        </div>
                                        <input type="hidden" name="cantidad_objetivos_especificos" value="{$ objetivos_especificos.length $}"/>
                                        <div ng-hide="true" id="objetivos_especificos_a_eliminar">
                                            {{--Contenedor de inputs hidden que se encargan de identificar los objetivos espf. "viejos" a eliminar--}}
                                        </div>                                                                                            
                                        <div style="max-height:280px; overflow-y:auto;" id="contenedor_objetivos_esp">

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="objetivo_especifico in objetivos_especificos" class="nga-fast nga-stagger-fast nga-fade">
                                                        
                                                        {{--Presenta los inputs correspondientes a los objetivos especificos "viejos" o bien, aquellos que ya existen en la BD--}}
                                                        <td ng-if="objetivo_especifico.id != null">
                                                            <span class="error-text" ng-show="objetivo_especifico.invalido">Longitud mínima de 5 caracteres y máxima de 200</span>
                                                            <input type="text" class="form-control" name="objetivo_especifico_viejo[]"
                                                            ng-model="objetivo_especifico.nombre" ng-change="validar_objetivos_especificos(objetivo_especifico)"
                                                            ng-class="{'invalid_control': objetivo_especifico.invalido}"/>
                                                            
                                                            <input type="hidden" name="id_especifico_viejo[]" value="{$ objetivo_especifico.id $}" />
                                                        </td>
                                                        
                                                        {{--Presenta los inputs correspondientes a los objetivos especificos "nuevos" o bien, aquellos que apenas se agregarán a la BD--}}
                                                        <td ng-if="objetivo_especifico.id == null">
                                                            <span class="error-text" ng-show="objetivo_especifico.invalido">Longitud mínima de 5 caracteres y máxima de 200</span>
                                                            <input type="text" class="form-control" name="objetivo_especifico_nuevo[]"
                                                            ng-model="objetivo_especifico.nombre" ng-change="validar_objetivos_especificos(objetivo_especifico)"
                                                            ng-class="{'invalid_control': objetivo_especifico.invalido}"/>
                                                        </td>
                                                        
                                                        <td>
                                                            <button type="button" class="btn btn-default" ng-click="eliminar_objetivo_especifico(objetivo_especifico)">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr ng-show="data.objetivos_especificos.length==0" class="nga-fast nga-stagger-fast nga-fade">
                                                        <td colspan="2"><p class="text-center"><strong>Sin objetivos específicos, se requiere al menos un objetivo específico</strong></p></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                    			</div>
                    		</fieldset> {{--objetivos--}}
                    		
                    		<hr />
                    		
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <button type="button" class="btn btn-primary btn-block" ng-click="validar_info_general()" >Guardar cambios&nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                    <input type="submit" id="input_editar_proyecto" ng-hide="true"/>
                                </div>
                                <div class="col-xs-12 hidden-md hidden-lg">&nbsp;</div>
                                <div class="col-xs-12 col-md-4">
                                    <a class="btn btn-default btn-block" href="/proyectos/listar">Cancelar</a>
                                </div>
                            </div>                    		
                    		
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
        sgpi_app.value('id_usuario', {{ Auth::user()->id }});
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

