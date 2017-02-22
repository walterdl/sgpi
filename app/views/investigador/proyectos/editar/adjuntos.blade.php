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
                Editar documentos iniciales
            </li>
        </ol>
        <br />
    </section>

    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_adjuntos_controller">
        
        <form action="/proyectos/editar/adjuntos" method="POST" enctype='multipart/form-data'>
        
            <div class="box">
                <div class="box-header with-border">
                    <h3>Edición de documentos iniciales de proyecto</h3>
                </div>
                <div class="box-body">    

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
                                            <label for="documento_presupuesto">Cargar aquí el documento de presupuesto</label>
                                            <input id="presupuesto" type="file" name="presupuesto"
                                            	ngf-select ngf-pattern=".xlsx,.xltm,.xlsm,.xls"
                                            	ng-model="data.documento_presupuesto"
                                            	ngf-change="cambia_documento_presupuesto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control" ng-class="{'invalid_control': data.documento_presupuesto_invalido}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="formato_documento_presupuesto">Descargue el formato del documento de presupuesto</label>
                                            <a href="/file/formato/presupuesto" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
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
                                            <label for="documento_presentacion_proyecto">Cargar aquí el documento de presentación</label>
                                            <input id="presentacion_proyecto" type="file" name="presentacion_proyecto"
                                            	ngf-select ngf-pattern=".doc,.docx,.dotx,.DOC,.DOCX"
                                            	ng-model="data.documento_presentacion_proyecto"
                                            	ngf-change="cambia_documento_presentacion_proyecto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control" ng-class="{'invalid_control': data.documento_presentacion_proyecto_invalido}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="formato_documento_presentacion_proyecto">Descargue el formato del documento de presentación</label>
                                            <a href="/file/formato/presentacion_proyecto" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
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
                                            <label for="documento_acta_inicio">Cargar aquí el documento de acta de inicio</label>
                                            <input id="acta_inicio" type="file" name="acta_inicio"
                                            	ngf-select ngf-pattern=".doc,.docx,.dotx,.DOC,.DOCX,.w"
                                            	ng-model="data.documento_acta_inicio"
                                            	ngf-change="cambia_documento_acta_inicio($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                            	class="form-control" ng-class="{'invalid_control': data.documento_acta_inicio_invalido}"/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label for="formato_documento_acta_inicio">Descargue el formato del acta de inicio</label>
                                            <a href="/file/formato/acta_inicio" class="btn btn-primary btn-block text-center">Descargar formato&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
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
            	            <button type="button" class="btn btn-default btn-block" ng-click="regresar_gastos()">
            	                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Regresar a gastos
            	            </button>
            	        </div>                        	        
            	        <div class="col-xs-12">&nbsp;</div>                    		                                                                        
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <button type="button" class="btn btn-primary btn-block" ng-click="registrar_proyecto()">Registrar proyecto&nbsp;<i class="fa fa-check" aria-hidden="true"></i></button>
                            <input type="submit" id="input_registrar_proyecto" ng-hide="true"/>
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
        id_proyecto="{{$proyecto_id}}";
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
