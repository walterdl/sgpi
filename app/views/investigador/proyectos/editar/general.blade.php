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
                <a><b>Editar proyecto</b></a>
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
                        
                        {{--Contenito tab datos básicos--}}
                        <div id="contenido_info_general" class="tab-pane fade active in">
                            <br />
                            <div class="container-sgpi">
                                
                                <!--datos básicos-->
                                <fieldset>
                        			<legend>Datos básicos</legend>
                                    <div class="row is-flex">
                                        
                                        {{--Cidigo FMI--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="codigo_fmi">Código FMI <span class="error-text" ng-show="data.validacion_codigo_fmi != null">{$ data.validacion_codigo_fmi $}</span></label>
                                                <input type="text" name="codigo_fmi" id="codigo_fmi" ng-model="data.proyecto.codigo_fmi" ng-change="validar_codigo_fmi()" class="form-control" ng-class="{'invalid_control': data.validacion_codigo_fmi != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Subcentro de costo--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="subcentro_costo">Subcentro de costo <span class="error-text" ng-show="data.validacion_subcentro_costo != null">{$ data.validacion_subcentro_costo $}</span></label>
                                                <input type="text" name="subcentro_costo" id="subcentro_costo" ng-model="data.proyecto.subcentro_costo" ng-change="validar_subcentro_costo()" class="form-control" ng-class="{'invalid_control': data.validacion_subcentro_costo != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Nombre del proyecto--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="nombre_proyecto">Nombre del proyecto <span class="error-text" ng-show="data.validacion_nombre_proyecto != null">{$ data.validacion_nombre_proyecto $}</span></label>
                                                <input type="text" name="nombre_proyecto" id="nombre_proyecto" ng-model="data.proyecto.nombre" ng-change="validar_nombre_proyecto()" class="form-control" ng-class="{'invalid_control': data.validacion_nombre_proyecto != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Fecha de inicio del proyecto--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="fecha_inicio">Fecha de inicio del proyecto <span class="error-text" ng-show="data.validacion_fecha_inicio != null">{$ data.validacion_fecha_inicio $}</span></label>
                                            <div class="input-group">
                                                <input type="text" name="fecha_inicio" id="fecha_inicio" 
                                                    ng-model="data.proyecto.fecha_inicio" 
                                                    is-open="visibilidad.show_datepicker_fecha_inicio"
                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd"
                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                    ng-click="visibilidad.show_datepicker_fecha_inicio=true"
                                                    ng-readonly="true"
                                                    class="form-control white-readonly" ng-class="{'invalid_control': data.validacion_fecha_inicio != null}"/>
                                                <span class="input-group-addon btn btn-default" ng-click="visibilidad.show_datepicker_fecha_inicio=true">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                            </div>
                                        </div> 
                                        
                                        {{--Duración en meses del proyecto--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="duracion_meses">Duración en meses del proyecto <span class="error-text" ng-show="data.validacion_duracion_meses != null">{$ data.validacion_duracion_meses $}</span></label>
                                                <input type="number" min="12" name="duracion_meses" id="duracion_meses" ng-model="data.proyecto.duracion_meses" ng-change="calcular_fecha_final()" class="form-control" ng-class="{'invalid_control': data.validacion_duracion_meses != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Fecha final del proyecto aproximada--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="fecha_final">Fecha final del proyecto</label>
                                            <div class="input-group">
                                                <input type="text" name="fecha_final" id="fecha_final" class="form-control white-readonly" 
                                                    ng-model="data.proyecto.fecha_fin" is-open="visibilidad.show_datepicker_fecha_final"
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
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="convocatoria">Convocatoria</label>
                                                <input type="text" name="convocatoria" id="convocatoria" ng-model="data.proyecto.convocatoria" class="form-control"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Año de la convocatoria--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-group">
                                                <label for="anio_convocatoria">Año de la convocatoria</label>
                                                <input type="number" min="2000" name="anio_convocatoria" id="anio_convocatoria" ng-model="data.proyecto.anio_convocatoria" class="form-control"/>
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
                                                <label for="objetivo_general">Objetivo general <span class="error-text" ng-show="data.validacion_objetivo_general != null">{$ data.validacion_objetivo_general $}</span></label>
                                                <input type="text" name="objetivo_general" id="objetivo_general" 
                                                ng-model="data.proyecto.objetivo_general" ng-change="validar_objetivo_general()"
                                                class="form-control" ng-class="{'invalid_control': data.validacion_objetivo_general != null}"/>
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
                                            <div style="max-height:280px; overflow-y:auto;" id="contenedor_objetivos_esp">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="objetivo_especifico in data.proyecto.objetivos_especificos" class="nga-fast nga-stagger-fast nga-fade">
                                                            <td>
                                                                <span class="error-text" ng-show="objetivo_especifico.validacion != null">{$ objetivo_especifico.validacion $}</span>
                                                                <input type="text" class="form-control" name="objetivo_especifico_{$ $index $}"
                                                                ng-model="objetivo_especifico.nombre" ng-change="validar_objetivos_especificos(objetivo_especifico)"
                                                                ng-class="{'invalid_control': objetivo_especifico.validacion != null}"/>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-default" ng-click="eliminar_objetivo_especifico(objetivo_especifico)">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr ng-show="data.objetivos_especificos.length==0" class="nga-fast nga-stagger-fast nga-fade">
                                                            <td colspan="2"><p class="text-center"><strong>Sin objetivos específicos, agregar al menos uno</strong></p></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="cantidad_objetivos_especificos" value="{$ data.objetivos_especificos.length $}"/>
                                            </div>
                                        </div>
                        			</div>
                        		</fieldset> {{--objetivos--}}
                        		
                        		<hr />
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        
                                        <!-- Boton de editar   ng-click="validar_info_general()"-->
                                        <button type="button" class="btn btn-primary" >Guardar Cambios&nbsp;</button>
                                    </div>
                                </div>                    		
                        		
                            </div> 
                        </div> {{--Contenito tab datos básicos--}}
                        
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
