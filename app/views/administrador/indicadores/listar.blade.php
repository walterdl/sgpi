@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style>
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
            background: rgba(255, 255, 255, 0.7);
            border-radius: 3px;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;        
        }    
        input[type="radio"]{
            height: 15px;
            width: 15px;
        }
        .wrap-text{
            white-space: normal;
        }
        .tooltip.tooltip-invalid_control .tooltip-inner {
            color: white;
            background-color: #8B0000;
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
        }        
        #contenedor_proyectos{
            position: relative;
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
                <a href="#"><b>Indicadores</b></a>
            </li>
        </ol>
        <br />
    </section>
    
    {{--contenido--}}
    <section class="content" ng-cloak ng-controller="general_controller">
        <div class="box">
            <div class="box-header with-border">
                <h3>Indicadores de proyectos de investigación</h3>
            </div>
            <div class="box-body">
                <div class="row is-flex" ng-show="proyectos.length>0">
                    <div class="col-xs-12 col-sm-6">
                        <div id="proyectos_final_aprobado" style="width: 100%; height:370px;"></div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div id="totaL_mujeres_hombres" style="width: 100%; height:370px;"></div>
                    </div>               
                    <div class="col-xs-12 col-sm-6">
                        <div id="proys_x_clasificacion" style="width: 100%; height:370px;"></div>
                    </div>      
                    <div class="col-xs-12 col-sm-6">
                        <div id="proys_x_area" style="width: 100%; height:370px;"></div>
                    </div>
                </div>
                <h4 ng-show="proyectos.length==0">Sin proyectos de investigación, no se pueden mostrar indicadores</h4>
                <br />
                <fieldset>
                    <legend>Proyectos de investigación</legend>
                    <p>Filtrar búsqueda de proyectos de investigación por:</p>
                    <div class="row is-flex">
                        <div class="col-xs-12 col-md-3">
                            <div class="radio text-center">
                                <label>
                                    <input type="radio" name="radios_filtros" ng-click="cambia_tipo_filtro(null)" ng-checked="true">
                                    Todos los proyectos
                                </label>
                            </div>                                                    
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="radio text-center">
                                <label>
                                    <input type="radio" name="radios_filtros" ng-click="cambia_tipo_filtro('sede')">
                                    Sede
                                </label>
                            </div>                        
        					<ui-select theme="bootstrap"
        					ng-model="data.sede_seleccionada" ng-change="cambia_filtro_sede()"
        					ng-class="{'invalid_control': filtro_sede_invalido}"
        					uib-tooltip="Seleccione una sede válida" tooltip-enable="filtro_sede_invalido" tooltip-class="tooltip-invalid_control"
        					ng-disabled="filtro_sedes_deshabilitado">
        						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
        						<ui-select-choices repeat="item in sedes_ucc | filter: $select.search">
        							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
        						</ui-select-choices>
        					</ui-select>                            
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="radio text-center">
                                <label>
                                    <input type="radio" name="radios_filtros" ng-click="cambia_tipo_filtro('facultad')">
                                    Facultad / dependencia
                                </label>
                            </div>                        
        					<ui-select theme="bootstrap"
        					ng-model="data.facultad_seleccionada" ng-change="cambia_filtro_facultad()"
        					ng-class="{'invalid_control': filtro_facultad_invalido}"
        					uib-tooltip="Seleccione una facultad válida" tooltip-enable="filtro_facultad_invalido" tooltip-class="tooltip-invalid_control"        					
        					ng-disabled="filtro_facultades_deshabilitado">
        						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
        						<ui-select-choices repeat="item in facultades_correspondientes | filter: $select.search">
        							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
        						</ui-select-choices>
        					</ui-select>                            
                        </div>                        
                        <div class="col-xs-12 col-md-3">
                            <div class="radio text-center">
                                <label>
                                    <input type="radio" name="radios_filtros" ng-click="cambia_tipo_filtro('grupo')">
                                    Grupo de investigación
                                </label>
                            </div>                        
        					<ui-select theme="bootstrap"
        					ng-model="data.grupo_investigacion_seleccionado" ng-change="validar_filtro_grupo_investigacion()"
        					ng-class="{'invalid_control': filtro_grupo_invalido}"
        					uib-tooltip="Seleccione una grupo de investigación válido" tooltip-enable="filtro_grupo_invalido" tooltip-class="tooltip-invalid_control"        					        					
        					ng-disabled="filtro_grupos_deshabilitado">
        						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
        						<ui-select-choices repeat="item in grupos_inv_correspondientes | filter: $select.search">
        							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
        						</ui-select-choices>
        					</ui-select>                            
                        </div>                             
                        <div class="col-xs-12">&nbsp;</div>
                        <div class="col-xs-12 col-md-3">
                            <button class="btn btn-primary wrap-text" ng-click="filtrar_proyectos()" ng-disabled="consultado_proyectos">Filtrar y buscar proyectos <i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive" id="contenedor_proyectos">
                        <table datatable="ng" dt-options="dtOptions" class="table table-hover table-stripped table-bordered">
                            <thead>
                                <tr>
                                    <th>Código FMI</th>
                                    <th class="no-wrap">Subcentro de costo</th>
                                    <th class="no-wrap">Nombre de proyecto</th>
                                    <th class="no-wrap">Grupo de investigación ejecutor</th>
                                    <th>Duración (meses)</th>
                                    <th>Más información</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="proyecto in proyectos">
                                    <td>{$ proyecto.codigo_fmi $}</td>
                                    <td>{$ proyecto.subcentro_costo $}</td>
                                    <td>{$ proyecto.nombre_proyecto $}</td>
                                    <td>{$ proyecto.nombre_grupo_inv_principal $}</td>
                                    <td>{$ proyecto.duracion_meses $}</td>
                                    <td><button type="button" class="btn btn-default" ng-click="mas_info(proyecto.id)"><i class="fa fa-info-circle" aria-hidden="true"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        {{--Overlay interno de tab productos--}}
                        <div class="overlay-2" ng-show="show_velo_tabla_proyectos">
                            <div style="display:table; width:100%; height:100%;">
                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_velo_tabla_proyectos">
                                    <!--Contenido definido dinámicamente desde controlador-->
                                </div>
                            </div>                                
                        </div>                        
                    </div>                    
                </fieldset>
                <fieldset ng-controller="mas_info_proyecto_controller" style="position: relative;">
                    <legend>Detalles de proyecto</legend>
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
                    <div class="overlay-2" ng-show="visibilidad.show_velo_mas_info_proy">
                        <div style="display:table; width:100%; height:100%;">
                            <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_mas_info_proy">
                                <!--Contenido definido dinámicamente desde controlador-->
                            </div>
                        </div>    
                    </div>                      
                </fieldset>
            </div>
            
           {{--Overlay o velo general--}}
           <div class="overlay" ng-show="show_velo_general">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_velo_general">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>    
            </div>                
        </div>
    </section>    

@stop {{--Stop section 'contenido'--}}

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
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
