@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        .posicionado{
            position: relative !important;
        }
        #velo_contenedor_datos_usuario{
            position: absolute;
            background-color: rgba(250,250,250,.7);
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 2;
        }
        #row_buscar_id{
            margin-left: -10px;
            margin-right: -10px;
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
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i><b></b></a></li> <i class="fa fa-chevron-right" aria-hidden="true"></i> <li><a href="/usuarios"><b>Usuarios</b></a></li>
            <i class="fa fa-chevron-right" aria-hidden="true"></i> Editar
        </ol>
        <br />
    </section>
    <section class="content" ng-controller="editar_usuarios_controller" ng-cloak>


        <div class="box box-default">
            
            <div class="box-header with-border">
                <h3>Actualizar usuario</h3>
            </div>
            
            <div class="box-body">
                
                <div class="container-sgpi">
                    <br />
                    
                    <!-- ---------------------Inicio del formulario de editar-->
                    <form action="/usuarios/actualizar_usuario" method="POST" name="form_actualizar_usuario" enctype="multipart/form-data">
                        <input type="hidden" name="token_integridad" ng-model="data.token_integridad" value="{$ data.token_integridad $}"/>
                    	
                    	<div id="contenedor_datos_usuario" class="posicionado">
                    	    <!----------------Datos básicos-->
                    		<fieldset>
                    			<legend>Datos básicos</legend>
                    			<div class="row is-flex sin-margen">
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="nombre">Nombres <span ng-show="data.btn_guardar_seleccionado && visibilidad.show_nombres_invalido" class="error-text">{$ data.msj_error_nombres $}</span></label>
                    					<input type="text" name="nombres" ng-model="data.personaEditar.nombres" ng-change="validar_nombres()" 
                    					class="form-control"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_nombres_invalido}"
                    					ng-required="true"/>
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="apellidos">Apellidos</label>
                    					<input type="text" name="apellidos" ng-model="data.personaEditar.apellidos" ng-change="validar_apellidos()" 
                    					class="form-control"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_apellidos_invalido}"
                    					ng-required="true"/>
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="identificacion">Identificación</label>
                    					<input type="number" ng-model="data.personaEditar.identificacion" class="form-control" ng-disabled="true"/>
                    					<input type="hidden" value="{$ data.personaEditar.identificacion $}" name="identificacion" class="form-control"/>
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="tipo_identificacion">Tipo de identificación</label>
                    					<ui-select theme="bootstrap" ng-model="data.tipo_identificacion" ng-change="validar_tipo_id()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_tipo_id_invalido}"
                    					ng-required="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.tipos_identificacion | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="tipo_identificacion" value="{$ data.tipo_identificacion.id $}"/>
                    				</div>    
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="sexo">Sexo</label>
                    					<ui-select theme="bootstrap"  
                    					ng-model="data.sexo" ng-change="validar_sexo()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_sexo_invalido}"
                    					ng-require="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.sexos | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="sexo" value="{$ data.sexo.id $}"/>
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="edad">Edad</label>
                    					<input type="number" name="edad" 
                    					class="form-control"
                    					ng-model="data.personaEditar.edad" ng-change="validar_edad()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_edad_invalido}"
                    					ng-required="true" min="0" max="100" />                				    
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="formacion">Formación</label>
                    					<ui-select theme="bootstrap"  
                    					ng-model="data.formacion" ng-change="validar_formacion()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_formacion_invalido}">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.formaciones | filter: $select.search">
                    							<div ng-bind-html="item | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="formacion" value="{$ data.formacion $}"/>
                    				</div>
                    				
                    			</div>
                    		</fieldset>
                    		<br />
                    		
                    		
                    		<!-----------------Datos del usuario-->
                    		<fieldset>
                    			<legend>Datos del usuario</legend>
                    			<div class="row is-flex sin-margen">
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="username">Nombre de usuario</label>
                    					
                    					<input type="text" name="username" class="form-control" 
                    					ng-model="data.usuarioEditar.username" ng-change="validar_username()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_username_invalido}"
                    					ng-required="true"/>
                    					
                    					<input type="hidden" value="{$ data.usuarioEditar_username $}" name="username_verificar"/>
                    				</div>
                    				<!--<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">-->
                    				<!--	<label for="password">Contraseña</label>-->
                    					
                    				<!--	<input type="password" name="password" class="form-control" -->
                    				<!--	ng-model="data.password" ng-change="validar_password()"-->
                    				<!--	ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_password_invalido}"-->
                    				<!--	ng-required="true"/>-->
                    					
                    				<!--	<input type="password" class="form-control" -->
                    				<!--	ng-model="data.repeat_password" ng-change="validar_password()"-->
                    				<!--	placeholder="Repetir contraseña"-->
                    				<!--	ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_password_invalido}"/>-->
                    				<!--</div>                			    -->
                    			    
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="email">Email</label>
                    					<input type="email" name="email" ng-model="data.usuarioEditar.email" ng-change="validar_email()"
                    					class="form-control" 
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_email_invalido}"
                    					ng-required="true"/>
                    				</div>       
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    					<label for="rol">Tipo de usuario</label>
                    					<ui-select theme="bootstrap" 
                    					ng-model="data.rol" ng-change="cambia_tipo_usuario()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_rol_invalido}"
                    					ng-required="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.roles | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="rol" value="{$ data.rol.id $}"/>
                    				</div>
                    				
                    				
                    				
                    				
                    				<!-- DATOS EXTRAS PENDIENTE ----------------------------->
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.show_categoria_inv">
                    					<label for="categoria_investigador">Categoría de investigador</label>
                    					<ui-select theme="bootstrap"  
                    					ng-model="data.categoria_investigador" ng-change="validar_categoria_inv()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_categoria_inv_invalido}"
                    					ng-required="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.categorias_investigador | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="categoria_investigador" value="{$ data.categoria_investigador.id $}"/>
                    				</div>   
                    				
                    				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3" ng-show="visibilidad.show_sede">
                    					<label for="sede">Sede de origen</label>
                    					<ui-select theme="bootstrap" 
                    					ng-model="data.sede" ng-change="cambia_sede()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_sede_invalido}" 
                    					ng-required="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.sedes | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="sede" value="{$ data.sede.id $}"/>
                    				</div>
                    				
                    				<div class="form-group col-xs-12 col-md-6" ng-show="visibilidad.show_grupo_inv">
                    					<label for="grupo_investigacion">Grupo de investigación</label>
                    					<ui-select theme="bootstrap" 
                    					ng-model="data.grupo_investigacion" ng-change="validar_grupo_inv()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_grupo_inv_invalido}"
                    					ng-required="true">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.grupos_investigacion_correspondientes | filter: $select.search">
                    							<p style="margin:0;font-size:15px;font-style:italic;" ng-bind-html="item.nombre | highlight: $select.search"></p>
                    							<p style="margin:0;"><strong>Area: </strong><span ng-bind-html="item.nombre_area"></span></p>
                    							<p style="margin:0;"><strong>Gran area: </strong><span ng-bind-html="item.nombre_gran_area"></span></p>
                    							<p style="margin:0;"><strong>Facultad: </strong><span ng-bind-html="item.facultad_dependencia"></span></p>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="grupo_investigacion" value="{$ data.grupo_investigacion.id $}"/>
                    				</div>  
                    				
                    				
                    			 <!--foto-->
                    			 <!--   <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">-->
                    				<!--	<label for="foto">Foto&nbsp;<span style="font-weight: normal;">Prueba de msj de error</span></label>-->
                    				<!--	<input id="foto" class="form-control" type="file" ngf-select ngf-pattern=".jpg,.jpeg,.png" name="foto" ngf-accept="'image/jpg, image/jpeg, image/png'" -->
                    				<!--	ng-model="data.foto"-->
                    				<!--	ngf-change="cambiaFoto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"/>-->
                    				<!--	<div class="row row-sin-margen">-->
                    				<!--		<div class="col-xs-12">-->
                    				<!--			<p class="text-center"><img ng-show="form_nuevo_usuario.foto.$valid" ngf-thumbnail="data.foto" class="thumb img-circle" style="height:120px; width: 120px;"></p>-->
                    				<!--		</div>-->
                    				<!--		<div class="col-xs-12">-->
                    				<!--			<button type="button" class="btn btn-default btn-block" ng-click="data.foto=null" ng-show="data.foto">Eliminar</button>-->
                    				<!--		</div>-->
                    				<!--	</div>-->
                    				<!--</div>                                  -->
                    			
                    			</div>
                    		</fieldset>
                    		<br />
                    		
                    		
                    		<div class="row">
                    		    <div class="col-xs-12 col-md-offset-2 col-md-8 col-md-offset-2">
                            		<button type="button" ng-disabled="visibilidad.disable_btn" class="btn btn-primary btn-block" ng-click="btn_update_usuario_click()" ng-disabled="visibilidad.deshabilitar_btn_guardar">
                            		    Guardar&nbsp;
                            		    <span ng-show="visibilidad.show_cargando_guardado">Verificando...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></span>
                            		    <span ng-show="visibilidad.show_error_cargando_guardado" style="color:#8B0000;">Error en la validación, intentar de nuevo</span>
                            		</button>
                            		<button type="submit" id="btn_cargar" ng-show="false"></button>
                    		    </div>
                    		</div>
                    		
                    	</div>
                    	<!--------------------- Fin del formulario -->
                    </form>                
                </div>
            </div>
            
    		<div class="overlay" ng-show="visibilidad.show_velo_contenedor_datos_usuario">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion">
            			<!--Contenido generado desde crear_usuarios_controller-->
                    </div>
                </div>    		    
    		</div>
    		
    		<!--para ver cargando-->
            <div class="overlay" ng-show="visibilidad.show_datos_usuario">
                <i class="fa fa-circle-o-notch fa-spin"></i>
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
    
    <!-- --------------- carga los datos a unas variables java script-->
    <script type="text/javascript">
        $(document).ready(function(){
            usuario ='{{$usuario}}';
            persona ='{{$persona}}';
            //console.log(usuario+"\n"+persona);
        });
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