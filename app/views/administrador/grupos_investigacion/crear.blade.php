@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
@stop <!--Stop section 'styles'-->

@section('pre_scripts')
    @if(isset($pre_scripts))
        @foreach($pre_scripts as $script) 
            <script type="text/javascript" src="/{{ $script }}"></script>
        @endforeach
    @endif
@stop <!--Stop section 'pre_scripts'-->

@section('contenido')
    
    <style type="text/css">
        .ajs-message.ajs-notify { 
            color: white;
            font-weight: bold;
            background-color: rgba(53, 147, 210, .8);  
            border-color: #31708f; 
        }
        .invalid_control{
            box-shadow: 0 1px 1px #8B0000 inset, 0 0 8px red;
            outline: 0 none;
        }
    </style>
    
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i><b></b></a></li> <i class="fa fa-chevron-right" aria-hidden="true"></i> <li><a href="/grupos/listar"><b>Grupos de investigación</b></a></li>
            <i class="fa fa-chevron-right" aria-hidden="true"></i> Registrar
        </ol>
        <br />
    </section>
    <section class="content" ng-cloak>
        <div class="box" ng-controller="crear_grupos_investigacion_controller">
            <div class="box-header with-border">
                <h3>Registrar nuevo grupo de investigación</h3>
                <div class="alert alert-danger" role="alert" ng-show="visibilidad.error_carga_data_inicial">{$ mensaje_error_carga_inicial $}</div>
            </div>
            <div class="box-body">
                <form action="/grupos/guardar_nuevo_grupo_inv" method="POST" name="form_nuevo_grupo_inv">
                    <div class="container-sgpi">
                    <!--<div class="container">-->
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 altura_74">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_nombre_invalido">Campo requerido</span>
                                    <input class="form-control" type="text" name="nombre" id="nombre" ng-model="data.nombre" ng-required="true" ng-change="validar_nombre()" 
                                    ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_nombre_invalido}"
                                    ng-disabled="visibilidad.error_carga_data_inicial"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 altura_74">
                                <div class="form-group">
                                    <label for="gran_area">Gran area</label> <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_gran_area_invalido">Campo requerido</span>
                                    <ui-select ng-model="data.gran_area" theme="bootstrap" ng-change="cambia_gran_area()"  
                                    ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_gran_area_invalido}"
                                    ng-disabled="visibilidad.error_carga_data_inicial">
                                    	<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                    	<ui-select-choices repeat="item in data.gran_areas | filter: $select.search">
                                    		<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                    	</ui-select-choices>
                                    </ui-select>
                                    <input type="hidden" name="gran_area" value="{$ data.gran_area.id $}"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 altura_74">
                                <div class="form-group">
                                    <label for="area">Area</label> <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_area_invalido">Campo requerido</span>
                                    <ui-select ng-model="data.area" theme="bootstrap" ng-change="validar_area()" ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_area_invalido}"
                                    ng-disabled="visibilidad.error_carga_data_inicial">
                                    	<ui-select-match placeholder="Seleccione..." allow-clear="true">{$ $select.selected.nombre $}</ui-select-match>
                                    	<ui-select-choices repeat="item in data.areas | filter: $select.search">
                                    		<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                    	</ui-select-choices>
                                    </ui-select>
                                    <input type="hidden" name="area" value="{$ data.area.id $}"/>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 altura_74">
                                <div class="form-group">
                                    <label for="area">Clasificación</label> <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_clasif_grupo_inv_invalido">Campo requerido</span>
                                    <ui-select ng-model="data.clasificacion_grupo_inv" theme="bootstrap" ng-change="validar_clasif_grupo_inv()" ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_clasif_grupo_inv_invalido}"
                                    ng-disabled="visibilidad.error_carga_data_inicial">
                                    	<ui-select-match placeholder="Seleccione..." allow-clear="true">{$ $select.selected.nombre $}</ui-select-match>
                                    	<ui-select-choices repeat="item in data.clasificaciones_grupos_inv | filter: $select.search">
                                    		<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                    	</ui-select-choices>
                                    </ui-select>
                                    <input type="hidden" name="clasificacion_grupo_inv" value="{$ data.clasificacion_grupo_inv.id $}"/>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <br />
                            </div>
                            <div class="col-xs-12 col-sm-6" >
                                <label for="lineas_investigacion">Líneas de investigación <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> <span ng-show="data.lineas_investigacion-length==0">(Sin registros)</span></label> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_lineas_inv_invalido">Campo requerido</span>
                                <ui-select id="lineas_investigacion" multiple ng-model="data.lineas_investigacion_seleccionadas" theme="bootstrap" 
                                close-on-select="true" style="width: 100%;" title="Líneas de investigación" search-enabled="true" on-select="seleccion_linea_inv($item)" on-remove="eliminacion_linea_inv($item)"
                                ng-change="validar_lineas_inv()" ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_lineas_inv_invalido}"
                                ng-disabled="visibilidad.error_carga_data_inicial">
                                    <ui-select-match placeholder="Seleccione...">{$ $item.nombre $}</ui-select-match>
                                    <ui-select-choices repeat="linea_inv in data.lineas_investigacion">
                                        {$ linea_inv.nombre $}
                                    </ui-select-choices>
                                </ui-select>
                                <div id="inputs_lineas_inv_seleccionadas" ng-hide="true">
                                    
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row" style="margin:0;">
                                    <div class="col-xs-12 col-md-7" id="col_otra_linea" style="padding:0;">
                                        <label for="nueva_linea_investigacion">Otra línea de investigción</label>
                                        <input type="text" ng-model="data.nueva_linea_investigacion" class="form-control"/>
                                    </div>
                                    <div class="col-xs-12 col-md-5" style="padding:0;">
                                        <button type="button" id="btn_add_otra_linea" class="btn btn-default btn-block" ng-click="btn_add_linea_inv_click()" ng-disabled="data.nueva_linea_investigacion==null || data.nueva_linea_investigacion.length==0 || visibilidad.error_carga_data_inicial">Agregar <i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <br />
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label for="sede">Sede UCC: <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> </label> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_sede_invalido">Campo requerido</span>
                                <ui-select id="sede_select" ng-model="data.sede" ng-change="cambia_sede()" ng-change="cambiaSede()" ng-required="true" 
                                theme="bootstrap" ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_sede_invalido}"
                                ng-disabled="visibilidad.error_carga_data_inicial">
                                	<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                	<ui-select-choices repeat="item in data.sedes | filter: $select.search">
                                		<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                	</ui-select-choices>
                                </ui-select>
                                <input type="hidden" name="sede" value="{$ data.sede.id $}"/>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <label for="sede">Facultad / dependencia: <i ng-show="visibilidad.cargando_data_inicial" class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i> </label> <span style="color:rgb(139,0,0);" ng-show="btn_guardar_seleccionado && visibilidad.show_facultad_invalido">Campo requerido</span>
                                <ui-select ng-model="data.facultad" theme="bootstrap" ng-change="validar_facultad()" ng-class="{'invalid_control': btn_guardar_seleccionado && visibilidad.show_facultad_invalido}"
                                ng-disabled="visibilidad.error_carga_data_inicial">
                                	<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                	<ui-select-choices repeat="item in data.facultades_correspondientes | filter: $select.search">
                                		<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                	</ui-select-choices>
                                </ui-select>
                                <input type="hidden" name="facultad" value="{$ data.facultad.id $}"/>
                            </div>
                        </div>
                        <hr />
                        <button type="button" class="btn btn-primary" ng-disabled="visibilidad.show_procesando_envio || visibilidad.error_carga_data_inicial" ng-click="btn_guardar_click()">Guardar </button> <span ng-show="visibilidad.show_procesando_envio">Procesando...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></span>
                        <input type="submit" name="guardar" ng-hide="true" id="guardar"/>
                    </div>
                </form>
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