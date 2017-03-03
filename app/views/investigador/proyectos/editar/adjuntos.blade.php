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
        input[type="checkbox"]{
            width: 20px;
            height: 20px;
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
                Editar documentos iniciales
            </li>
        </ol>
        <br />
    </section>

    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_adjuntos_controller">
        
        <form action="/proyectos/editar/adjuntos" method="POST" enctype='multipart/form-data'>
            
            <input type="hidden" name="id_proyecto" value="{{ $id_proyecto }}">
        
            <div class="box">
                <div class="box-header with-border">
                    <h3>Edición de documentos iniciales de proyecto</h3>
                </div>
                <div class="box-body">
                        
                    {{--Información del proyecto--}}
                    <div class="callout callout-info">
                    	<h4>{$ info_proyecto.nombre_proyecto $}</h4>
                    	<p>
                    		Grupo de investigación: <strong>{$ info_proyecto.grupo_investigacion_ejecutor $} - {$ info_proyecto.facultad $} - Sede {$ info_proyecto.sede $}</strong> 
                    	</p>
                    	<p>
                    	    Investigador principal: <strong>{$ info_proyecto.nombre_completo_investigador_principal $}</strong>
                    	</p>
                    </div>                

                    <div class="container-sgpi">
                        
                        {{--documento de presupuesto--}}
                        <div class="panel panel-default" style="box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Documento de presupuesto para proyecto de investigación
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="documento_presupuesto">Seleccionar para editar el documento</label>
                                            <div class="checkbox">
                                                <label style="font-size: inherit;">
                                                    <input type="checkbox" ng-model="editar_presupuesto">&nbsp;&nbsp;&nbsp;Cargar nuevo documento de presupuesto
                                                </label>
                                            </div>                                                                                        
                                        </div>
                                        <div class="form-group">
                                            <label for="documento_presupuesto">Cargar aquí el documento de presupuesto</label>
                                            <input id="presupuesto" type="file" name="presupuesto"
                                            	ngf-select ngf-max-size="20MB"
                                            	ng-model="documento_presupuesto"
                                            	ngf-change="validar_documento_presupuesto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control btn btn-default" ng-class="{'invalid_control': documento_presupuesto_invalido}"
                                            	ng-disabled="!editar_presupuesto"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="presupuesto_actual">Descargue aquí el documento de presupuesto actual</label>
                                            <a href="/file/presupuesto/{$ presupuesto $}" class="btn btn-primary btn-block text-center">Descargar presupuesto actual&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="form-group">
                                            <label for="formato_documento_presupuesto">Descargue el formato del documento de presupuesto</label>
                                            <a href="/file/formato?nombre_formato=presupuesto" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>                                
                        </div>{{--documento de presupuesto--}}
                        
                        {{--documento de presentacion de proyecto--}}
                        <div class="panel panel-default" style="box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Documento de presentación de proyecto de investigación
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="documento_presentacion_proyecto">Seleccionar para editar el documento</label>
                                            <div class="checkbox">
                                                <label style="font-size: inherit;">
                                                    <input type="checkbox" ng-model="editar_presentacion_proyecto">&nbsp;&nbsp;&nbsp;Cargar nuevo doc. presentación de proyecto
                                                </label>
                                            </div>                                                                                        
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="documento_presentacion_proyecto">Cargar aquí el documento de presentación</label>
                                            <input id="presentacion_proyecto" type="file" name="presentacion_proyecto"
                                            	ngf-select ng-model="documento_presentacion_proyecto" ngf-max-size="20MB"
                                            	ngf-change="validar_documento_presentacion_proyecto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control btn btn-default" ng-class="{'invalid_control': documento_presentacion_proyecto_invalido}"
                                            	ng-disabled="!editar_presentacion_proyecto"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="presentacion_proyecto">Descargue aquí el documento de presentación de proyecto actual</label>
                                            <a href="/file/presupuesto/{$ presentacion_proyecto $}" class="btn btn-primary btn-block text-center">Descargar presentación de proyecto actual&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="formato_documento_presentacion_proyecto">Descargue el formato del documento de presentación</label>
                                            <a href="/file/formato?nombre_formato=presentacion_proyecto" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>                                
                        </div>{{--documento de presentacion de proyecto--}}                            
                        
                        {{--documento de acta de inicio--}}
                        <div class="panel panel-default" style="box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    Documento de acta de inicio de proyecto
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="documento_acta_inicio">Seleccionar para editar el documento</label>
                                            <div class="checkbox">
                                                <label style="font-size: inherit;">
                                                    <input type="checkbox" ng-model="editar_acta_inicio">&nbsp;&nbsp;&nbsp;Cargar nueva acta de inicio
                                                </label>
                                            </div>                                                                                        
                                        </div>                                                                                
                                        <div class="form-group">
                                            <label for="documento_acta_inicio">Cargar aquí el documento de acta de inicio</label>
                                            <input id="acta_inicio" type="file" name="acta_inicio"
                                            	ngf-select ngf-max-size="20MB"
                                            	ng-model="documento_acta_inicio"
                                            	ngf-change="validar_documento_acta_inicio($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control btn btn-default" ng-class="{'invalid_control': documento_acta_inicio_invalido}"
                                            	ng-disabled="!editar_acta_inicio"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="acta_inicio">Descargue aquí el documento de acta de inicio actual</label>
                                            <a href="/file/presupuesto/{$ acta_inicio $}" class="btn btn-primary btn-block text-center">Descargar acta de inicio actual&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>                                                                                
                                        <div class="form-group">
                                            <label for="formato_documento_acta_inicio">Descargue el formato del acta de inicio</label>
                                            <a href="/file/formato?nombre_formato=acta_inicio" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>                                
                        </div>{{--documento de acta de inicio--}}
                        
                    </div>
                    
                    <br />
                    <hr />
                    
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <button type="button" class="btn btn-primary btn-block" ng-click="guardar()">Guardar cambios&nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                            <input type="submit" id="input_registrar_proyecto" ng-hide="true"/>
                        </div>
            	        <div class="col-xs-12 visible-xs-block">&nbsp;</div>                    		                                                                        
            	        <div class="col-xs-12 col-sm-6 col-md-4">
            	            <a href="/proyectos/listar" type="button" class="btn btn-default btn-block" ng-click="regresar_gastos()">
            	                Cancelar
            	            </a>
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
        sgpi_app.value('acta_inicio', {{ json_encode($acta_inicio) }});
        sgpi_app.value('presentacion_proyecto', {{ json_encode($presentacion_proyecto) }});
        sgpi_app.value('presupuesto', {{ json_encode($presupuesto) }});
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
