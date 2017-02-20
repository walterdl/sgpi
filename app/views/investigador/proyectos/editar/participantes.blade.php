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
        .ps-container > .ps-scrollbar-x-rail{
            opacity: 0.7 !important;
        }        
        .lightgray-bg{
            background-color: #D3D3D3;
        }
        .td-nuevo-participante{
            padding-left: 8px; 
            padding-top: 15px; 
            padding-bottom: 15px; 
            padding-right: 0px;            
        }
        .panel-nuevo-participante{
            margin: 0; 
            box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);            
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
                <a href="#">Editar participantes</a>
            </li>            
        </ol>
        <br />
    </section>
    
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_participantes_controller">
        <div class="box">
            <div class="box-header with-border">
                <h3>Edición de participantes de proyecto</h3>
            </div>
            <div class="box-body">    
                <form id="_form_" action="/proyectos/editar/post_participantes" method="POST">
                    <div ng-hide="true" id="contenedor_ids_participantes_a_eliminar"></div>                    
                    <input type="hidden" name="id_proyecto" value="{{ $id_proyecto }}"/>
                	<input type="hidden" name="cantidad_participantes" value="{$ investigadores.length $}"/>
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
                                                    <input id="input_buscar_id" type="number" min="1" ng-model="identificacion_a_buscar" ng-change="data.msj_label_busqueda_id=''" class="form-control">
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
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            		            <label for="nombres_nuevo_participante">
                            		                Nombres <span class="error-text" ng-show="nombres_nuevo_participante_invalido">Longitud mínima de 3 caracteres y máxima de 200</span>
                            		            </label>
                            		            <input type="text" id="nombres_nuevo_participante" ng-model="nombres_nuevo_participante" ng-change="validar_nombres_nuevo_participante()"
                            		            class="form-control"
                            		            ng-class="{'invalid_control': nombres_nuevo_participante_invalido}"
                            		            ng-readonly="datos_basicos_persona_recuperados"/>
                            		        </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            		            <label for="apellidos_nuevo_participante">
                            		                Apellidos <span class="error-text" ng-show="apellidos_nuevo_participante_invalido">Longitud mínima de 3 caracteres y máxima de 200</span>
                            		            </label>
                            		            <input type="text" id="apellidos_nuevo_participante" ng-model="apellidos_nuevo_participante" ng-change="validar_apellidos_nuevo_participante()"
                            		            class="form-control"
                            		            ng-class="{'invalid_control': apellidos_nuevo_participante_invalido}"
                            		            ng-readonly="datos_basicos_persona_recuperados"/>
                            		        </div>
                            		     </div>                    		     
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            		            <label for="identificacion_nuevo_participante">
                            		                Identificación
                            		            </label>
                            		            <input type="number" id="identificacion_nuevo_participante" ng-model="identificacion_nuevo_participante" ng-change="validar_identificacion_nuevo_participante()"
                            		            class="form-control"
                            		            ng-readonly="true"/>
                            		        </div>
                            		     </div>                                                		     
                        				<div class="col-xs-12 col-sm-6 col-md-4">
                        				    <div class="form-group">
                            					<label for="formacion_nuevo_participante">
                            					    Formación <span class="error-text" ng-show="formacion_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                            					</label>
                            					<ui-select id="formacion_nuevo_participante" theme="bootstrap"
                            					ng-model="data.formacion_nuevo_participante" ng-change="validar_formacion_nuevo_participante()"
                            					ng-required="true" ng-disabled="datos_basicos_persona_recuperados"
                            					ng-class="{'invalid_control': formacion_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected $}</ui-select-match>
                            						<ui-select-choices repeat="item in formaciones | filter: $select.search">
                            							<div ng-bind-html="item | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>
                        				    </div>
                        				</div>                             
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            					<label for="rol">
                            					    Rol en el proyecto <span class="error-text" ng-show="rol_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                            					</label>
                            					<ui-select theme="bootstrap" 
                            					ng-model="data.rol_nuevo_participante" ng-change="cambia_rol_proyecto_nuevo_participante()"
                            					ng-required="true"
                            					ng-class="{'invalid_control': rol_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                            						<ui-select-choices repeat="item in roles | filter: $select.search">
                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>    
                            		        </div>
                            		     </div>                            				
                        				<div class="col-xs-12 col-sm-6 col-md-4">
                        				    <div class="form-group">
                            					<label for="tipo_identificacion_nuevo_participante">
                            					    Tipo de identificación <span class="error-text" ng-show="tipo_id_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                            					</label>
                            					<ui-select theme="bootstrap"
                            					ng-model="data.tipo_identificacion_nuevo_participante" ng-change="validar_tipo_id_nuevo_participante()"
                            					ng-required="true" ng-disabled="datos_basicos_persona_recuperados"
                            					ng-class="{'invalid_control': tipo_id_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                            						<ui-select-choices repeat="item in tipos_identificacion | filter: $select.search">
                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>
                        				    </div>
                        				</div>                                		     
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            					<label for="sexo">
                            					    Sexo <span class="error-text" ng-show="sexo_nuevo_participante_invalido">Campo requerido. Elegir una opción</span>
                            					</label>
                            					<ui-select theme="bootstrap"  
                            					ng-model="data.sexo_nuevo_participante" ng-change="validar_sexo_nuevo_participante()"
                            					ng-require="true" ng-disabled="datos_basicos_persona_recuperados"
                            					ng-class="{'invalid_control': sexo_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                            						<ui-select-choices repeat="item in sexos | filter: $select.search">
                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>
                            		        </div>
                            		     </div>                    		                         		                                				
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            		            <label for="edad_nuevo_participante">
                            		                Edad <span class="error-text" ng-show="edad_nuevo_participante_invalido">Edad mínima de 10</span>
                            		            </label>
                            		            <input type="number" id="edad_nuevo_participante" ng-model="edad_nuevo_participante" ng-change="validar_edad_nuevo_participante()"
                            		            class="form-control"
                            		            ng-class="{'invalid_control': edad_nuevo_participante_invalido}"
                            		            ng-readonly="datos_basicos_persona_recuperados"/>
                            		        </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
                            		        <div class="form-group">
                            		            <label for="email_nuevo_participante">
                            		                Email <span class="error-text" ng-show="email_nuevo_participante_invalido">Email inválido</span>
                            		            </label>
                            		            <input type="email" id="email_nuevo_participante" ng-model="email_nuevo_participante" ng-change="validar_email_nuevo_participante()"
                            		            class="form-control"
                            		            ng-class="{'invalid_control': email_nuevo_participante_invalido}"/>
                            		        </div>
                            		     </div>                                		     
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==4">
                            		        <div class="form-group">
                            		            <label for="institucion_nuevo_participante">Institución / entidad</label>
                            		            <input type="text" id="institucion_nuevo_participante" class="form-control white-readonly" 
                            		            value="Universidad Cooperativa de Colombia" 
                            		            ng-readonly="true" class="form-control white-readonly"/>
                            		        </div>
                            		     </div>                   
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==4">
                            		        <div class="form-group">
                            		            <label for="sede_nuevo_participante">
                            		                Sede <span class="error-text" ng-show="sede_nuevo_participante_invalido">Campo requerido. Elegir una sede</span>
                            		            </label>
                            					<ui-select theme="bootstrap"  
                            					ng-model="data.sede_nuevo_participante" ng-change="cambia_sede_nuevo_participante()"
                            					ng-require="true"
                            					ng-class="{'invalid_control': sede_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                            						<ui-select-choices repeat="item in sedes_ucc | filter: $select.search">
                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>                                		            
                            		        </div>
                            		     </div>                                                   		     
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==4">
                            		        <div class="form-group">
                            		            <label for="grupo_inv_nuevo_participante">
                            		                Grupo de investigación <span class="error-text" ng-show="grupo_inv_nuevo_participante_invalido">Campo requerido. ELegir una opción</span>
                            		            </label>
                            					<ui-select theme="bootstrap"  
                            					ng-model="data.grupo_inv_nuevo_participante" ng-change="cambia_grupo_inv_nuevo_participante()"
                            					ng-require="true"
                            					ng-class="{'invalid_control': grupo_inv_nuevo_participante_invalido}">
                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                            						<ui-select-choices repeat="item in grupos_inv_nuevo_participante | filter: $select.search">
                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                            						</ui-select-choices>
                            					</ui-select>                                		            
                            		        </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==4">
                            		        <div class="form-group">
                            		            <label for="facultad_nuevo_participante">
                            		                Facultad <span class="error-text" ng-show="facultad_nuevo_participante_invalido">Campo requerido. Elegir una facultad</span>
                            		            </label>
                            					<input type="text" id="facultad_nuevo_participante" ng-model="facultad_nuevo_participante.nombre" ng-change="validar_facultad_nuevo_participante()"
                            					class="form-control white-readonly"
                            					ng-readonly="true"
                            					ng-class="{'invalid_control': facultad_nuevo_participante_invalido}"/>
                            		        </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==5||data.rol_nuevo_participante.id==6">
                            		        <div class="form-group">
                            		            <label for="entidad_grupo_inv_externo_nuevo_participante">
                            		                Entidad / grupo de investigación co-ejecutor <span class="error-text" ng-show="entidad_externa_nuevo_participante_invalido">Longitud mínima de 5 caractéres y máximo de 150</span>
                            		            </label>
                            					<input type="text" id="entidad_grupo_inv_externo_nuevo_participante" ng-model="entidad_externa_nuevo_participante" ng-change="validar_entidad_externa_nuevo_participante()"
                            					class="form-control"
                            					ng-class="{'invalid_control': entidad_externa_nuevo_participante_invalido}"/>
                            		        </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4" ng-show="data.rol_nuevo_participante.id==6">
                            		        <div class="form-group">
                            		            <label for="programa_academico_nuevo_participante">
                            		                Programa académico <span class="error-text" ng-show="programa_academico_participante_invalido">Longitud mínima de 5 caractéres y máximo de 150</span>
                            		            </label>
                            					<input type="text" ng-model="programa_academico_nuevo_participante" ng-change="validar_programa_acad_nuevo_participante()"
                            					class="form-control"
                            					ng-class="{'invalid_control': programa_academico_participante_invalido}"/>
                            		        </div>
                            		     </div>
                            		     <div class="col-xs-12 col-sm-6 col-md-4">
                            		         <div class="form-group">
                            		            <label for="fecha_ejecucion_nuevo_participante">
                            		                Fecha de ejecución del gasto de personal <span class="error-text" ng-show="fecha_ejecucion_nuevo_participante_invalido">Campo requerido</span>
                            		            </label>                            		         
                                                <div class="input-group">
                                                    <input type="text"
                                                    ng-model="fecha_ejecucion_nuevo_participante" ng-change="validar_fecha_ejecucion_nuevo_participante()"
                                                    is-open="show_datepicker_fecha_ejecucion"
                                                    datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                    clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                    ng-click="show_datepicker_fecha_ejecucion=true"
                                                    ng-readonly="true"
                                                    class="form-control white-readonly" ng-class="{'invalid_control': fecha_ejecucion_nuevo_participante_invalido}"/>
                                                    <span class="input-group-addon btn btn-default" ng-click="show_datepicker_fecha_ejecucion=true">
                                                        <i class="glyphicon glyphicon-calendar"></i>
                                                    </span>
                                                </div>
                            		         </div>
                            		     </div>
                            		    <div class="col-xs-12 col-sm-6 col-md-4">
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
                        	<div style="max-height:550px;" id="contenedor_tabla_participantes">
                        		<table class="table table-striped">
                        			<tbody>	
                        				<tr ng-repeat="investigador in investigadores" class="nga-fast nga-stagger-fast nga-fade">
                        				    {{--Investigador pricipal--}}
                        					<td class="td-nuevo-participante" ng-if="investigador.es_investigador_principal">
                        						<div class="panel panel-default panel-nuevo-participante">
                        							<div class="panel-heading" role="tab">
                        								<div class="alert alert-info" role="alert" ng-show="investigador.id_investigador!='nuevo'">
                        								    <p><i class="fa fa-info-circle" aria-hidden="true"></i> Investigador principal del proyecto</p>
                        								</div>
                        								<div class="row is-flex">
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="nombres_inv_principal">Nombres</label>
                        											<input type="text" id="nombres_inv_principal" class="form-control lightgray-bg" ng-model="investigador.nombres"
                        											ng-readonly="true">
                        										</div>
                        									</div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="apellidos_inv_principal">Apellidos</label>
                        											<input type="text" id="apellidos_inv_principal" class="form-control lightgray-bg" ng-model="investigador.apellidos"
                        											ng-readonly="true">
                        										</div>
                        									</div>                    	
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="identificacion_nuevo_participante">Identificación</label>
                        											<input type="text" id="identificacion_nuevo_participante" name="identificacion_investigador_principal" class="form-control lightgray-bg" ng-model="investigador.identificacion"
                        											ng-readonly="true">
                        										</div>
                        									</div>                    	                                        		     
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="formacion_inv_principal">Formación</label>
                        											<input type="text" id="formacion_inv_principal" class="form-control lightgray-bg" ng-model="investigador.formacion"
                        											ng-readonly="true">
                        										</div>
                        									 </div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="rol">Rol en el proyecto</label>
                        											<input type="text" class="form-control lightgray-bg" ng-model="investigador.nombre_rol"
                        											ng-readonly="true">
                        										</div>
                        									 </div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
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
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">				
                        												<label for="tipo_id_inv_principal">Tipo de identificación</label>
                        												<input type="text" id="tipo_id_inv_principal" class="form-control lightgray-bg" value="{$ investigador.nombre_tipo_identificacion $}"
                        												ng-readonly="true"/>
                        											</div>
                        										</div> {{--Tipo de identificación--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<label for="sexo_inv_principal">Sexo</label>
                        											<input type="text" class="form-control lightgray-bg" ng-if="investigador.sexo=='m'" value="Hombre" ng-readonly="true"/>
                        											<input type="text" class="form-control lightgray-bg" ng-if="investigador.sexo=='f'" value="Mujer" ng-readonly="true"/>
                        										 </div> {{--Sexo--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="edad_inv_principal">Edad</label>
                        												<input type="number" id="edad_inv_principal" class="form-control lightgray-bg" ng-model="investigador.edad"
                        												ng-readonly="true">
                        											</div>
                        										 </div> {{--Edad--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="email_inv_principal">Email</label>
                        												<input type="text" id="email_inv_principal" class="form-control lightgray-bg" ng-model="investigador.email"
                        												ng-readonly="true">
                        											</div>
                        										 </div> {{--email--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="sede_inv_principal">Institución / entidad</label>
                        												<input type="text" id="sede_inv_principal" class="form-control lightgray-bg" value="Universidad Cooperativa de Colombia"
                        												ng-readonly="true"/>
                        											</div>
                        										</div> {{--Sede UCC--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="institucion_inv_principal">Sede</label>
                        												<input type="text" id="institucion_inv_principal" class="form-control lightgray-bg" value="{$ buscar_nombre_sede_x_id_grupo_inv(investigador.id_grupo_investigacion_ucc) $}"
                        												ng-readonly="true"/>
                        											</div>
                        										</div>{{--Grupo de investigación--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="grupo_inv_principal">Grupo de investigación</label>
                        												<input type="text" id="grupo_inv_principal" class="form-control lightgray-bg" value="{$ buscar_nombre_grupo_x_id_grupo_inv(investigador.id_grupo_investigacion_ucc) $}"
                        												ng-readonly="true"/>
                        											</div>
                        										</div>{{--Facultad--}}
                        										<div class="col-xs-12 col-sm-6 col-md-4">
                        											<div class="form-group">
                        												<label for="facultad_inv_principal">Facultad</label>
                        												<input type="text" id="facultad_inv_principal" class="form-control lightgray-bg" value="{$ buscar_nombre_facultad_x_id_grupo_inv(investigador.id_grupo_investigacion_ucc) $}"
                        												ng-readonly="true"/>
                        											</div>
                        										</div>{{--Facultad grupo de investigación--}}                                                                    
                        									</div>
                        								</div>
                        							</div>
                        						</div>
                        					</td> {{--./Investigador principal--}}
                        				    {{--Otros participantes--}}
                        					<td class="td-nuevo-participante" ng-if="!investigador.es_investigador_principal">
                        						<div class="panel panel-default panel-nuevo-participante">
                        							<div class="panel-heading" role="tab">
                        								<!--nombres, apellidos, identificacion, formacion, rol, btnMasInfo, removerBtn-->
                        								{{--Información sobre el tipo de participante; si es existente en la BD, si se pueden editar sus datos o las causas de no poderse--}}
                        								<!--
                        								Se debe informar al usuario que:
                        								-El investigador ya hace parte del proyecto
                        								-Que no se pueden editar sus datos ya que el investigador cuenta con usuario en el sistema
                        								-Que no se puede eliminar porque su gasto de personal ya tiene desembolso cargado
                        								-Que no se puede eliminar porque es encargado de un productos
                        								-->
                        								<div class="alert alert-info" role="alert" ng-show="investigador.id_investigador!='nuevo'">
                        								    <p><i class="fa fa-info-circle" aria-hidden="true"></i> El particicipante hace parte del proyecto actualmente</p>
                        								    <p ng-if="investigador.tiene_usuario">Los datos básicos del participante no se pueden editar ya que cuenta con usuario en el sistema</p>
                        								    <p ng-if="(investigador.tiene_desembolso | boolean) || (investigador.es_encargado_de_algun_producto | boolean)">No se puede remover el participante porque:</p>
                        								    <ul ng-if="(investigador.tiene_desembolso | boolean) || (investigador.es_encargado_de_algun_producto | boolean)">
                        								        <li ng-if="investigador.tiene_desembolso | boolean">El gasto del personal ya tiene un desembolso cargado</li>
                        								        <li ng-if="investigador.es_encargado_de_algun_producto | boolean">El participante es encargado de un producto del proyecto</li>
                        								    </ul>
                        								</div>
                        								<div class="alert alert-info" role="alert" ng-show="investigador.id_investigador=='nuevo' && investigador.tiene_usuario">
                        								    <p>Los datos básicos del participante no se pueden editar ya que cuenta con usuario en el sistema</p>
                        								</div>
                        								<input type="hidden" name="fecha_ejecucion_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.fecha_ejecucion $}">
                        								<div class="row is-flex">
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="nombres_{$ investigador.id_investigador $}_{$ $index $}">Nombres <span class="error-text" ng-show="investigador.nombres_invalidos">Logitud mínima de 3 caractéres y máximo de 200</span></label>
                        											<input type="text" id="nombres_{$ investigador.id_investigador $}_{$ $index $}" name="nombres_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.nombres" ng-change="validar_nombres_participante_agregado(investigador)"
                        											ng-readonly="investigador.tiene_usuario || investigador.es_nuevo_participante" class="form-control"
                        											ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.nombres_invalidos}"/>
                        										</div>
                        									</div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="apellidos_{$ investigador.id_investigador $}_{$ $index $}">Apellidos <span class="error-text" ng-show="investigador.apellidos_invalidos">Logitud mínima de 3 caractéres y máximo de 200</span></label>
                        											<input type="text" id="apellidos_{$ investigador.id_investigador $}_{$ $index $}" name="apellidos_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.apellidos" ng-change="validar_apellidos_participante_agregado(investigador)"
                        											ng-readonly="investigador.tiene_usuario || investigador.es_nuevo_participante" class="form-control"
                        											ng-class="{'lightgray-bg': investigador.tiene_usuario, 'invalid_control': investigador.apellidos_invalidos}"/>
                        										</div>
                        									</div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label for="identificacion_{$ investigador.id_investigador $}_{$ $index $}">Identificación <span class="error-text" ng-show="investigador.identificacion_invalido">La identificación ya está ocupada o es inválida</span></label>
                        											<input type="number" id="identificacion_{$ investigador.id_investigador $}_{$ $index $}" name="identificacion_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.identificacion" ng-change="validar_identificacion_participante_agregado(investigador)"
                        											ng-readonly="investigador.tiene_usuario || investigador.es_nuevo_participante" class="form-control"
                        											ng-class="{'lightgray-bg': investigador.tiene_usuario, 'invalid_control': investigador.identificacion_invalido}"/>
                        										</div>
                        									</div>             		
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<label for="formacion_{$ investigador.id_investigador $}_{$ $index $}">Formación <span class="error-text" ng-show="investigador.formacion_invalido">Campo requerido. Elegir una opción</span></label>
                                            					<ui-select id="formacion_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                            					ng-model="investigador.formacion" ng-change="validar_formacion_participante_agregado(investigador)"
                                            					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                            					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.formacion_invalido}">
                                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected $}</ui-select-match>
                                            						<ui-select-choices repeat="item in formaciones | filter: $select.search">
                                            							<div ng-bind-html="item | highlight: $select.search"></div>
                                            						</ui-select-choices>
                                            					</ui-select>
                                            					<input type="hidden" name="formacion_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.formacion $}">
                        									</div> 		
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<label for="rol_{$ investigador.id_investigador $}_{$ $index $}">Rol en el proyecto <span class="error-text" ng-show="investigador.rol_invalido">Campo requerido. Elegir una opción</span></label>
                                            					<ui-select id="rol_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                            					ng-model="investigador.rol" ng-change="validar_rol_participante_agregado(investigador)"
                                            					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                            					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.rol_invalido}">
                                            						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                            						<ui-select-choices repeat="item in roles | filter: $select.search">
                                            							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                            						</ui-select-choices>
                                            					</ui-select>
                                            					<input type="hidden" name="rol_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.rol.id $}">
                        									</div>
                        									<div class="col-xs-12 col-sm-6 col-md-4">
                        										<div class="form-group">
                        											<label>&nbsp;</label>
                        											<button type="button" data-toggle="collapse" data-target="#collapse_{$ $index $}" aria-expanded="true" aria-controls="collapse_{$ $index $}" class="btn btn-primary btn-block">
                        												Mas información <i class="fa fa-caret-down" aria-hidden="true"></i>
                        											</button>
                        										</div>
                        									</div>
                        									<div class="col-xs-12 col-sm-6 col-md-4" ng-if="!investigador.tiene_desembolso && !investigador.es_encargado_de_algun_producto">
                        										<div class="form-group">                                        					
                        											<label>&nbsp;</label>
                        											<button type="button" class="btn btn-default btn-block" ng-click="remover_participante(investigador)">
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
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4">
                        											<label for="tipo_id_{$ investigador.id_investigador $}_{$ $index $}">Tipo de identificación <span class="error-text" ng-show="investigador.tipo_id_invalido">Campo requerido. Elegir una opción</span></label>
                                                					<ui-select id="tipo_id_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                                					ng-model="investigador.tipo_identificacion" ng-change="validar_tipo_id_participante_agregado(investigador)"
                                                					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                                					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.tipo_id_invalido}">
                                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                                						<ui-select-choices repeat="item in tipos_identificacion | filter: $select.search">
                                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                                						</ui-select-choices>
                                                					</ui-select>          
                                                					<input type="hidden" name="tipo_id_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.tipo_identificacion.id $}">
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4">
                        											<label for="sexo_{$ $index $}">Sexo <span class="error-text" ng-show="investigador.sexo_invalido">Campo requerido. Elegir una opción</span></label>
                                                					<ui-select id="tipo_id_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                                					ng-model="investigador.sexo" ng-change="validar_sexo_participante_agregado(investigador)"
                                                					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                                					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.sexo_invalido}">
                                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                                						<ui-select-choices repeat="item in sexos | filter: $select.search">
                                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                                						</ui-select-choices>
                                                					</ui-select>          
                                                					<input type="hidden" name="sexo_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.sexo.id $}">
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4">
                        											<label for="edad_{$ investigador.id_investigador $}_{$ $index $}">Edad <span class="error-text" ng-show="investigador.edad_invalido">Campo requerido. Elegir una opción</span></label>
                        											<input type="number" id="edad_{$ investigador.id_investigador $}_{$ $index $}" name="edad_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.edad" ng-change="validar_edad_participante_agregado(investigador)"
                        											ng-readonly="investigador.tiene_usuario || investigador.es_nuevo_participante" class="form-control"
                        											ng-class="{'lightgray-bg': investigador.tiene_usuario, 'invalid_control': investigador.edad_invalido}"/>
                        										</div>                                										
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4">
                        											<label for="email_{$ investigador.id_investigador $}_{$ $index $}">Email <span class="error-text" ng-show="investigador.edad_invalido">Email inválido</span></label>
                        											<input type="text" id="email_{$ investigador.id_investigador $}_{$ $index $}" name="email_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.email" ng-change="validar_email_participante_agregado(investigador)"
                        											ng-readonly="investigador.tiene_usuario || investigador.es_nuevo_participante" class="form-control"
                        											ng-class="{'lightgray-bg': investigador.tiene_usuario, 'invalid_control': investigador.email_invalido}"/>
                        										</div>			
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==4">
                        											<label for="ucc_{$ $index $}">Institución / entidad</label>
                        											<input type="text" id="ucc_{$ $index $}" value="Universidad Cooperativa de Colombia" 
                        											ng-readonly="true" class="form-control lightgray-bg"/>
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==4">
                        											<label for="sede_{$ investigador.id_investigador $}_{$ $index $}">Sede</label>
                                                					<ui-select id="sede_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                                					ng-model="investigador.sede" ng-change="validar_sede_participante_agregado(investigador)"
                                                					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                                					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.sede_invalido}">
                                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                                						<ui-select-choices repeat="item in sedes_ucc | filter: $select.search">
                                                							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                                                						</ui-select-choices>
                                                					</ui-select>
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==4">
                        											<label for="grupo_inv_{$ investigador.id_investigador $}_{$ $index $}">Grupo de investigación</label>
                                                					<ui-select id="grupo_inv_{$ investigador.id_investigador $}_{$ $index $}" theme="bootstrap"
                                                					ng-model="investigador.grupo_investigacion_ucc" ng-change="validar_grupo_inv_participante_agregado(investigador)"
                                                					ng-disabled="investigador.tiene_usuario || investigador.es_nuevo_participante"
                                                					ng-class="{'lightgray-bg': investigador.tiene_usuario || investigador.es_nuevo_participante, 'invalid_control': investigador.grupo_inv_invalido}">
                                                						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                                                						<ui-select-choices repeat="item in investigador.grupos_investigacion_para_select | filter: $select.search">
                                                							<div ng-bind-html="item | highlight: $select.search"></div>
                                                						</ui-select-choices>
                                                					</ui-select>                        											
                                                					<input type="hidden" name="grupo_inv_{$ investigador.id_investigador $}_{$ $index $}" value="{$ investigador.grupo_investigacion_ucc.id $}" />
                        										</div> 
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==4">
                        											<label for="facultad_{$ investigador.id_investigador $}_{$ $index $}">Facultad / dependencia</label>
                        											<input type="text" id="facultad_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.facultad_ucc" 
                        											ng-readonly="true" class="form-control lightgray-bg"/>
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==5 || investigador.rol.id==6">
                        											<label for="entidad_externa_{$ investigador.id_investigador $}_{$ $index $}">Entidad / grupo de investigación co-ejecutor</label>
                        											<input type="text" id="entidad_externa_{$ investigador.id_investigador $}_{$ $index $}" name="entidad_externa_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.entidad_grupo_inv_externo" ng-change="validar_entidad_externa_participante_agregado(investigador)"
                        											ng-readonly="investigador.es_nuevo_participante"
                        											class="form-control"
                        											ng-class="{'invalid_control': investigador.entidad_o_grupo_investigacion_invalido}"/>
                        										</div>
                        										<div class="form-group col-xs-12 col-sm-6 col-md-4" ng-if="investigador.rol.id==6">
                        											<label for="programa_academico_{$ investigador.id_investigador $}_{$ $index $}">Programa académico</label>
                        											<input type="text" id="programa_academico_{$ investigador.id_investigador $}_{$ $index $}" name="programa_academico_{$ investigador.id_investigador $}_{$ $index $}" 
                        											ng-model="investigador.programa_academico" ng-change="validar_programa_academico_participante_agregado(investigador)"
                        											ng-readonly="investigador.es_nuevo_participante"
                        											class="form-control"
                        											ng-class="{'invalid_control': investigador.programa_academico_invalido}"/>
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
                        <br />
                        <p><strong>Cantidad total de participantes: </strong> {$ investigadores.length $}</p>
                        <br />
                        <div class="row">
                	        <div class="col-xs-12 col-sm-6">
                	            <button type="button" class="btn btn-primary btn-block" ng-click="show_modal_grupos_investigacion()">
                	                Ver entidades / grupos de investigación participantes
                	            </button>
                	        </div>                                    
                        </div>
                        <br />
                		<hr />
                	    <div class="row">
                	        <div class="col-xs-12 col-sm-6 col-md-4">
                	            <button type="button" class="btn btn-primary btn-block" ng-click="guardar()">
                	                Guardar cambios <i class="fa fa-floppy-o" aria-hidden="true"></i>
                	            </button>
                	            <input type="submit" ng-hide="true" id="btn_submit_form">
                	        </div>                        	        
                	        <div class="col-xs-12 col-sm-6 col-md-4">
                	            <a href="/proyectos/listar" type="button" class="btn btn-default btn-block">
                	                Cancelar
                	            </a>
                	        </div>
                	    </div>	
                    </div>
                </form>
            </div>
    	    <div class="overlay" ng-show="visibilidad.show_velo_general">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion_general">
            			<!--Contenido generado desde crear_usuarios_controller-->
                    </div>
                </div>    		    
    		</div>            
        </div>
    </section>
    
    {{--Modal de presentacion de grupos y entidades que participan--}}
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
