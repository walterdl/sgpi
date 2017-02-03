@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style>
        .file-input{
            min-width: 230px;
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
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i></a></li>
            <i class="fa fa-chevron-right" aria-hidden="true"></i> Gestión de formatos de documentos
        </ol>
        <br />
    </section>
    
    <section class="content" ng-controller="formatos_tipos_documentos_controller" ng-cloak>
        <div class="box">
            <div class="box-header with-border">
                <h3>Gestión de formatos de tipos de documentos</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive" id="contenedor_tabla_formatos">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Formato</th>
                                <th>Descargar formato actual</th>
                                <th>Cargar nuevo formato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Presupuesto</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=presupuesto" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_presupuesto" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('presupuesto')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': presupuesto_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('presupuesto')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                    
                                    <p ng-show="cargando_archivo_presupuesto" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_presupuesto" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>
                                </td>
                            </tr>
                            <tr>
                                <td>Presentacion de proyecto</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=presentacion_proyecto" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_ppt_proyecto" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('presentacion_proyecto')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': ppt_proyecto_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('presentacion_proyecto')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>                                    	
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_ppt_proyecto" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_ppt_proyecto" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>                            
                            <tr>
                                <td>Acta de inicio</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=acta_inicio" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_acta_inicio" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('acta_inicio')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': acta_inicio_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('acta_inicio')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_acta_inicio" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_acta_inicio" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>                                                        
                            <tr>
                                <td>Informe de avance</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=informe_avance" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_informe_avance" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('informe_avance')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': informe_avance_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('informe_avance')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_informe_avance" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_informe_avance" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>    
                            <tr>
                                <td>Desembolso</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=desembolso" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_desembolso" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('desembolso')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': desembolso_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('desembolso')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_desembolso" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_desembolso" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>                                
                            <tr>
                                <td>Memoria académica</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=memoria_academica" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_memoria_academica" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('memoria_academica')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': memoria_academica_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('memoria_academica')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_memoria_academica" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_memoria_academica" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>   
                            <tr>
                                <td>Acta de finalización</td>
                                <td>
                                    <a href="/file/formato?nombre_formato=acta_finalizacion" class="btn btn-primary btn-block">
                                        Formato actual <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="file"
                                        	ngf-select ng-model="archivo_acta_finalizacion" ngf-max-size="20MB"
                                        	ngf-change="validar_archivo('acta_finalizacion')"
                                        	ng-disabled="cargando_algun_archivo"
                                        	class="form-control btn btn-default file-input" ng-class="{'invalid_control': acta_finalizacion_invalido}"
                                        	uib-tooltip="El archivo a cargar debe tener un tamaño máximo de 20MB" tooltip-enable="true"/>
                                    	<span class="input-group-btn">
                                    		<button class="btn btn-primary btn-block" ng-click="cargar_archivo('acta_finalizacion')" ng-disabled="cargando_algun_archivo">Cargar <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
                                    	</span>                                        	
                                    </div>                                                                        
                                    <p ng-show="cargando_archivo_acta_finalizacion" class="nga-fast nga-stagger-fast nga-fade">Progreso de carga del archivo:</p>
                                    <uib-progressbar ng-show="cargando_archivo_acta_finalizacion" max="total_archivo" value="carga_actual" type="info" class="nga-fast nga-stagger-fast nga-fade">
                                        <i>{$ porcentaje_carga $}%</i>
                                    </uib-progressbar>                                    
                                </td>
                            </tr>                               
                        </tbody>
                    </table>
                </div>
            </div>
    		<div class="overlay" ng-show="show_velo_general">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_velo_general">
            			<!--Contenido generado desde crear_usuarios_controller-->
                    </div>
                </div>    		    
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