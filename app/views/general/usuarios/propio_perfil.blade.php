@extends('plantilla')

@section('styles')
    @if(isset($styles))
        @foreach($styles as $style) 
            <link rel="stylesheet" href="/{{ $style }}" type="text/css" />
        @endforeach
    @endif
    <style type="text/css">
        #previa_imagen img{
            border:1px solid rgb(230,230,250);
        }
        .margin-top{
            margin-top: 130px;
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
    
    <?php $hay_notify_operacion_previa = Session::get('notify_operacion_previa') ?>
    @if(isset($hay_notify_operacion_previa))
        <span ng-hide="true" ng-init='notify_operacion_previa={{ json_encode(Session::get("notify_operacion_previa")) }}'></span>
        <span ng-hide="true" ng-init='mensaje_operacion_previa={{ json_encode(Session::get("mensaje_operacion_previa")) }}'></span>
    @endif        
    
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-home" style="font-size:18px;"></i><b></b></a></li> <i class="fa fa-chevron-right" aria-hidden="true"></i> <li><a href="#"><b>Perfil</b></a></li>
        </ol>
        <br />
    </section>
    
    <section class="content" ng-cloak>
        <div class="box" ng-controller="propio_perfil_controller">
            <div class="box-header with-border">
                <h3>Perfil</h3>
            </div>
           <div class="box-body">
               <br />
               <div class="container-sgpi">
                   <div class="checkbox">
                       <label style="font-size:20px;">
                           <input type="checkbox" ng-model="data.editar_datos" style="height:20px; width:20px;">&nbsp;&nbsp;&nbsp;Editar datos
                       </label>
                   </div>
                   <br />
                   <form action="/usuarios/guardar_edicion_propio_perfil" method="POST" name="form_edicion_propio_perfil" enctype="multipart/form-data">
                       <input type="hidden" name="id_usuario" value="{$ data.id_usuario $}"/>
                       <!--Datos básicos-->
                       <fieldset>
                			<legend>Datos básicos</legend>
                			<div class="row is-flex sin-margen">
                			    <div class="col-xs-12 col-sm-6 col-md-4" id="col_foto">
                			        <div class="form-grouo">
                    					<label for="foto">Foto <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_foto_invalido" class="error-text">{$ data.msj_error_foto $}</span></label>
                    					<input id="foto" class="form-control" type="file" name="foto"
                    					ngf-select ngf-pattern=".jpg,.jpeg,.png" ngf-accept="'image/jpg, image/jpeg, image/png'" 
                    					ng-model="data.foto"
                    					ngf-change="cambiaFoto($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                    					ng-readonly="!data.editar_datos"/>
                    					<div class="row row-sin-margen">
                    						<div class="col-xs-12">
                    						    <br />
                    						    <!--previa de imagen-->
                    							<p id="previa_imagen" class="text-center">
                    							    <img ng-show="form_edicion_propio_perfil.foto.$valid" ngf-thumbnail="data.foto" class="img-circle" style="height:120px; width: 120px;" />
                    							    @if(Auth::user()->persona->foto == "") 
                                                         @if(Auth::user()->persona->sexo == "m")
                                                            <img src="/file/imagen_perfil/male.jpg" ng-show="visibilidad.show_foto_previa" class="img-circle" style="height:120px; width: 120px;">
                                                         @else
                                                            <img src="/file/imagen_perfil/female.jpg" ng-show="visibilidad.show_foto_previa" class="img-circle" style="height:120px; width: 120px;">
                                                         @endif
                                                    @else
                                                        <img src="/file/imagen_perfil/{{Auth::user()->persona->foto}}" ng-show="visibilidad.show_foto_previa" class="thumb img-circle" style="height:120px; width: 120px;">
                                                    @endif
                    						    </p>
                    						</div>
                    						<div class="col-xs-12">
                    							<button type="button" class="btn btn-default btn-block" ng-click="limpiar_foto()" ng-show="data.foto">Eliminar</button>
                    							<br />
                    						</div>
                    					</div>
                			        </div>
                				</div>            
                				<!--<div class="hidden-xs hidden-sm col-md-8 col-lg-9">&nbsp;</div>-->
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group" ng-class="{'margin-top': visibilidad.margin_top_nombres}" id="#form-group-nombres">
                    					<label for="nombre">Nombres <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_nombres_invalido" class="error-text">Longitud mínima de 3 caracteres y máxima de 200</span></label>
                    					<input type="text" name="nombres" ng-model="data.nombres" ng-change="validar_nombres()" 
                    					class="form-control" ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_nombres_invalido}"
                    					ng-required="true"
                    					ng-readonly="!data.editar_datos"/>                				    
                				    </div>
                    			</div>
                    			<div class="col-xs-12 col-sm-6 col-md-4">
                    			    <div class="form-group" ng-class="{'margin-top': visibilidad.margin_top_apellidos}" id="#form-group-apellidos">
                    					<label for="apellidos">Apellidos <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_apellidos_invalido" class="error-text">Longitud mínima de 3 caracteres y máxima de 200</span></label>
                    					<input type="text" name="apellidos" ng-model="data.apellidos" ng-change="validar_apellidos()" 
                    					class="form-control"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_apellidos_invalido}"
                    					ng-required="true"
                    					ng-readonly="!data.editar_datos"/>
                    			    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <!--ng-class="{'margin-top': visibilidad.margin_top_identificacion}" id="#form-group-identificacion"-->
                				    <div class="form-group">
                    					<label for="identificacion">Identificación <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_identificacion_invalido" class="error-text">Identificación inválida</span></label>
                    					<input type="number" name="identificacion"  ng-model="data.identificacion" ng-change="validar_id()"
                    					class="form-control"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_identificacion_invalido}"
                    					ng-required="true" min="0"
                    					ng-readonly="!data.editar_datos"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="tipo_identificacion">Tipo de identificación <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_tipo_id_invalido" class="error-text">Campo requerido. Elegir una opción</span></label>
                    					<ui-select theme="bootstrap" search-enabled="false"
                    					ng-model="data.tipo_identificacion" ng-change="validar_tipo_id()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_tipo_id_invalido}"
                    					ng-required="true"
                    					ng-disabled="!data.editar_datos">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.tipos_identificacion | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="tipo_identificacion" value="{$ data.tipo_identificacion.id $}"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="sexo">Sexo <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_sexo_invalido" class="error-text">Campo requerido. Elegir una opción</span></label>
                    					<ui-select theme="bootstrap" search-enabled="false"
                    					ng-model="data.sexo" ng-change="validar_sexo()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_sexo_invalido}"
                    					ng-require="true"
                    					ng-disabled="!data.editar_datos">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.sexos | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="sexo" value="{$ data.sexo.id $}"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="edad">Edad <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_edad_invalido" class="error-text">Valor mínimo de 10</span></label>
                    					<input type="number" name="edad"  ng-model="data.edad" ng-change="validar_edad()"
                    					class="form-control"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_edad_invalido}"
                    					ng-required="true" min="0"
                    					ng-readonly="!data.editar_datos"/>                				    
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="formacion">Formación <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_formacion_invalido" class="error-text">Campo requerido. Elegir una opción</span></label>
                    					<ui-select theme="bootstrap" search-enabled="false"
                    					ng-model="data.formacion" ng-change="validar_formacion()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_formacion_invalido}"
                    					ng-disabled="!data.editar_datos">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.formaciones | filter: $select.search">
                    							<div ng-bind-html="item | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="formacion" value="{$ data.formacion $}"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="email">Email <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_email_invalido" class="error-text">Email inválido</span></label>
                    					<input type="email" name="email" ng-model="data.email" ng-change="validar_email()"
                    					class="form-control" 
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_email_invalido}"
                    					ng-required="true"
                    					ng-readonly="!data.editar_datos"/>
                				    </div>
                				</div>
                    		</div>
                		</fieldset>
                		<br />
                		<!--Datos del usuario-->
                		<fieldset>
                			<legend>Datos del usuario</legend>
                			<div class="row is-flex sin-margen">
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    					<label for="username">Nombre de usuario <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_username_invalido" class="error-text">Campo requerido. El usuario debe ser unico</span></label>
                    					<input type="text" name="username" ng-model="data.username" ng-change="validar_username()"
                    					class="form-control" 
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_username_invalido}"
                    					ng-required="true"
                    					ng-readonly="!data.editar_datos"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-sm-6 col-md-4">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-default btn-block text-center" ng-click="cambiar_contrasenia()">Cambiar contraseña</button>
                                </div>                  			    
                				<div class="col-xs-12 col-sm-6 col-md-4">
                				    <div class="form-group">
                    				    <label for="tipo_usuario">Tipo de usuario</label>
                    				    <input type="text" class="form-control" ng-readonly="true" value="{$ data.tipo_usuario $}"/>
                				    </div>
                				</div>
                				<!--<div class="col-xs-12 col-sm-6 col-md-4" ng-show="visibilidad.show_categoria_inv">-->
                				<!--    <div class="form-group">-->
                    <!--					<label for="categoria_investigador">Categoría de investigador <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_categoria_inv_invalido" class="error-text">{$ data.msj_error_categoria_inv $}</span></label>-->
                    <!--					<ui-select theme="bootstrap"  -->
                    <!--					ng-model="data.categoria_investigador" ng-change="validar_categoria_inv()"-->
                    <!--					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_categoria_inv_invalido}"-->
                    <!--					ng-required="true"-->
                    <!--					ng-disabled="!data.editar_datos">-->
                    <!--						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>-->
                    <!--						<ui-select-choices repeat="item in data.categorias_investigador | filter: $select.search">-->
                    <!--							<div ng-bind-html="item.nombre | highlight: $select.search"></div>-->
                    <!--						</ui-select-choices>-->
                    <!--					</ui-select>-->
                    <!--					<input type="hidden" name="categoria_investigador" value="{$ data.categoria_investigador.id $}"/>-->
                				<!--    </div>-->
                				<!--</div>                        -->
                				<div class="col-xs-12 col-sm-6 col-md-4" ng-show="visibilidad.show_sede">
                				    <div class="form-group">
                    					<label for="sede">Sede de origen <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_sede_invalido" class="error-text">Campo requerido. Elegir una opción</span></label>
                    					<ui-select theme="bootstrap" search-enabled="false"
                    					ng-model="data.sede" ng-change="cambia_sede()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_sede_invalido}" 
                    					ng-required="true"
                    					ng-disabled="!data.editar_datos">
                    						<ui-select-match placeholder="Seleccione...">{$ $select.selected.nombre $}</ui-select-match>
                    						<ui-select-choices repeat="item in data.sedes | filter: $select.search">
                    							<div ng-bind-html="item.nombre | highlight: $select.search"></div>
                    						</ui-select-choices>
                    					</ui-select>
                    					<input type="hidden" name="sede" value="{$ data.sede.id $}"/>
                				    </div>
                				</div>
                				<div class="col-xs-12 col-md-4" ng-show="visibilidad.show_grupo_inv">
                				    <div class="form-group">
                    					<label for="grupo_investigacion">Grupo de investigación <span ng-show="data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_grupo_inv_invalido" class="error-text">Campo requerido. Elegir una opción</span></label>
                    					<ui-select theme="bootstrap" search-enabled="false"
                    					ng-model="data.grupo_investigacion" ng-change="validar_grupo_inv()"
                    					ng-class="{'invalid_control': data.btn_guardar_seleccionado && data.editar_datos && visibilidad.show_grupo_inv_invalido}"
                    					ng-required="true"
                    					ng-disabled="!data.editar_datos">
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
                				</div>
                			</div>
                		</fieldset>
                		<!--boton guardar-->
                		<br />
                		<div class="row">
                		    <div class="hidden-xs hidden-sm col-md-2">&nbsp;</div>
                		    <div class="col-xs-12 col-md-8">
                        		<button type="button" class="btn btn-primary btn-block" ng-click="guardar()" ng-disabled="visibilidad.deshabilitar_btn_guardar || !data.editar_datos">
                        		    Guardar&nbsp;
                        		    <span ng-show="visibilidad.show_cargando_guardado">Verificando...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></span>
                        		    <span ng-show="visibilidad.show_error_cargando_guardado" style="color:#8B0000;">Error en la validación, intentar de nuevo</span>
                        		</button>
                        		<button tyoe="submit" id="btn_cargar" ng-show="false"></button>
                		    </div>
                		    <div class="hidden-xs hidden-sm col-md-2">&nbsp;</div>
                		</div>
                   </form>                       
               </div>
           </div>
           <div class="overlay" ng-show="visibilidad.show_velo_msj_operacion">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;" ng-bind-html="data.msj_operacion">
                        <!--Contenido definido dinámicamente desde controlador-->
                    </div>
                </div>    
            </div>
        </div>
    </section>
    
    <!--plantilla de modal de cambio de password-->
    <script type="text/ng-template" id="modal_cambio_pasdword.html">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">Cambio de contraseña</h3>
        </div>
        <div class="modal-body" id="modal-body">
            <div class="container-sgpi">
                <label>&nbsp;{$ data.error_contrasenia $}</label>
                <input type="password" ng-model="data.contrasenia1" ng-change="cambia_contrasenia1()"
                placeholder="Contraseña actual" class="form-control"
                ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_contrasenia1_invalido}"/>
                <br />
                <input type="password" ng-model="data.contrasenia2" ng-change="cambia_contrasenia2()" 
                placeholder="Nueva contraseña" class="form-control" 
                ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_contrasenia2_invalido}"/>
                <br />
                <input type="password" ng-model="data.contrasenia3" ng-change="cambia_contrasenia3()"
                placeholder="Confirmar contraseña" class="form-control"
                ng-class="{'invalid_control': data.btn_guardar_seleccionado && visibilidad.show_contrasenia3_invalido}"/>
                <br />
            </div>
            <div class="overlay-externo" ng-show="visibilidad.show_velo_msj_operacion">
                <div style="display:table; width:100%; height:100%;">
                    <div style="display:table-cell; vertical-align: middle;">
                        <h4 class="text-center">Cambiando contraseña...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-disabled="visibilidad.deshabilitar_btn_guardar" ng-click="guardar()">Guardar</button>
            <button class="btn btn-default" type="button" ng-click="cancelar()">Cancelar</button>
        </div>
    </script>
    
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
        <script>
            sgpi_app.value('idUsuario', {{ $id_usuario }});
        </script>
    @stop
@endif