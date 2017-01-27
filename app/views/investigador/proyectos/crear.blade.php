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
                <a href="#"><b>Registrar proyecto</b></a>
            </li>
        </ol>
        <br />
    </section>
    
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="crear_proyecto_controller">
        
        <form action="/proyectos/registrar_nuevo_proyecto" method="POST" enctype='multipart/form-data'>
        
            <div class="box">
                <div class="box-header with-border">
                    <h3>Registrar proyecto</h3>
                </div>
                <div class="box-body">    
               
                   {{--Tabs--}}
                    <ul class="nav nav-tabs" id="tabs">
                        <li id="tab_info_general" role="presentation" class="active"
                        uib-tooltip="Navegar entre pestañas por los botones inferiores" tooltip-enable="true">
                            <a href="#contenido_info_general" disabled>Información general del proyecto</a>
                        </li>
                        <li id="tab_participantes" role="presentation" 
                        uib-tooltip="Navegar entre pestañas por los botones inferiores" tooltip-enable="true">
                            <a href="#contenido_participantes" disabled>Participantes del proyecto</a>
                        </li>      
                        <li id="tab_productos" role="presentation"
                        uib-tooltip="Navegar entre pestañas por los botones inferiores" tooltip-enable="true">
                            <a href="#contenido_productos" disabled>Productos del proyecto</a>
                        </li>                          
                        <li id="tab_gastos" role="presentation"
                        uib-tooltip="Navegar entre pestañas por los botones inferiores" tooltip-enable="true">
                            <a href="#contenido_gastos" disabled>Gastos del proyecto</a>
                        </li>
                        <li id="tab_adjuntos" role="presentation"
                        uib-tooltip="Navegar entre pestañas por los botones inferiores" tooltip-enable="true">
                            <a href="#contenido_adjuntos" disabled>Adjuntos del proyecto</a>
                        </li>                    
                    </ul> {{--Tabs--}}
                    
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
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="codigo_fmi">Código FMI <span class="error-text" ng-show="data.validacion_codigo_fmi != null">{$ data.validacion_codigo_fmi $}</span></label>
                                                <input type="text" name="codigo_fmi" id="codigo_fmi" ng-model="data.codigo_fmi" ng-change="validar_codigo_fmi()" class="form-control" ng-class="{'invalid_control': data.validacion_codigo_fmi != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Subcentro de costo--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="subcentro_costo">Subcentro de costo <span class="error-text" ng-show="data.validacion_subcentro_costo != null">{$ data.validacion_subcentro_costo $}</span></label>
                                                <input type="text" name="subcentro_costo" id="subcentro_costo" ng-model="data.subcentro_costo" ng-change="validar_subcentro_costo()" class="form-control" ng-class="{'invalid_control': data.validacion_subcentro_costo != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Nombre del proyecto--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="nombre_proyecto">Nombre del proyecto <span class="error-text" ng-show="data.validacion_nombre_proyecto != null">{$ data.validacion_nombre_proyecto $}</span></label>
                                                <input type="text" name="nombre_proyecto" id="nombre_proyecto" ng-model="data.nombre_proyecto" ng-change="validar_nombre_proyecto()" class="form-control" ng-class="{'invalid_control': data.validacion_nombre_proyecto != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Fecha de inicio del proyecto--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label for="fecha_inicio">Fecha de inicio del proyecto <span class="error-text" ng-show="data.validacion_fecha_inicio != null">{$ data.validacion_fecha_inicio $}</span></label>
                                            <div class="input-group">
                                                <input type="text" name="fecha_inicio" id="fecha_inicio" 
                                                    ng-model="data.fecha_inicio" ng-change="calcular_fecha_final()"
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
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="duracion_meses">Duración en meses del proyecto <span class="error-text" ng-show="data.validacion_duracion_meses != null">{$ data.validacion_duracion_meses $}</span></label>
                                                <input type="number" min="12" name="duracion_meses" id="duracion_meses" ng-model="data.duracion_meses" ng-change="calcular_fecha_final()" class="form-control" ng-class="{'invalid_control': data.validacion_duracion_meses != null}"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Fecha final del proyecto aproximada--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label for="fecha_final">Fecha final del proyecto</label>
                                            <div class="input-group">
                                                <input type="text" name="fecha_final" id="fecha_final" class="form-control white-readonly" 
                                                    ng-model="data.fecha_final" is-open="visibilidad.show_datepicker_fecha_final"
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
                                                <label for="convocatoria">Convocatoria</label>
                                                <input type="text" name="convocatoria" id="convocatoria" ng-model="data.convocatoria" class="form-control"/>
                                            </div>
                                        </div> 
                                        
                                        {{--Año de la convocatoria--}}
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label for="anio_convocatoria">Año de la convocatoria</label>
                                                <input type="number" min="2000" name="anio_convocatoria" id="anio_convocatoria" ng-model="data.anio_convocatoria" class="form-control"/>
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
                                                ng-model="data.objetivo_general" ng-change="validar_objetivo_general()"
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
                                                        <tr ng-repeat="objetivo_especifico in data.objetivos_especificos" class="nga-fast nga-stagger-fast nga-fade">
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
                                        <button type="button" class="btn btn-primary" ng-click="validar_info_general()">Continuar a participantes&nbsp;<i class="fa fa-chevron-circle-right" aria-hidden="true"></i></button>
                                    </div>
                                </div>                    		
                        		
                            </div> 
                        </div> {{--Contenito tab datos básicos--}}
                        
                        {{--Contenito tab participantes--}}
                        <div id="contenido_participantes" class="tab-pane fade" 
                        ng-controller="crear_participantes_proyectos_controller">
                        	<input type="hidden" name="cantidad_participantes" value="{$ data.participantes_proyecto.length $}"/>
                            <br />
                            <div class="container-sgpi">
                        		<br />
                        		
                        		<fieldset>
                        		    <legend>Búsqueda de datos por identificación</legend>
                        		    <div class="box nested-box">
                        		        <div class="box-body">
                	                        <div class="alert alert-info borde-rectangular" role="alert">
                                                <p class="text-left">
                                                    <i class="fa fa-id-card fa-2x" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                                                    <strong>Buscar primero identificación del la persona </strong> en la base de datos para recuperar posibles datos básicos.
                                                </p>
                                            </div>
                                    		<div class="row is-flex">
                                    		    <div class="col-xs-12 col-sm-6">
                                    		        <div class="form-group">
                                    		            <label for="buscar_id">Identificación <span style="color: #B22222;">{$ data.msj_label_busqueda_id $}</span></label>
                                    		            <div class="input-group">
                                                            <input id="input_buscar_id" type="number" min="1" ng-model="data.identificacion_a_buscar" class="form-control">
                                                            <span class="input-group-addon btn btn-default" ng-click="buscar_datos_x_id()"><i class="fa fa-search"></i></span>
                                                        </div>
                                    		        </div>
                                    		    </div>
                                    		    <div class="col-xs-12">&nbsp;</div>
                                    		</div>
                        		        </div>
                                		<div class="overlay" ng-show="visibilidad.velo_busqueda_id">
                                            <div style="display:table; width:100%; height:100%;">
                                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_busqueda_id">
                                        			<!--Contenido generado desde crear_usuarios_controller-->
                                                </div>
                                            </div>    		    
                                		</div>
                        		    </div>
                        		</fieldset> {{--Búsqueda de datos por identificación--}}
                        		
                        		<fieldset>
                        		    <legend>Edición de datos del participante</legend>
                        		    <div class="box nested-box">
                        		        <div class="box-body">
                                    		<div class="row is-flex">
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <button type="button" class="btn btn-default" style="white-space: normal;" tabindex="-1" ng-click="buscar_otra_id()">
                                    		            <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Buscar otra identificación
                                    		        </button>
                                    		    </div>
                                    		    <div class="col-xs-12">&nbsp;</div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for="nombres_nuevo_participante">
                                    		                Nombres <span style="color:#B22222;" ng-show="visibilidad.nombres_nuevo_participante_invalido">{$ data.msj_validacion_nombres_nuevo_participante $}</span>
                                    		            </label>
                                    		            <input type="text" id="nombres_nuevo_participante" ng-model="data.nombres_nuevo_participante" ng-change="validar_nombres_nuevo_participante()"
                                    		            class="form-control"
                                    		            ng-class="{'invalid_control': visibilidad.nombres_nuevo_participante_invalido}"
                                    		            ng-readonly="data.datos_basicos_persona_recuperados"/>
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for="apellidos_nuevo_participante">
                                    		                Apellidos <span style="color:#B22222;" ng-show="visibilidad.apellidos_nuevo_participante_invalido">{$ data.msj_validacion_apellidos_nuevo_participante $}</span>
                                    		            </label>
                                    		            <input type="text" id="apellidos_nuevo_participante" ng-model="data.apellidos_nuevo_participante" ng-change="validar_apellidos_nuevo_participante()"
                                    		            class="form-control"
                                    		            ng-class="{'invalid_control': visibilidad.apellidos_nuevo_participante_invalido}"
                                    		            ng-readonly="data.datos_basicos_persona_recuperados"/>
                                    		        </div>
                                    		     </div>                    		     
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for="identificacion_nuevo_participante">
                                    		                Identificación
                                    		            </label>
                                    		            <input type="number" min="1" id="identificacion_nuevo_participante" ng-model="data.identificacion_nuevo_participante" ng-change="validar_identificacion_nuevo_participante()"
                                    		            class="form-control"
                                    		            ng-readonly="true"/>
                                    		        </div>
                                    		     </div>                                                		     
                                				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                					<label for="formacion_nuevo_participante">
                                					    Formación <span style="color:#B22222;" ng-show="visibilidad.formacion_nuevo_participante_invalido">{$ data.msj_validacion_formacion_nuevo_participante $}</span>
                                					</label>
                                					<ui-select id="formacion_nuevo_participante" theme="bootstrap"
                                					ng-model="data.formacion_nuevo_participante" ng-change="validar_formacion_nuevo_participante()"
                                					ng-required="true" ng-disabled="data.datos_basicos_persona_recuperados"
                                					ng-class="{'invalid_control': visibilidad.formacion_nuevo_participante_invalido, 'white-readonly': data.datos_basicos_persona_recuperados}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected $}</ui-select-match>
                                						<ui-select-choices repeat="item in data.formaciones | filter: $select.search">
                                							<div ng-bind-html="item | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>
                                				</div>                             
                                    		     <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                					<label for="rol">
                                					    Rol en el proyecto <span style="color:#B22222;" ng-show="visibilidad.rol_nuevo_participante_invalido">Rol requerido. Elegir una opción</span>
                                					</label>
                                					<ui-select theme="bootstrap" 
                                					ng-model="data.rol_nuevo_participante" ng-change="cambia_rol_proyecto_nuevo_participante()"
                                					ng-required="true"
                                					ng-class="{'invalid_control': visibilidad.rol_nuevo_participante_invalido}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                						<ui-select-choices repeat="item in data.roles | filter: $select.search">
                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>                    		         
                                    		     </div>                            				
                                				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                					<label for="tipo_identificacion_nuevo_participante">
                                					    Tipo de identificación <span style="color:#B22222;" ng-show="visibilidad.tipo_id_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                                					</label>
                                					<ui-select theme="bootstrap"
                                					ng-model="data.tipo_identificacion_nuevo_participante" ng-change="validar_tipo_id_nuevo_participante()"
                                					ng-required="true" ng-disabled="data.datos_basicos_persona_recuperados"
                                					ng-class="{'invalid_control': visibilidad.tipo_id_nuevo_participante_invalido}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                						<ui-select-choices repeat="item in data.tipos_identificacion | filter: $select.search">
                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>
                                				</div>                                		     
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                					<label for="sexo">
                                					    Sexo <span style="color:#B22222;" ng-show="visibilidad.sexo_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                                					</label>
                                					<ui-select theme="bootstrap"  
                                					ng-model="data.sexo_nuevo_participante" ng-change="validar_sexo_nuevo_participante()"
                                					ng-require="true" ng-disabled="data.datos_basicos_persona_recuperados"
                                					ng-class="{'invalid_control': visibilidad.sexo_nuevo_participante_invalido}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                						<ui-select-choices repeat="item in data.sexos | filter: $select.search">
                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>
                                    		     </div>                    		                         		                                				
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for="edad_nuevo_participante">
                                    		                Edad <span style="color:#B22222;" ng-show="visibilidad.edad_nuevo_participante_invalido">Edad mínima de 10</span>
                                    		            </label>
                                    		            <input type="number" min="10" id="edad_nuevo_participante" ng-model="data.edad_nuevo_participante" ng-change="validar_edad_nuevo_participante()"
                                    		            class="form-control"
                                    		            ng-class="{'invalid_control': visibilidad.edad_nuevo_participante_invalido}"
                                    		            ng-readonly="data.datos_basicos_persona_recuperados"/>
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for="email_nuevo_participante">
                                    		                Email <span style="color:#B22222;" ng-show="visibilidad.email_nuevo_participante_invalido">Email inválido</span>
                                    		            </label>
                                    		            <input type="email" id="email_nuevo_participante" ng-model="data.email_nuevo_participante" ng-change="validar_email_nuevo_participante()"
                                    		            class="form-control"
                                    		            ng-class="{'invalid_control': visibilidad.email_nuevo_participante_invalido}"/>
                                    		        </div>
                                    		     </div>                                		     
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.institucion_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="institucion_nuevo_participante">Institución / entidad</label>
                                    		            <input type="text" id="institucion_nuevo_participante" class="form-control white-readonly" 
                                    		            value="Universidad Cooperativa de Colombia" 
                                    		            ng-readonly="true" class="form-control white-readonly"/>
                                    		        </div>
                                    		     </div>                   
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.sede_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="sede_nuevo_participante">
                                    		                Sede <span style="color:#B22222;" ng-show="visibilidad.sede_nuevo_participante_invalido">Campo requerido. Elegir una sede</span>
                                    		            </label>
                                    					<ui-select theme="bootstrap"  
                                    					ng-model="data.sede_nuevo_participante" ng-change="cambia_sede_nuevo_participante()"
                                    					ng-require="true"
                                    					ng-class="{'invalid_control': visibilidad.sede_nuevo_participante_invalido}">
                                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                    						<ui-select-choices repeat="item in data.sedes | filter: $select.search">
                                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                    						</ui-select-choices>
                                    					</ui-select>                                		            
                                    		        </div>
                                    		     </div>                                                   		     
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.grupo_inv_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="grupo_inv_nuevo_participante">
                                    		                Grupo de investigación <span style="color:#B22222;" ng-show="visibilidad.grupo_inv_nuevo_participante_invalido">Campo requerido. ELegir un grupo de invetigación</span>
                                    		            </label>
                                    					<ui-select theme="bootstrap"  
                                    					ng-model="data.grupo_inv_nuevo_participante" ng-change="cambia_grupo_inv_nuevo_participante()"
                                    					ng-require="true"
                                    					ng-class="{'invalid_control': visibilidad.grupo_inv_nuevo_participante_invalido}">
                                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                    						<ui-select-choices repeat="item in data.grupos_inv_nuevo_participante | filter: $select.search">
                                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                    						</ui-select-choices>
                                    					</ui-select>                                		            
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.facultad_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="facultad_nuevo_participante">
                                    		                Facultad <span style="color:#B22222;" ng-show="visibilidad.facultad_nuevo_participante_invalido">Campo requerido. Elegir una facultad</span>
                                    		            </label>
                                    					<input type="text" id="facultad_nuevo_participante" ng-model="data.facultad_nuevo_participante.nombre" ng-change="validar_facultad_nuevo_participante()"
                                    					class="form-control white-readonly"
                                    					ng-readonly="true"
                                    					ng-class="{'invalid_control': visibilidad.facultad_nuevo_participante_invalido}"/>
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.entidad_grupo_inv_externo_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="entidad_grupo_inv_externo_nuevo_participante">
                                    		                Entidad / grupo de investigación co-ejecutor <span style="color:#B22222;" ng-show="visibilidad.entidad_externa_nuevo_participante_invalido">Entidad/grupo inv. inválido</span>
                                    		            </label>
                                    					<input type="text" id="entidad_grupo_inv_externo_nuevo_participante" ng-model="data.entidad_externa_nuevo_participante" ng-change="validar_entidad_externa_nuevo_participante()"
                                    					class="form-control"
                                    					ng-class="{'invalid_control': visibilidad.entidad_externa_nuevo_participante_invalido}"/>
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.programa_academico_nuevo_participante">
                                    		        <div class="form-group">
                                    		            <label for="programa_academico_nuevo_participante">
                                    		                Programa académico <span style="color:#B22222;" ng-show="visibilidad.programa_academico_participante_invalido">Programa académico inválido</span>
                                    		            </label>
                                    					<input type="text" ng-model="data.programa_academico_nuevo_participante" ng-change="validar_programa_acad_nuevo_participante()"
                                    					class="form-control"
                                    					ng-class="{'invalid_control': visibilidad.programa_academico_participante_invalido}"/>
                                    		        </div>
                                    		     </div>
                                    		    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    		        <div class="form-group">
                                    		            <label for=""></label>
                                    					<button type="button" ng-click="agregar_participante()" class="btn btn-primary btn-block">Agregar participante <i class="fa fa-plus" arua-hidden="true"></i></button>
                                    		        </div>
                                    		     </div>                                		     
                                    		</div>
                        		        </div>
                                		<div class="overlay" ng-show="visibilidad.velo_edicion_datos_participante">
                                            <div style="display:table; width:100%; height:100%;">
                                                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_edicion_datos_participante">
                                        			<!--Contenido generado desde crear_usuarios_controller-->
                                                </div>
                                            </div>    		    
                                		</div>                    		    
                        		    </div>
                        		</fieldset> {{--Edición de datos del participante--}}
                        		
                                <fieldset>
                                	<legend>Participantes agregados</legend>
                                	<div style="max-height:400px;" id="contenedor_tabla_participantes">
                                		<table class="table table-striped">
                                			<tbody>	
                                				<tr ng-repeat="participante in data.participantes_proyecto" class="nga-fast nga-stagger-fast nga-fade">
                                				    {{--Investigador pricipal--}}
                                					<td style="padding-left: 8px; padding-top: 15px; padding-bottom: 15px; padding-right: 0px;" ng-if="participante.es_investigador_principal==true">
                                						<div class="panel panel-default" style="margin: 0; box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                                							<div class="panel-heading" role="tab">
                                								<div class="row is-flex">
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="nombres_inv_principal">Nombres</label>
                                											<input type="text" id="nombres_inv_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.nombres"
                                											ng-readonly="true">
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="apellidos_inv_principal">Apellidos</label>
                                											<input type="text" id="apellidos_inv_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.apellidos"
                                											ng-readonly="true">
                                										</div>
                                									</div>                    	
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="identificacion_nuevo_participante">Identificación</label>
                                											<input type="text" id="identificacion_nuevo_participante" name="identificacion_investigador_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.identificacion"
                                											ng-readonly="true">
                                										</div>
                                									</div>                    	                                        		     
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="formacion_inv_principal">Formación</label>
                                											<input type="text" id="formacion_inv_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.formacion"
                                											ng-readonly="true">
                                										</div>
                                									 </div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="rol">Rol en el proyecto</label>
                                											<input type="text" class="form-control white-readonly" ng-model="data.info_investigador_principal.nombre_rol"
                                											ng-readonly="true">
                                										</div>
                                									 </div>
                                									 <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label>&nbsp;</label>
                                											<button type="button" data-toggle="collapse" data-target="#collapse_inv_principal" aria-expanded="true" aria-controls="collapse_inv_principal" class="btn btn-primary btn-block">
                                												Mas información <i class="fa fa-caret-down" aria-hidden="true"></i>
                                											</button>
                                										</div>
                                									</div>
                                								</div>
                                							</div>
                                							<div id="collapse_inv_principal" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_inv_principal" aria-expanded="false" style="height: 0px;">
                                								<div class="panel-body">
                                									<div class="row is-flex">
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">				
                                												<label for="tipo_id_inv_principal">Tipo de identificación</label>
                                												<input type="text" id="tipo_id_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_tipo_identificacion $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div> {{--Tipo de identificación--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="sexo_inv_principal">Sexo</label>
                                											<input type="text" class="form-control white-readonly" ng-if="data.info_investigador_principal.sexo=='m'" value="Hombre" ng-readonly="true"/>
                                											<input type="text" class="form-control white-readonly" ng-if="data.info_investigador_principal.sexo=='f'" value="Mujer" ng-readonly="true"/>
                                										 </div> {{--Sexo--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="edad_inv_principal">Edad</label>
                                												<input type="number" min="10" id="edad_inv_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.edad"
                                												ng-readonly="true">
                                											</div>
                                										 </div> {{--Edad--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="email_inv_principal">Email</label>
                                												<input type="text" id="email_inv_principal" class="form-control white-readonly" ng-model="data.info_investigador_principal.email"
                                												ng-readonly="true">
                                											</div>
                                										 </div> {{--email--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="institucion_inv_principal">Institución / entidad</label>
                                												<input type="text" id="institucion_inv_principal" class="form-control white-readonly" value="Universidad Cooperativa de Colombia"
                                												ng-readonly="true"/>
                                											</div>
                                										</div> {{--Institucion UCC--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="institucion_inv_principal">Sede</label>
                                												<input type="text" id="institucion_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_sede $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div>{{--Sede--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="institucion_inv_principal">Sede</label>
                                												<input type="text" id="institucion_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_grupo_inv $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div>{{--Grupo de investigación--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="facultad_inv_principal">Sede</label>
                                												<input type="text" id="facultad_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_facultad $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div>{{--Facultad grupo de investigación--}}                                                                    
                                									</div>
                                								</div>
                                							</div>
                                						</div>
                                					</td> {{--./Investigador principal--}}
                                				    {{--Otros participantes--}}
                                					<td style="padding-left: 8px; padding-top: 15px; padding-bottom: 15px; padding-right: 0px;" ng-if="participante.es_investigador_principal==false">
                                						<div class="panel panel-default" style="margin: 0; box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                                							<div class="panel-heading" role="tab">
                                								<!--nombres, apellidos, identificacion, formacion, rol, btnMasInfo, removerBtn-->
                                								<div class="row is-flex">
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="nombres_{$ $index $}">Nombres</label>
                                											<input type="text" id="nombres_{$ $index $}" name="nombres_{$ $index $}" ng-model="participante.nombres" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="apellidos_{$ $index $}">Apellidos</label>
                                											<input type="text" id="apellidos_{$ $index $}" name="apellidos_{$ $index $}" ng-model="participante.apellidos" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="identificacion_{$ $index $}">Identificación</label>
                                											<input type="number" min="1" id="identificacion_{$ $index $}" name="identificacion_{$ $index $}" ng-model="participante.identificacion" 
                                											ng-readonly="true" class="form-control white-readonly" />
                                										</div>
                                									</div>             		
                                									<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<label for="formacion_{$ $index $}">Formación</label>
                                										<input type="text" id="formacion_{$ $index $}" name="formacion_{$ $index $}" ng-model="participante.formacion" 
                                										ng-readonly="true" class="form-control white-readonly"/>
                                									</div> 		
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<label for="rol_{$ $index $}">Rol en el proyecto</label>
                                										<input type="text" id="rol_{$ $index $}" ng-model="participante.rol" 
                                										ng-readonly="true" class="form-control white-readonly"/>
                                										<input type="hidden" name="id_rol_{$ $index $}" value="{$ participante.id_rol $}"/>
                                									</div>
                                									
                                									 <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label>&nbsp;</label>
                                											<button type="button" data-toggle="collapse" data-target="#collapse_{$ $index $}" aria-expanded="true" aria-controls="collapse_{$ $index $}" class="btn btn-primary btn-block">
                                												Mas información <i class="fa fa-caret-down" aria-hidden="true"></i>
                                											</button>
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">                                        					
                                											<label>&nbsp;</label>
                                											<button type="button" class="btn btn-default btn-block" ng-click="remover_participante(participante)">
                                												Remover participante <i class="fa fa-times"></i> &nbsp;
                                											</button>                                        							                                        						
                                										</div>
                                									</div>
                                									
                                								</div>
                                							</div>
                                							<div id="collapse_{$ $index $}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapse_{$ $index $}" aria-expanded="false" style="height: 0px;">
                                								<div class="panel-body">
                                									<!--tipoId, sexo, edad, email, ucc, sede, grupo_inv, facultad, entidad/grupo_inv externo, programa_acadenimo-->
                                									<div class="row is-flex">
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="tipo_identificacion_{$ $index $}">Tipo de identificación</label>
                                											<input type="text" id="tipo_identificacion_{$ $index $}" ng-model="participante.tipo_identificacion" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" name="tipo_identificacion_{$ $index $}" value="{$ participante.id_tipo_identificacion $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="sexo_{$ $index $}">Sexo</label>
                                											<input type="text" id="sexo_{$ $index $}" ng-model="participante.sexo" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_sexo_{$ $index $}" name="sexo_{$ $index $}" value="{$ participante.id_sexo $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="edad_{$ $index $}">Edad</label>
                                											<input type="text" id="edad_{$ $index $}" name="edad_{$ $index $}" ng-model="participante.edad" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>                                										
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="email_{$ $index $}">Email</label>
                                											<input type="text" id="email_{$ $index $}" name="email_{$ $index $}" ng-model="participante.email" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>			
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==4">
                                											<label for="ucc_{$ $index $}">Institución / entidad</label>
                                											<input type="text" id="ucc_{$ $index $}" value="Universidad Cooperativa de Colombia" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==4">
                                											<label for="sede_{$ $index $}">Sede</label>
                                											<input type="text" id="sede_{$ $index $}" ng-model="participante.sede" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_sede_{$ $index $}" name="sede_{$ $index $}" value="{$ participante.id_sede $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==4">
                                											<label for="grupo_inv_{$ $index $}">Grupo de investigación</label>
                                											<input type="text" id="grupo_inv_{$ $index $}" ng-model="participante.grupo_investigacion" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_grupo_investigacion_{$ $index $}" name="grupo_investigacion_{$ $index $}" value="{$ participante.id_grupo_investigacion $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==4">
                                											<label for="facultad_{$ $index $}">Facultad / dependencia</label>
                                											<input type="text" id="facultad_{$ $index $}" ng-model="participante.facultad_dependencia" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_facultad_dependencia_{$ $index $}" name="facultad_dependencia_{$ $index $}" value="{$ participante.id_facultad_dependencia $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==5 || participante.id_rol==6">
                                											<label for="entidad_externa_{$ $index $}">Entidad / grupo de investigación co-ejecutor</label>
                                											<input type="text" id="entidad_externa_{$ $index $}" name="entidad_externa_{$ $index $}" ng-model="participante.entidad_grupo_inv_externo" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.id_rol==6">
                                											<label for="programa_academico_{$ $index $}">Programa académico</label>
                                											<input type="text" id="programa_academico_{$ $index $}" name="programa_academico_{$ $index $}" ng-model="participante.programa_academico" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>			
                                									</div>
                                								</div>
                                							</div>
                                						</div>
                                					</td>    
                                				</tr> <!--./tr's participantes agregados-->
                                			</tbody>
                                		</table>
                                	</div>
                                </fieldset> {{--Participantes agregados--}}
                                
                                <div class="row">
                        	        <div class="col-xs-12 col-sm-6">
                        	            <button type="button" class="btn btn-primary btn-block" ng-click="mostrar_modal_grupos_investigacion()">
                        	                Ver entidades / grupos de investigación participantes
                        	            </button>
                        	        </div>                                    
                                </div>
                                <br />
                        		<hr />
                        	    <div class="row">
                        	        <div class="col-xs-12 col-sm-6 col-md-4">
                        	            <button type="button" class="btn btn-default btn-block" ng-click="regresar_info_general()">
                        	                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Regresar a información general
                        	            </button>
                        	        </div>                        	        
                        	        <div class="col-xs-12">&nbsp;</div>
                        	        <div class="col-xs-12 col-sm-6 col-md-4">
                        	            <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_productos()">
                        	                Ingresar productos&nbsp;<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                        	            </button>
                        	        </div>
                        	    </div>	
                            </div>
                        </div> {{--Contenito tab participantes--}}
                        
                        {{--Contenido tab productos--}}
                        <div id="contenido_productos" class="tab-pane fade"
                        ng-controller="crear_productos_proyecto_controller">
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
                        		        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_producto()">Agregar nuevo tipo de producto <i class="fa fa-plus" aria-hidden="true"></i></button>
                        		    </div>
                        		</div>
                        		<hr />
                        		<div class="table-responsive" id="contenedor_productos">
                        		    <!--id="contenedor_productos"-->
                        		    <table class="table table-hover">
                        		        <thead id="table_head_productos">
                        		            <tr>
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
                        		            <tr ng-if="data.productos.length==0"><td colspan="11"><p class="text-left">Productos no añadidos</p></td></tr>
                        		            <tr ng-if="data.productos.length>0" ng-repeat="producto in data.productos" class="nga-fast nga-stagger-fast nga-fade">
                        		                <td>
                        		                    <input type="text" ng-readonly="true"
                        		                    ng-model="producto.tipo_producto_general.nombre"
                        		                    class="white-readonly form-control"/>
                        		                    <input type="hidden" name="id_tipo_producto_general_{$ $index $}" value="{$ producto.tipo_producto_general.id $}"/>
                        		                </td>
                        		                <td>
                        		                    <input type="text" ng-readonly="true"
                        		                    ng-model="producto.tipo_producto_especifico.nombre"
                        		                    class="white-readonly form-control"/>
                        		                    <input type="hidden" name="id_tipo_producto_especifico_{$ $index $}" value="{$ producto.tipo_producto_especifico.id $}"/>
                        		                </td>
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.nombre_invalido">Longitud mínima de 5 caracteres y máxima de 200</span>
                        		                    <input type="text" name="nombre_producto_{$ $index $}" ng-model="producto.nombre" ng-change="validar_nombre_producto(producto)"
                        		                    class="form-control" ng-class="{'invalid_control': producto.nombre_invalido}" style="min-width: 170px;"/>
                        		                </td>
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.participante_invalido">Campo requerido. Elegir un participante</span>
                                					<ui-select theme="bootstrap" append-to-body="true"
                                					ng-model="producto.participante" ng-change="validar_participante_producto(producto)"
                                					ng-required="true" ng-class="{'invalid_control': producto.participante_invalido}">
                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombres + ' ' + $select.selected.apellidos $}</ui-select-match>
                                						<ui-select-choices repeat="item in data.participantes_proyecto | filter: $select.search">
                                							<div ng-bind-html="item.nombres + ' ' + item.apellidos | highlight: $select.search"></div>
                                						</ui-select-choices>
                                					</ui-select>                                                
                                					<input type="hidden" name="encargado_producto_{$ $index $}" value="{$ producto.participante.identificacion $}"/>
                        		                </td>
                        		                <td>
                        		                    <span class="error-text" ng-show="producto.fecha_proyectada_radicar_invalido">Ingresar fecha</span>
                        		                    <div class="input-group">
                                                        <input type="text" name="fecha_proyectada_radicar_{$ $index $}"
                                                            ng-model="producto.fecha_proyectada_radicar" ng-change="validar_fecha_proyectada_radicar(producto)"
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
                                                            ng-model="producto.fecha_remision" ng-change="validar_fecha_remision(producto)"
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
                                                            ng-model="producto.fecha_confirmacion_editorial" ng-change="validar_fecha_confirmacion_editorial(producto)"
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
                                                            ng-model="producto.fecha_recepcion_evaluacion" ng-change="validar_fecha_recepcion_evaluacion(producto)"
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
                                                            ng-model="producto.fecha_respuesta_evaluacion" ng-change="validar_fecha_respuesta_evaluacion(producto)"
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
                                                            ng-model="producto.fecha_aprobacion_publicacion" ng-change="validar_fecha_aprobacion_publicacion(producto)"
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
                                                            ng-model="producto.fecha_publicacion" ng-change="validar_fecha_publicacion(producto)"
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
                        	            <button type="button" class="btn btn-default btn-block" ng-click="regresar_participantes()">
                        	                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Regresar a participantes
                        	            </button>
                        	        </div>                        	        
                        	        <div class="col-xs-12">&nbsp;</div>                    		        
                    		        <div class="col-xs-12 col-sm-6 col-md-4">
                    		            <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_gastos()">Ingresar gastos&nbsp;<i class="fa fa-chevron-circle-right" aria-hidden="true"></i></button>
                    		        </div>
                    		    </div>                    		
                        	</div>
                        </div> {{--Contenido tab productos--}}
                        
                        {{--Contenido tab gastos--}}
                        <div id="contenido_gastos" class="tab-pane fade"
                        ng-controller="crear_gastos_proyectos_controller">
                            
                            <input type="hidden" name="cantidad_gastos_equipos" value="{$ data.gastos_equipos.length $}"/>
                            <input type="hidden" name="cantidad_gastos_software" value="{$ data.gastos_software.length $}"/>
                            <input type="hidden" name="cantidad_gastos_salidas" value="{$ data.gastos_salidas.length $}"/>
                            <input type="hidden" name="cantidad_gastos_materiales" value="{$ data.gastos_materiales.length $}"/>
                            <input type="hidden" name="cantidad_gastos_servicios_tecnicos" value="{$ data.gastos_servicios_tecnicos.length $}"/>
                            <input type="hidden" name="cantidad_gastos_bibliograficos" value="{$ data.gastos_bibliograficos.length $}"/>
                            <input type="hidden" name="cantidad_gastos_digitales" value="{$ data.gastos_digitales.length $}"/>
                            
                            <br />
                            
                            <p><strong>Agregar entidad fuente de presupuesto (por defecto se tiene UCC y CONADI)</strong></p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <ui-select id="multiselect_entidades_presupuesto" 
                                    multiple ng-model="data.entidades_presupuesto_seleccionadas" 
                                    close-on-select="true" search-enabled="true" on-select="seleccion_entidad_presupuesto($item)" on-remove="remocion_entidad_presupuesto($item)"
                                    style="width: 100%; height: 34px;" theme="bootstrap"  title="Seleccionar otras entidades fuente de presupuesto...">
                                        <ui-select-match placeholder="Seleccione...">{$ $item.nombre $}</ui-select-match>
                                        <ui-select-choices repeat="entidad_presupuesto in data.entidades_fuente_presupuesto">
                                            {$ entidad_presupuesto.nombre $}
                                        </ui-select-choices>
                                    </ui-select>
                                    <div id="inputs_nuevas_entidades_fuente_presupuesto" ng-hide="true">
                                        {{--contenido generado desde controlador--}}
                                        {{--son inputs hidden para crear nuevos registros de entidades financiadoras--}}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <p style="color: rgb(178, 34, 34);" ng-show="visibilidad.nueva_entidadPresupuesto_incorrecto">{$ data.msj_nueva_entidadPresupuesto_incorrecto $}</p>
                                    <div class="input-group">
                                        <input type="text" ng-model="data.nueva_entidad_entidad_presupuesto" 
                                        ng-change="visibilidad.nueva_entidadPresupuesto_incorrecto=false" 
                                        class="form-control" ng-class="{'invalid_control': visibilidad.nueva_entidadPresupuesto_incorrecto}"/>
                                        <span class="input-group-addon btn btn-default" ng-click="agregar_nueva_entidadPresupuesto()">
                                            Agregar&nbsp;<i class="glyphicon glyphicon-plus"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br />
                            
                            {{--Gastos personas--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head_descripcion_gastos_personal">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_descripcion_gastos_personal" aria-expanded="true" aria-controls="head_descripcion_gastos_personal">
                            				<span class="glyphicon glyphicon-minus"></span>&nbsp;Descripción de gastos de personal
                            			</a>
                            		</h4>
                                </div>
                                <div id="body_descripcion_gastos_personal" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="head_descripcion_gastos_personal">
                            		<div class="panel-body">
                            		    <div class="table-responsive" id="contenedor_gastos_personal">
                                			<table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Nombre del investigador</th>
                                                        <th>Formación académica</th>
                                                        <th>Rol en el proyecto</th>
                                                        <th>Dedicación (horas semanales)</th>
                                                        <th>Total semanas</th>
                                                        <th>Valor hora</th>
                                                        <th>UCC</th>
                                                        <th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                                                        <th>Fecha de ejecución</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="participante in data.participantes_proyecto">
                                                        <td>{$ $index + 1 $}</td>
                                                        <td>{$ participante.nombres + ' ' + participante.apellidos $}</td>
                                                        <td>{$ participante.formacion $}</td>
                                                        <td>{$ participante.rol $}</td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_dedicacion_semanal_{$ participante.identificacion $}" min="0" 
                                                            ng-model="participante.dedicacion_semanal" ng-change="validar_dedicacion_semanal(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.dedicacion_semanal_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.dedicacion_semanal_invalido"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_total_semanas_{$ participante.identificacion $}" 
                                                            ng-model="participante.total_semanas" ng-change="validar_total_semanas(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.total_semanas_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.total_semanas_invalido"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_valor_hora_{$ participante.identificacion $}" 
                                                            ng-model="participante.valor_hora" ng-change="validar_valor_hora(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.valor_hora_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.valor_hora_invalido"/>
                                                        </td>                                                                                                        
                                                        <td>
                                                            <input type="number" name="gasto_personal_presupuesto_ucc_{$ participante.identificacion $}" 
                                                            ng-model="participante.presupuesto_ucc" ng-change="suma_totales_personal(participante, 'ucc')"
                                                            class="form-control" ng-class="{'invalid_control': participante.presupuesto_ucc_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.presupuesto_ucc_invalido"/>
                                                        </td>                                                                                                                                                            
                                                        <td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                                                            <input type="number" name="gasto_personal_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ participante.identificacion $}" 
                                                            ng-model="participante.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_personal(participante, 'otro', entidad_presupuesto.id)"
                                                            class="form-control" ng-class="{'invalid_control': participante.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_personal_fecha_ejecucion_{$ participante.identificacion $}" id="gasto_personal_fecha_ejecucion_{$ participante.identificacion $}" 
                                                                    ng-model="participante.fecha_ejecucion" ng-change="validar_fecha_ejecucion_participante(participante)"
                                                                    is-open="participante.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="participante.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': participante.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="participante.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {$ participante.presupuesto_total|currency:$:2 $}
                                                        </td>
                                                    </tr>
                            						<tr ng-if="data.participantes_proyecto.length>0">
                            							<td colspan="7" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_personal.ucc|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_personal.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td colspan="2" class="text-right">{$ data.totales_personal.total|currency:$ $}</td>
                            						</tr>                                                
                                                </tbody>
                                			</table>
                            		    </div>
                            		</div>
                            	</div>
                            </div>{{--./Gastos personas--}}
                            
                            {{--Gastos equipos--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head_descripcion_gastos_equipos">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_descripcion_gastos_equipos" aria-expanded="true" aria-controls="head_descripcion_gastos_equipos">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Descripción de los equipos que se planean adquirir y utilizar
                            			</a>
                            		</h4>
                                </div>
                                <div id="body_descripcion_gastos_equipos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_descripcion_gastos_equipos">
                            		<div class="panel-body">
                            		    <div class="row">
                            		        <div class="col-xs-12 col-sm-6 col-md-4">
                            		            <button type="button" class="btn btn-primary btn-block" ng-click="agregar_gasto_equipo()">Agregar equipo&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                            		        </div>
                            		    </div>
                            		    <br />
                            		    <div class="table-responsive" id="contenedor_gastos_equipos">
                                			<table class="table table-hover table-bordered">
                                			    <thead id="table_head_gastos_equipos">
                                			        <tr>
                                			            <th>N°</th>
                                			            <th>Equipo</th>
                                			            <th>Justificación</th>
                                			            <th>UCC</th>
                                			            <th>CONADI</th>
                                			            <th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                                			            <th>Fecha de ejecución</th>
                                			            <th>Total</th>
                                			            <th>Remover</th>
                                			        </tr>
                                			    </thead>
                                			    <tbody id="table_body_gastos_equipos">
                                        		    <tr ng-if="data.gastos_equipos.length==0">
                                        		        <td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de equipos</td>
                                        		    </tr>                            			        
                                                    <tr ng-repeat="gasto_equipo in data.gastos_equipos">
                                                        <td>{$ $index + 1 $}</td>
                                                        <td>
                                                            <input type="text" name="gasto_equipo_nombre_{$ $index $}" 
                                                            ng-model="gasto_equipo.equipo" ng-change="validar_nombre_equipo(gasto_equipo)"
                                                            class="form-control" ng-class="{'invalid_control': gasto_equipo.nombre_equipo_invalido}"
                                                            uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.nombre_equipo_invalido"/>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="gasto_equipo_justificacion_{$ $index $}" 
                                                            ng-model="gasto_equipo.justificacion" ng-change="validar_justificacion_gasto_equipo(gasto_equipo)"
                                                            class="form-control" ng-class="{'invalid_control': gasto_equipo.justificacion_invalido}"
                                                            uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.justificacion_invalido"/>
                                                        </td>                                   
                                                        <td>
                                                            <input type="number" name="gasto_equipo_presupuesto_ucc_{$ $index $}"
                                                            ng-model="gasto_equipo.presupuesto_ucc" ng-change="suma_totales_equipos(gasto_equipo, 'ucc')"
                                                            class="form-control" ng-class="{'invalid_control': gasto_equipo.presupuesto_ucc_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.presupuesto_ucc_invalido"/>
                                                        </td>                                                                                       
                                                        <td>
                                                            <input type="number" name="gasto_equipo_presupuesto_conadi_{$ $index $}"
                                                            ng-model="gasto_equipo.presupuesto_conadi" ng-change="suma_totales_equipos(gasto_equipo, 'conadi')"
                                                            class="form-control" ng-class="{'invalid_control': gasto_equipo.presupuesto_conadi_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.presupuesto_conadi_invalido"/>
                                                        </td> 
                                                        <td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                                                            <input type="number" name="gasto_equipo_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}"
                                                            ng-model="gasto_equipo.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_equipos(gasto_equipo, 'otro', entidad_presupuesto.id)"
                                                            class="form-control" ng-class="{'invalid_control': gasto_equipo.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_equipo_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_equipo.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_equipo(gasto_equipo)"
                                                                    is-open="gasto_equipo.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_equipo.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_equipo.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_equipo.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_equipo.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                                                        
                                                        <td>
                                                            {$ gasto_equipo.total|currency:$:2 $}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-default" ng-click="remover_gasto_equipo(gasto_equipo)"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                        </td>
                                                    </tr>
                            						<tr ng-if="data.gastos_equipos.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_equipos.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_equipos.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_equipos.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>&nbsp;</td>
                            							<td>{$ data.totales_equipos.total|currency:$ $}</td>
                            						</tr>                                                
                                			    </tbody>
                                			</table>                            
                                	    </div>
                                    </div>
                                </div>
                            </div>{{--./Gastos equipos--}}
                            
                            {{--Gastos software--}}
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="head_descripcion_gastos_software">
                                    <h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_descripcion_gastos_software" aria-expanded="true" aria-controls="head_descripcion_gastos_software">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Descripción del software que se planea adquirir
                            			</a>                                    
                                    </h4>
                                </div>
                                <div id="body_descripcion_gastos_software" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_descripcion_gastos_software">
                            		<div class="panel-body">
                                        <div class="row">
                                        	<div class="col-xs-12 col-sm-6 col-md-4">
                                        		<button type="button" class="btn btn-primary btn-block" ng-click="agregar_gasto_software()">Agregar software&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                        	</div>
                                        </div>
                                        <br />
                                        <div class="table-responsive" id="contenedor_gastos_software">
                                        	<table class="table table-hover table-bordered">
                                        		<thead>
                                        			<tr>
                                        				<th>N°</th>
                                        				<th>Software</th>
                                        				<th>Justificación</th>
                                        				<th>UCC</th>
                                        				<th>CONADI</th>
                                        				<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                                        				<th>Fecha de ejecución</th>
                                        				<th>Total</th>
                                        				<th>Remover</th>
                                        			</tr>
                                        		</thead>
                                        		<tbody>
                                        		    <tr ng-if="data.gastos_software.length==0">
                                        		        <td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de software</td>
                                        		    </tr>
                                        			<tr ng-repeat="gasto_software in data.gastos_software">
                                        				<td>{$ $index + 1 $}</td>
                                        				<td>
                                        					<input type="text" name="gasto_software_nombre_{$ $index $}" 
                                        					ng-model="gasto_software.software" ng-change="validar_nombre_software(gasto_software)"
                                        					class="form-control" ng-class="{'invalid_control': gasto_software.nombre_software_invalido}"
                                        					uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.nombre_software_invalido"/>
                                        				</td>
                                        				<td>
                                        					<input type="text" name="gasto_software_justificacion_{$ $index $}"
                                        					ng-model="gasto_software.justificacion" ng-change="validar_justificacion_software(gasto_software)"
                                        					class="form-control" ng-class="{'invalid_control': gasto_software.justificacion_invalido}"
                                        					uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.justificacion_invalido"/>
                                        				</td>                                   
                                        				<td>
                                        					<input type="number" name="gasto_software_presupuesto_ucc_{$ $index $}" 
                                        					ng-model="gasto_software.presupuesto_ucc" ng-change="suma_totales_software(gasto_software, 'ucc')"
                                        					class="form-control" ng-class="{'invalid_control': gasto_software.presupuesto_ucc_invalido}"
                                        					uib-tooltip="La cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.presupuesto_ucc_invalido"/>
                                        				</td>                                                                                       
                                        				<td>
                                        					<input type="number" name="gasto_software_presupuesto_conadi_{$ $index $}"
                                        					ng-model="gasto_software.presupuesto_conadi" ng-change="suma_totales_software(gasto_software, 'conadi')"
                                        					class="form-control" ng-class="{'invalid_control': gasto_software.presupuesto_conadi_invalido}"
                                        					uib-tooltip="La cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.presupuesto_conadi_invalido"/>
                                        				</td> 
                                        				<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                                        					<input type="number" name="gasto_software_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}"
                                        					ng-model="gasto_software.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_software(gasto_software, 'otro', entidad_presupuesto.id)"
                                        					class="form-control" ng-class="{'invalid_control': gasto_software.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                                        					uib-tooltip="La cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                                        				</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_software_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_software.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_software(gasto_software)"
                                                                    is-open="gasto_software.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_software.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_software.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_software.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_software.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                                                   				
                                        				<td>
                                        				    {$ gasto_software.total|currency:$:2 $}
                                        				</td>
                                        				<td>
                                        					<button type="button" class="btn btn-default" ng-click="remover_gasto_software(gasto_software)"><i class="fa fa-times" aria-hidden="true"></i></button>
                                        				</td>
                                        			</tr>
                            						<tr ng-if="data.gastos_software.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_software.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_software.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_software.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>&nbsp;</td>
                            							<td class="text-right">{$ data.totales_software.total|currency:$ $}</td>
                            						</tr>                                                                                    			
                                        		</tbody>
                                        	</table>                            
                                        </div>
                            		</div>
                            	</div>
                            </div>{{--Gastos software--}}
                            
                            {{--Gastos salidas campo--}}
                            <div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="head_descripcion_gastos_salidas">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_descripcion_gastos_salidas" aria-expanded="true" aria-controls="head_descripcion_gastos_salidas">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Valoración salidas de campo
                            			</a>                                    
                            		</h4>
                            	</div>
                            	<div id="body_descripcion_gastos_salidas" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_descripcion_gastos_salidas">
                            		<div class="panel-body">
                            			<div class="row">
                            				<div class="col-xs-12 col-sm-6 col-md-4">
                            					<button type="button" class="btn btn-primary btn-block" ng-click="agregar_salida_campo()">Agregar salida de campo&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                            				</div>
                            			</div>
                            			<br />
                            			<div class="table-responsive" id="contenedor_gastos_salidas">
                            				<table class="table table-hover table-bordered">
                            					<thead>
                            						<tr>
                            							<th>N°</th>
                            							<th>Justificación</th>
                            							<th>N° de salidas</th>
                            							<th>Valor unitario</th>
                            							<th>UCC</th>
                            							<th>CONADI</th>
                            							<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                            							<th>Fecha de ejecución</th>
                            							<th>Total</th>
                            							<th>Remover</th>
                            						</tr>
                            					</thead>
                            					<tbody>
                            						<tr ng-if="data.gastos_salidas.length==0">
                            							<td colspan="{$ 9 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de salidas de campo</td>
                            						</tr>
                            						<tr ng-repeat="gasto_salida in data.gastos_salidas">
                            							<td>{$ $index + 1 $}</td>
                            							<td>
                            								<input type="text" name="gasto_salida_justificacion_{$ $index $}" 
                            								ng-model="gasto_salida.justificacion" ng-change="validar_justificacion_gasto_salida(gasto_salida)"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.justificacion_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.justificacion_invalido"/>
                            							</td>
                            							<td>
                            								<input type="number" name="gasto_salida_cantidad_salidas_{$ $index $}" 
                            								ng-model="gasto_salida.cantidad_salidas" ng-change="validar_cantidad_salidas(gasto_salida)"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.cantidad_salidas_invalido}"
                            								uib-tooltip="Cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.cantidad_salidas_invalido"/>
                            							</td>                                   
                            							<td>
                            								<input type="number" name="gasto_salida_valor_unitario_{$ $index $}" 
                            								ng-model="gasto_salida.valor_unitario" ng-change="validar_valor_unitario(gasto_salida)"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.valor_unitario_invalido}"
                            								uib-tooltip="Cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.valor_unitario_invalido"/>
                            							</td>                  							
                            							<td>
                            								<input type="number" name="gasto_salida_presupuesto_ucc_{$ $index $}"
                            								ng-model="gasto_salida.presupuesto_ucc" ng-change="suma_totales_salidas(gasto_salida, 'ucc')"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.presupuesto_ucc_invalido}"
                            								uib-tooltip="Cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.presupuesto_ucc_invalido"/>
                            							</td>                                                                                       
                            							<td>
                            								<input type="number" name="gasto_salida_presupuesto_conadi_{$ $index $}"
                            								ng-model="gasto_salida.presupuesto_conadi" ng-change="suma_totales_salidas(gasto_salida, 'conadi')"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.presupuesto_conadi_invalido}"
                            								uib-tooltip="Cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.presupuesto_conadi_invalido"/>
                            							</td> 
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            								<input type="number" name="gasto_salida_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}"
                            								ng-model="gasto_salida.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_salidas(gasto_salida, 'otro', entidad_presupuesto.id)"
                            								class="form-control" ng-class="{'invalid_control': gasto_salida.presupuesto_externo_invalido[entidad_presupuesto.id]}" 
                            								uib-tooltip="Cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                            							</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_salida_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_salida.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_salida(gasto_salida)"
                                                                    is-open="gasto_salida.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_salida.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_salida.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_salida.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                                                   			
                            							<td>
                            							    {$ gasto_salida.total|currency:$:2 $}
                            							</td>
                            							<td>
                            								<button type="button" class="btn btn-default" ng-click="remover_gasto_salida(gasto_salida)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            							</td>
                            						</tr>
                            						<tr ng-if="data.gastos_salidas.length>0">
                            							<td colspan="4" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_salidas.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_salidas.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_salidas.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>&nbsp;</td>
                            							<td>{$ data.totales_salidas.total|currency:$ $}</td>
                            						</tr>                             						
                            					</tbody>
                            				</table>                            
                            			</div>
                            		</div>
                            	</div>
                            </div>{{--Gastos salidas campo--}}
                            
                            {{--Gastos Materiales y suministros--}}
                            <div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="head_descripcion_materiales">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_descripcion_materiales" aria-expanded="true" aria-controls="head_descripcion_materiales">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Materiales y suministros
                            			</a>
                            		</h4>
                            	</div>
                            	<div id="body_descripcion_materiales" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_descripcion_materiales">
                            		<div class="panel-body">
                            			<div class="row">
                            				<div class="col-xs-12 col-sm-6 col-md-4">
                            					<button type="button" class="btn btn-primary btn-block" ng-click="agregar_material()">Agregar material / suministro&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                            				</div>
                            			</div>
                            			<br />
                            			<div class="table-responsive" id="contenedor_gastos_materiales">
                            				<table class="table table-hover table-bordered">
                            					<thead>
                            						<tr>
                            							<th>N°</th>
                            							<th>Materiales</th>
                            							<th>Justificación</th>
                            							<th>UCC</th>
                            							<th>CONADI</th>
                            							<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                            							<th>Fecha de ejecución</th>
                            							<th>Total</th>
                            							<th>Remover</th>
                            						</tr>
                            					</thead>
                            					<tbody>
                            						<tr ng-if="data.gastos_materiales.length==0">
                            							<td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de materiales / suministros</td>
                            						</tr>                            			        
                            						<tr ng-repeat="gasto_material in data.gastos_materiales">
                            							<td>{$ $index $}</td>
                            							<td>
                            								<input type="text" name="gasto_material_nombre_{$ $index $}"
                            								ng-model="gasto_material.material" ng-change="validar_nombre_material(gasto_material)"
                            								class="form-control" ng-class="{'invalid_control': gasto_material.nombre_material_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.nombre_material_invalido"/>
                            							</td>
                            							<td>
                            								<input type="text" name="gasto_material_justificacion_{$ $index $}"
                            								ng-model="gasto_material.justificacion" ng-change="validar_justificacion_gasto_material(gasto_material)"
                            								class="form-control" ng-class="{'invalid_control': gasto_material.justificacion_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.justificacion_invalido"/>
                            							</td>                                   
                            							<td>
                            								<input type="number" name="gasto_material_presupuesto_ucc_{$ $index $}" 
                            								ng-model="gasto_material.presupuesto_ucc" ng-change="suma_totales_materiales(gasto_material, 'ucc')"
                            								class="form-control" ng-class="{'invalid_control': gasto_material.presupuesto_ucc_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.presupuesto_ucc_invalido"/>
                            							</td>                                                                                       
                            							<td>
                            								<input type="number" name="gasto_material_presupuesto_conadi_{$ $index $}" 
                            								ng-model="gasto_material.presupuesto_conadi" ng-change="suma_totales_materiales(gasto_material, 'conadi')"
                            								class="form-control" ng-class="{'invalid_control': gasto_material.presupuesto_conadi_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.presupuesto_conadi_invalido"/>
                            							</td> 
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            								<input type="number" name="gasto_material_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}" 
                            								ng-model="gasto_material.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_materiales(gasto_material, 'otro', entidad_presupuesto.id)"
                            								class="form-control" ng-class="{'invalid_control': gasto_material.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                            							</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_material_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_material.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_material(gasto_material)"
                                                                    is-open="gasto_material.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_material.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_material.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_material.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                                             							
                            							<td>
                            							    {$ gasto_material.total|currency:$:2 $}
                            							</td>
                            							<td>
                            								<button type="button" class="btn btn-default" ng-click="remover_gasto_material(gasto_material)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            							</td>
                            						</tr>
                            						<tr ng-if="data.gastos_materiales.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_materiales.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_materiales.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_materiales.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>&nbsp;</td>
                            							<td>{$ data.totales_materiales.total|currency:$ $}</td>
                            						</tr>                          						
                            					</tbody>
                            				</table>                            
                            			</div>
                            		</div>
                            	</div>
                            </div>{{--./Gastos Materiales y suministros--}}                        
                            
                            {{--Gastos Servicios técnicos--}}
                            <div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="head_servicios_tecnicos">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_servicios_tecnicos" aria-expanded="true" aria-controls="head_servicios_tecnicos">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Servicios técnicos
                            			</a>
                            		</h4>
                            	</div>
                            	<div id="body_servicios_tecnicos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_servicios_tecnicos">
                            		<div class="panel-body">
                            			<div class="row">
                            				<div class="col-xs-12 col-sm-6 col-md-4">
                            					<button type="button" class="btn btn-primary btn-block" ng-click="agregar_servicio_tecnico()">Agregar servicios técnicos&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                            				</div>
                            			</div>
                            			<br />
                            			<div class="table-responsive" id="contenedor_servicios_tecnicos">
                            				<table class="table table-hover table-bordered">
                            					<thead>
                            						<tr>
                            							<th>N°</th>
                            							<th>Materiales</th>
                            							<th>Justificación</th>
                            							<th>UCC</th>
                            							<th>CONADI</th>
                            							<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                            							<th>Fecha de ejecución</th>
                            							<th>Total</th>
                            							<th>Remover</th>
                            						</tr>
                            					</thead>
                            					<tbody>
                            						<tr ng-if="data.gastos_servicios_tecnicos.length==0">
                            							<td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de servicios técnicos</td>
                            						</tr>                            			        
                            						<tr ng-repeat="gasto_servicio in data.gastos_servicios_tecnicos">
                            							<td>{$ $index + 1 $}</td>
                            							<td>
                            								<input type="text" name="gasto_servicio_nombre_{$ $index $}"
                            								ng-model="gasto_servicio.servicio" ng-change="validar_nombre_servicio(gasto_servicio)"
                            								class="form-control" ng-class="{'invalid_control': gasto_servicio.nombre_servicio_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_servicio.nombre_servicio_invalido"/>
                            							</td>
                            							<td>
                            								<input type="text" name="gasto_servicio_justificacion_{$ $index $}" 
                            								ng-model="gasto_servicio.justificacion" ng-change="validar_justificacion_gasto_servicio(gasto_servicio)"
                            								class="form-control" ng-class="{'invalid_control': gasto_servicio.justificacion_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_servicio.justificacion_invalido"/>
                            							</td>                                   
                            							<td>
                            								<input type="number" name="gasto_servicio_presupuesto_ucc_{$ $index $}"
                            								ng-model="gasto_servicio.presupuesto_ucc" ng-change="suma_totales_servicios_tecnicos(gasto_servicio, 'ucc')"
                            								class="form-control" ng-class="{'invalid_control': gasto_servicio.presupuesto_ucc_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_servicio.presupuesto_ucc_invalido"/>
                            							</td>                                                                                       
                            							<td>
                            								<input type="number" name="gasto_servicio_presupuesto_conadi_{$ $index $}" 
                            								ng-model="gasto_servicio.presupuesto_conadi" ng-change="suma_totales_servicios_tecnicos(gasto_servicio, 'conadi')"
                            								class="form-control" ng-class="{'invalid_control': gasto_servicio.presupuesto_conadi_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_servicio.presupuesto_conadi_invalido"/>
                            							</td> 
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            								<input type="number" name="gasto_servicio_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}" 
                            								ng-model="gasto_servicio.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_servicios_tecnicos(gasto_servicio, 'otro', entidad_presupuesto.id)"
                            								class="form-control" ng-class="{'invalid_control': gasto_servicio.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_servicio.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                            							</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_servicio_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_servicio.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_servicio(gasto_servicio)"
                                                                    is-open="gasto_servicio.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_servicio.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_servicio.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_material.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_servicio.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                            							
                            							<td>
                            							    {$ gasto_servicio.total|currency:$:2 $}
                            							</td>
                            							<td>
                            								<button type="button" class="btn btn-default" ng-click="remover_gasto_servicio(gasto_servicio)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            							</td>
                            						</tr>
                                                    <tr ng-if="data.gastos_servicios_tecnicos.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_servicios_tecnicos.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_servicios_tecnicos.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_servicios_tecnicos.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<th>&nbsp;</th>
                            							<td>{$ data.totales_servicios_tecnicos.total|currency:$ $}</td>
                            						</tr>                          						
                            					</tbody>
                            				</table>                            
                            			</div>
                            		</div>
                            	</div>
                            </div>{{--./Gastos Servicios técnicos--}}                        
                            
                            {{--Gastos recursos bibliografricos--}}
                            <div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="head_recursos_bibliograficos">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_recursos_bibliograficos" aria-expanded="true" aria-controls="head_recursos_bibliograficos">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Recursos bibliográficos a adquirir
                            			</a>
                            		</h4>
                            	</div>
                            	<div id="body_recursos_bibliograficos" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_recursos_bibliograficos">
                            		<div class="panel-body">
                            			<div class="row">
                            				<div class="col-xs-12 col-sm-6 col-md-4">
                            					<button type="button" class="btn btn-primary btn-block" ng-click="agregar_recurso_bibliografico()">Agregar recurso bibliográfico<i class="fa fa-plus" aria-hidden="true"></i></button>
                            				</div>
                            			</div>
                            			<br />
                            			<div class="table-responsive" id="contenedor_recursos_bibliograficos">
                            				<table class="table table-hover table-bordered">
                            					<thead>
                            						<tr>
                            							<th>N°</th>
                            							<th>Título</th>
                            							<th>Justificación</th>
                            							<th>UCC</th>
                            							<th>CONADI</th>
                            							<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                            							<th>Fecha de ejecución</th>
                            							<th>Total</th>
                            							<th>Remover</th>
                            						</tr>
                            					</thead>
                            					<tbody>
                            						<tr ng-if="data.gastos_bibliograficos.length==0">
                            							<td colspan="{$ 8 + data.financiadores_seleccionados.length $}" class="text-left">Sin gastos de recursos bibliográficos</td>
                            						</tr>                            			        
                            						<tr ng-repeat="gasto_bibliografico in data.gastos_bibliograficos">
                            							<td>{$ $index + 1 $}</td>
                            							<td>
                            								<input type="text" name="gasto_bibliografico_nombre_{$ $index $}"
                            								ng-model="gasto_bibliografico.titulo" ng-change="validar_titulo_bibliografico(gasto_bibliografico)"
                            								class="form-control" ng-class="{'invalid_control': gasto_bibliografico.titulo_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.titulo_invalido"/>
                            							</td>
                            							<td>
                            								<input type="text" name="gasto_bibliografico_justificacion_{$ $index $}" 
                            								ng-model="gasto_bibliografico.justificacion" ng-change="validar_justificacion_gasto_bibliografico(gasto_bibliografico)"
                            								class="form-control" ng-class="{'invalid_control': gasto_bibliografico.justificacion_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.justificacion_invalido"/>
                            							</td>                                   
                            							<td>
                            								<input type="number" name="gasto_bibliografico_presupuesto_ucc_{$ $index $}"
                            								ng-model="gasto_bibliografico.presupuesto_ucc" ng-change="suma_totales_bibliograficos(gasto_bibliografico, 'ucc')"
                            								class="form-control" ng-class="{'invalid_control': gasto_bibliografico.presupuesto_ucc_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.presupuesto_ucc_invalido"/>
                            							</td>                                                                                       
                            							<td>
                            								<input type="number" name="gasto_bibliografico_presupuesto_conadi_{$ $index $}"
                            								ng-model="gasto_bibliografico.presupuesto_conadi" ng-change="suma_totales_bibliograficos(gasto_bibliografico, 'conadi')"
                            								class="form-control" ng-class="{'invalid_control': gasto_bibliografico.presupuesto_conadi_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.presupuesto_conadi_invalido"/>
                            							</td> 
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            								<input type="number" name="gasto_bibliografico_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}"
                            								ng-model="gasto_bibliografico.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_bibliograficos(gasto_bibliografico, 'otro', entidad_presupuesto.id)"
                            								class="form-control" ng-class="{'invalid_control': gasto_bibliografico.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                            							</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_bibliografico_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_bibliografico.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_bibliografico(gasto_bibliografico)"
                                                                    is-open="gasto_bibliografico.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_bibliografico.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_bibliografico.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_bibliografico.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_bibliografico.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                                     							
                            							<td>
                            							    {$ gasto_bibliografico.total $}
                            							</td>
                            							<td>
                            								<button type="button" class="btn btn-default" ng-click="remover_gasto_bibliografico(gasto_bibliografico)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            							</td>
                            						</tr>
                            						<tr ng-if="data.gastos_bibliograficos.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_bibliograficos.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_bibliograficos.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_bibliograficos.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>{$ data.totales_bibliograficos.total|currency:$ $}</td>
                            						</tr>
                            					</tbody>
                            				</table>                            
                            			</div>
                            		</div>
                            	</div>
                            </div>{{--./Gastos Recursos bibliografricos--}}                                                
                            
                            {{--Gastos educativos digitales--}}
                            <div class="panel panel-default">
                            	<div class="panel-heading" role="tab" id="head_recursos_educativos_digitales">
                            		<h4 class="panel-title">
                            			<a role="button" data-toggle="collapse" href="#body_educativos_digitales" aria-expanded="true" aria-controls="head_recursos_educativos_digitales">
                            				<span class="glyphicon glyphicon-plus"></span>&nbsp;Recursos educativos digitales
                            			</a>
                            		</h4>
                            	</div>
                            	<div id="body_educativos_digitales" class="panel-collapse collapse" role="tabpanel" aria-labelledby="head_recursos_educativos_digitales">
                            		<div class="panel-body">
                            			<div class="row">
                            				<div class="col-xs-12 col-sm-6 col-md-4">
                            					<button type="button" class="btn btn-primary btn-block" ng-click="agregar_recurso_digital()">Agregar recurso educativo digital<i class="fa fa-plus" aria-hidden="true"></i></button>
                            				</div>
                            			</div>
                            			<br />
                            			<div class="table-responsive" id="contenedor_recursos_digitales">
                            				<table class="table table-hover table-bordered">
                            					<thead>
                            						<tr>
                            							<th>N°</th>
                            							<th>Título</th>
                            							<th>Justificación</th>
                            							<th>UCC</th>
                            							<th>CONADI</th>
                            							<th ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">{$ entidad_presupuesto.nombre $}</th>
                            							<th>Fecha de ejecución</th>
                            							<th>Total</th>
                            							<th>Remover</th>
                            						</tr>
                            					</thead>
                            					<tbody>
                            						<tr ng-if="data.gastos_digitales.length==0">
                            							<td colspan="{$ 8 + data.financiadores_seleccionados.length $}" class="text-left">Sin gastos de recursos educativos digitales</td>
                            						</tr>                            			        
                            						<tr ng-repeat="gasto_digital in data.gastos_digitales">
                            							<td>{$ $index + 1 $}</td>
                            							<td>
                            								<input type="text" name="gasto_digital_nombre_{$ $index $}" 
                            								ng-model="gasto_digital.titulo" ng-change="validar_titulo_gasto_digital(gasto_digital)"
                            								class="form-control" ng-class="{'invalid_control': gasto_digital.titulo_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.titulo_invalido"/>
                            							</td>
                            							<td>
                            								<input type="text" name="gasto_digital_justificacion_{$ $index $}" 
                            								ng-model="gasto_digital.justificacion" ng-change="validar_justificacion_gasto_digital(gasto_digital)"
                            								class="form-control" ng-class="{'invalid_control': gasto_digital.justificacion_invalido}"
                            								uib-tooltip="Longitud mínima de 5 caracteres y máxima de 150" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.justificacion_invalido"/>
                            							</td>                                   
                            							<td>
                            								<input type="number" name="gasto_digital_presupuesto_ucc_{$ $index $}" 
                            								ng-model="gasto_digital.presupuesto_ucc" ng-change="suma_totales_digitales(gasto_digital, 'ucc')"
                            								class="form-control" ng-class="{'invalid_control': gasto_digital.presupuesto_ucc_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.presupuesto_ucc_invalido"/>
                            							</td>                                                                                       
                            							<td>
                            								<input type="number" name="gasto_digital_presupuesto_conadi_{$ $index $}" 
                            								ng-model="gasto_digital.presupuesto_conadi" ng-change="suma_totales_digitales(gasto_digital, 'conadi')"
                            								class="form-control" ng-class="{'invalid_control': gasto_digital.presupuesto_conadi_invalido}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.presupuesto_conadi_invalido"/>
                            							</td> 
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            								<input type="number" name="gasto_digital_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ $parent.$index $}" 
                            								ng-model="gasto_digital.otras_entidades_presupuesto[entidad_presupuesto.id]" ng-change="suma_totales_digitales(gasto_digital, 'otro', entidad_presupuesto.id)"
                            								class="form-control" ng-class="{'invalid_control': gasto_digital.presupuesto_externo_invalido[entidad_presupuesto.id]}"
                            								uib-tooltip="Cantidad mínima debe ser cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.presupuesto_externo_invalido[entidad_presupuesto.id]"/>
                            							</td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="gasto_digital_fecha_ejecucion_{$ $index $}"
                                                                    ng-model="gasto_digital.fecha_ejecucion" ng-change="validar_fecha_ejecucion_gasto_digital(gasto_digital)"
                                                                    is-open="gasto_digital.show_datepicker_fecha_ejecucion"
                                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha ejecución"
                                                                    ng-click="gasto_digital.show_datepicker_fecha_ejecucion=true"
                                                                    ng-readonly="true"
                                                                    class="form-control white-readonly" ng-class="{'invalid_control': gasto_digital.fecha_ejecucion_invalido}"
                                                                    uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_digital.fecha_ejecucion_invalido"/>
                                                                    <span class="input-group-addon btn btn-default" ng-click="gasto_digital.show_datepicker_fecha_ejecucion=true">
                                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                                    </span>
                                                            </div>                                                            
                                                        </td>                              							
                            							<td>
                            							    {$ gasto_digital.total $}
                            							</td>
                            							<td>
                            								<button type="button" class="btn btn-default" ng-click="remover_gasto_digital(gasto_digital)"><i class="fa fa-times" aria-hidden="true"></i></button>
                            							</td>
                            						</tr>
                            						<tr ng-if="data.gastos_digitales.length>0">
                            							<td colspan="3" class="text-right"><strong>Total</strong></td>
                            							<td>{$ data.totales_digitales.ucc|currency:$ $}</td>
                            							<td>{$ data.totales_digitales.conadi|currency:$ $}</td>
                            							<td ng-repeat="entidad_presupuesto in data.entidades_presupuesto_seleccionadas">
                            							    {$ data.totales_digitales.otras_entidades_presupuesto[entidad_presupuesto.id]|currency:$ $}
                            							</td>
                            							<td>&nbsp;</td>
                            							<td>{$ data.totales_digitales.total|currency:$ $}</td>
                            						</tr>
                            					</tbody>
                            				</table>                            
                            			</div>
                            		</div>
                            	</div>
                            </div>{{--./Gastos recursos educativos digitales--}}                                                                        
                            
                            <hr />
                            <div class="row">
                    	        <div class="col-xs-12 col-sm-6 col-md-4">
                    	            <button type="button" class="btn btn-default btn-block" ng-click="regresar_productos()">
                    	                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;Regresar a productos
                    	            </button>
                    	        </div>                        	        
                    	        <div class="col-xs-12">&nbsp;</div>                    		                                        
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_cargar_documentos()">
                                        Cargar documentos del proyecto&nbsp;<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div> {{--Contenido tab gastos--}}
                        
                        {{--Contenido tab adjuntos--}}
                        <div id="contenido_adjuntos" class="tab-pane fade"
                        ng-controller="adjuntos_proyecto_controller">
                            <br />
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
                                                    	ngf-select ngf-pattern=".xlsx,.xltm,.xlsm,.xls" ngf-max-size="20MB"
                                                    	ng-model="data.documento_presupuesto"
                                                    	ngf-change="cambia_documento_presupuesto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                                    	uib-tooltip="El archivo a cargar debe seguir el formato de presupuesto, formato Excel y máximo de 20MB" tooltip-enable="true"
                                                    	class="form-control" ng-class="{'invalid_control': data.documento_presupuesto_invalido}"/>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
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
                                                    <label for="documento_presentacion_proyecto">Cargar aquí el documento de presentación</label>
                                                    <input id="presentacion_proyecto" type="file" name="presentacion_proyecto"
                                                    	ngf-select ngf-pattern=".doc,.docx,.dotx,.DOC,.DOCX" ngf-max-size="20MB"
                                                    	ng-model="data.documento_presentacion_proyecto"
                                                    	ngf-change="cambia_documento_presentacion_proyecto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                                    	uib-tooltip="El archivo a cargar debe seguir el formato de presentación de proyecto, formato Word y máximo de 20MB" tooltip-enable="true"
                                                    	class="form-control" ng-class="{'invalid_control': data.documento_presentacion_proyecto_invalido}"/>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
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
                                                    <label for="documento_acta_inicio">Cargar aquí el documento de acta de inicio</label>
                                                    <input id="acta_inicio" type="file" name="acta_inicio"
                                                    	ngf-select ngf-pattern=".doc,.docx,.dotx,.DOC,.DOCX,.w" ngf-max-size="20MB"
                                                    	ng-model="data.documento_acta_inicio"
                                                    	ngf-change="cambia_documento_acta_inicio($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                                                    	uib-tooltip="El archivo a cargar debe seguir el formato de acta de inicio, formato Excel y máximo de 20MB" tooltip-enable="true"
                                                    	class="form-control" ng-class="{'invalid_control': data.documento_acta_inicio_invalido}"/>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-6">
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
                        </div> {{--Contenido tab adjuntos--}}
                        
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
    
    {{--Modal entidades / grupos investigacion--}}
    <script type="text/ng-template" id="modal_grupos_investigacion.html">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">Grupos de investigación y/o entidades que participan</h3>
        </div>
        <div class="modal-body" id="modal-body">
            <div class="table-responsive" style="max-height:450px;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Entidad / grupo de investigación</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="entidad_grupo in entidades_grupos">
                            <td>{$ $index+1 $}</td>
                            <td>{$ entidad_grupo.nombre $}</td>
                            <td>{$ entidad_grupo.rol $}</td>
                        </tr>
                        <tr ng-if="entidades_grupos.length==0">
                            <td class="text-center">Sin entidades / grupos de investigación añadidos</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" ng-click="cerrar()">Cerrar</button>
        </div>
    </script>

@stop <!--Stop section 'contenido'-->

@section('post_scripts')
    @if(isset($post_scripts))
        @foreach($post_scripts as $script) 
            <script type="text/javascript" src="/app/js/{{ $script }}"></script>
        @endforeach
    @endif
    <script type="text/javascript">
        sgpi_app.value('id_usuario', {{ Auth::user()->id }});
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
