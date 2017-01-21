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
                     
                        {{--Contenito tab participantes--}}
                        <div id="contenido_participantes" class="tab-pane fade active in" 
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
                                				<tr ng-repeat="participante in data.info_investigadores_usuario" class="nga-fast nga-stagger-fast nga-fade">
                                				    
     
                                				    {{--Investigador pricipal--}}
                                					<td style="padding-left: 8px; padding-top: 15px; padding-bottom: 15px; padding-right: 0px;" ng-if="participante.investigador_principarl==1">
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
                                												<label for="institucion_inv_principal">Grupo de Investigacion</label>
                                												<input type="text" id="institucion_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_grupo_inv $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div>{{--Grupo de investigación--}}
                                										<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<div class="form-group">
                                												<label for="facultad_inv_principal">Facultad</label>
                                												<input type="text" id="facultad_inv_principal" class="form-control white-readonly" value="{$ data.info_investigador_principal.nombre_facultad $}"
                                												ng-readonly="true"/>
                                											</div>
                                										</div>{{--Facultad grupo de investigación--}}                                                                    
                                									</div>
                                								</div>
                                							</div>
                                						</div>
                                					<!--</td> {{--./Investigador principal--}}-->
                                					
                                				    {{--Otros participantes--}}
                                					<td style="padding-left: 8px; padding-top: 15px; padding-bottom: 15px; padding-right: 0px;" ng-if="participante.investigador_principarl==0">
                                						<div class="panel panel-default" style="margin: 0; box-shadow: 1px 3px 23px -5px rgba(0,0,0,0.75);">
                                							<div class="panel-heading" role="tab">
                                								<!--nombres, apellidos, identificacion, formacion, rol, btnMasInfo, removerBtn-->
                                								<div class="row is-flex">
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="nombres_{$ $index $}">Nombres</label>
                                											<input type="text" id="nombres_{$ $index $}" name="nombres_{$ $index $}" ng-model="participante.info_investigador.nombres" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="apellidos_{$ $index $}">Apellidos</label>
                                											<input type="text" id="apellidos_{$ $index $}" name="apellidos_{$ $index $}" ng-model="participante.info_investigador.apellidos" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                									</div>
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<div class="form-group">
                                											<label for="identificacion_{$ $index $}">Identificación</label>
                                											<input type="number" min="1" id="identificacion_{$ $index $}" name="identificacion_{$ $index $}" ng-model="participante.info_investigador.identificacion" 
                                											ng-readonly="true" class="form-control white-readonly" />
                                										</div>
                                									</div>             		
                                									<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<label for="formacion_{$ $index $}">Formación</label>
                                										<input type="text" id="formacion_{$ $index $}" name="formacion_{$ $index $}" ng-model="participante.info_investigador.formacion" 
                                										ng-readonly="true" class="form-control white-readonly"/>
                                									</div> 		
                                									<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                										<label for="rol_{$ $index $}">Rol en el proyecto</label>
                                										<input type="text" id="rol_{$ $index $}" ng-model="participante.datos_extras.rol.nombre" 
                                										ng-readonly="true" class="form-control white-readonly"/>
                                										<input type="hidden" name="id_rol_{$ $index $}" value="{$ participante.datos_extras.rol.id $}"/>
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
                                											<input type="text" id="tipo_identificacion_{$ $index $}" ng-model="participante.info_investigador.tipo_identificacion.nombre" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" name="tipo_identificacion_{$ $index $}" value="{$ participante.info_investigador.tipo_identificacion.id $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="sexo_{$ $index $}">Sexo</label>
                                											
                                											<input type="text" id="sexo_{$ $index $}" ng-if="participante.info_investigador.sexo == 'm' " value="Hombre" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											
                                											<input type="text" id="sexo_{$ $index $}" ng-if="participante.info_investigador.sexo == 'f'" value="Mujer" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											
                                											<input type="hidden" id="id_sexo_{$ $index $}" name="sexo_{$ $index $}" value="{$ participante.info_investigador.id_sexo $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="edad_{$ $index $}">Edad</label>
                                											<input type="text" id="edad_{$ $index $}" name="edad_{$ $index $}" ng-model="participante.info_investigador.edad" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>                                										
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                											<label for="email_{$ $index $}">Email</label>
                                											<input type="text" id="email_{$ $index $}" name="email_{$ $index $}" ng-model="participante.datos_extras.email" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>			
                                										
                                										
                                										<!--/////otros datos /////////////////////////////////////////////////////////////////-->
                                										
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==4">
                                											<label for="ucc_{$ $index $}">Institución / entidad</label>
                                											<input type="text" id="ucc_{$ $index $}" value="Universidad Cooperativa de Colombia" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==4">
                                											<label for="sede_{$ $index $}">Sede</label>
                                											<input type="text" id="sede_{$ $index $}" ng-model="participante.datos_extras.grupo.facultad.sede.ciudad" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_sede_{$ $index $}" name="sede_{$ $index $}" value="{$ participante.id_sede $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==4">
                                											<label for="grupo_inv_{$ $index $}">Grupo de investigación</label>
                                											<input type="text" id="grupo_inv_{$ $index $}" ng-model="participante.datos_extras.grupo.nombre" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_grupo_investigacion_{$ $index $}" name="grupo_investigacion_{$ $index $}" value="{$ participante.id_grupo_investigacion $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==4">
                                											<label for="facultad_{$ $index $}">Facultad / dependencia</label>
                                											<input type="text" id="facultad_{$ $index $}" ng-model="participante.datos_extras.grupo.facultad.nombre" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                											<input type="hidden" id="id_facultad_dependencia_{$ $index $}" name="facultad_dependencia_{$ $index $}" value="{$ participante.id_facultad_dependencia $}"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==5 || participante.datos_extras.rol.id==6">
                                											<label for="entidad_externa_{$ $index $}">Entidad co-ejecutor</label>
                                											<input type="text" id="entidad_externa_{$ $index $}" name="entidad_externa_{$ $index $}" ng-model="participante.datos_extras.entidad_o_grupo_investigacion" 
                                											ng-readonly="true" class="form-control white-readonly"/>
                                										</div>
                                										<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-if="participante.datos_extras.rol.id==6">
                                											<label for="programa_academico_{$ $index $}">Programa académico</label>
                                											<input type="text" id="programa_academico_{$ $index $}" name="programa_academico_{$ $index $}" ng-model="participante.datos_extras.programa_academico" 
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
                        	            <button type="button" class="btn btn-default btn-block" ng-click="mostrar_modal_grupos_investigacion()">
                        	                Ver entidades / grupos de investigación participantes
                        	            </button>
                        	        </div>                                    
                                </div>
                                
                                <br />
                        		<hr />
                        	    <div class="row">
                        
                        	        <div class="col-xs-12 col-sm-6 col-md-4">
                        	            <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_productos()">
                        	                Guardar Cambios&nbsp;
                        	            </button>
                        	        </div>
                        	    </div>	
                            </div>
                        </div> {{--Contenito tab participantes--}}

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
