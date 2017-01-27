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
                        

                        {{--Contenido tab gastos--}}
                        <div id="contenido_gastos" class="tab-pane fade active in"
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
                                                        <!--<th>UCC</th>-->
                                                        <th ng-repeat="item in data.fuente_presupuesto">{$ item.entidad_fuente_presupuesto.nombre $}</th>
                                                        <th>Fecha de ejecución</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="participante in data.gasto_personal">
                                                        <td>{$ $index + 1 $}</td>
                                                        <td ng-show="participante.investigador.usuario == null">{$ participante.investigador.persona.nombres + ' ' + participante.info_investigador.persona.apellidos $}</td>
                                                        <td ng-show="participante.investigador.usuario == null">{$ participante.investigador.persona.formacion $}</td>
                                                        <td ng-show="participante.investigador.usuario == null">{$ participante.investigador.rol.nombre $}</td>
                                                        
                                                        <td ng-show="participante.investigador.usuario != null">{$ participante.investigador.usuario.persona.nombres + ' ' + participante.info_investigador.persona.apellidos $}</td>
                                                        <td ng-show="participante.investigador.usuario != null">{$ participante.investigador.usuario.persona.formacion $}</td>
                                                        <td ng-show="participante.investigador.usuario != null">{$ participante.investigador.rol.nombre $}</td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_dedicacion_semanal_{$ participante.identificacion $}" min="0" 
                                                            ng-model="participante.investigador.dedicacion_horas_semanales" ng-change="validar_dedicacion_semanal(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.dedicacion_semanal_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.dedicacion_semanal_invalido"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_total_semanas_{$ participante.identificacion $}" 
                                                            ng-model="participante.investigador.total_semanas" ng-change="validar_total_semanas(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.total_semanas_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.total_semanas_invalido"/>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="gasto_personal_valor_hora_{$ participante.identificacion $}" 
                                                            ng-model="participante.investigador.valor_hora" ng-change="validar_valor_hora(participante)"
                                                            class="form-control" ng-class="{'invalid_control': participante.valor_hora_invalido}"
                                                            uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.valor_hora_invalido"/>
                                                        </td>                                                                                                        
                                                        <!--<td>-->
                                                        <!--    <input type="number" name="gasto_personal_presupuesto_ucc_{$ participante.identificacion $}" -->
                                                        <!--    ng-model="participante.presupuesto_ucc" ng-change="suma_totales_personal(participante, 'ucc')"-->
                                                        <!--    class="form-control" ng-class="{'invalid_control': participante.presupuesto_ucc_invalido}"-->
                                                        <!--    uib-tooltip="La cantidad debe ser mayor a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="participante.presupuesto_ucc_invalido"/>-->
                                                        <!--</td>   -->
                                                        
                                                        <td ng-repeat="entidad in participante.gasto">
                                                            <!--{$ entidad.valor $}-->
                                                            <input type="number" name="gasto_personal_presupuesto_externo_{$ entidad_presupuesto.id $}_{$ participante.identificacion $}" 
                                                            ng-model="entidad.valor" ng-change="suma_totales_personal(participante, 'otro', entidad_presupuesto.id)"
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
                                    <button type="button" class="btn btn-primary btn-block" ng-click="continuar_a_cargar_documentos()">
                                        Guardar Cmabios
                                    </button>
                                </div>
                            </div>
                            
                        </div> {{--Contenido tab gastos--}}
                        
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
