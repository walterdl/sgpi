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
        .min-width-150{
            min-width: 150px;
        }
        .min-width-180{
            min-width: 180px !important;
        }
        table thead tr th {
            white-space: nowrap;
        }
        
        #contenedor_gastos_personal,
        #contenedor_gastos_equipos,
        #contenedor_gastos_software,
        #contenedor_gastos_salidas,
        #contenedor_gastos_materiales,
        #contenedor_gastos_servicios,
        #contenedor_gastos_bibliograficos,
        #contenedor_gastos_digitales
        {
          position: relative;
        }   
        
        .ps-container > .ps-scrollbar-x-rail{
            opacity: 0.7 !important;
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
                <a><b>Editar gastos</b></a>
            </li>
        </ol>
        <br />
    </section>
    
    <!--contenido |-->
    <section class="content" ng-cloak ng-controller="editar_gastos_proyectos_controller">
        
        <form action="/proyectos/editar/gastos" method="POST" enctype='multipart/form-data'>
            
            <div class="box">
                <div class="box-header with-border">
                    <h3>Editar proyecto</h3>
                </div>
                <div class="box-body">
                    
                    <div id="inputs_nuevas_entidades_fuente_presupuesto" ng-hide="true">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden para crear nuevos registros de entidades financiadoras--}}
                    </div>
                    <div id="inputs_entidades_fuente_presupuesto_existentes">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican las entidades fuente de presupuesto existentes en la BD seleccionadas para financiar el proyecto--}}
                    </div>
                    <div id="gastos_equipos_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos equipos que ya existen en la BD y que será eliminados--}}
                    </div>
                    <div id="gastos_software_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de software que ya existen en la BD y que será eliminados--}}
                    </div>
                    <div id="gastos_salidas_campo_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de salidas de campo que ya existen en la BD y que será eliminados--}}
                    </div>             
                    <div id="gastos_materiales_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de materiales que ya existen en la BD y que será eliminados--}}
                    </div>               
                    <div id="gastos_servicios_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de servicios técnicos que ya existen en la BD y que será eliminados--}}
                    </div>                               
                    <div id="gastos_bibliograficos_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de recursos bibliograficos que ya existen en la BD y que será eliminados--}}
                    </div>                                
                    <div id="gastos_digitales_existentes_a_eliminar">
                        {{--contenido generado desde controlador--}}
                        {{--son inputs hidden que identifican los gastos de educativos digitales que ya existen en la BD y que será eliminados--}}
                    </div>                                                    
                    
                    <br />
                    
                    <p><strong>Agregar entidad fuente de presupuesto (por defecto se tiene UCC y CONADI)</strong></p>
                    <div class="row is-flex">
                        <div class="col-xs-12 col-md-6">
                            <div class="alert alert-info">
                                <strong>Tener en cuenta: </strong> al remover una entidad fuente de presupuesto se remueve en todos los tipos de gasto
                            </div>
                        </div>          
                        <div class="col-xs-12 col-md-6">&nbsp;</div>
                        <div class="col-xs-12 col-md-6">
                            <ui-select id="multiselect_entidades_presupuesto"
                            multiple ng-model="data.entidades_fuente_presupuesto_seleccionadas"
                            close-on-select="true" search-enabled="true" on-select="seleccion_entidad_presupuesto_multiselect($item)" on-remove="remocion_entidad_presupuesto_multiselect($item)"
                            style="width: 100%;" theme="bootstrap"  title="Seleccionar otras entidades fuente de presupuesto...">
                                <ui-select-match placeholder="Seleccione...">{$ $item.nombre $}</ui-select-match>
                                <ui-select-choices repeat="entidad_presupuesto in data.entidades_fuente_presupuesto">
                                    {$ entidad_presupuesto.nombre $}
                                </ui-select-choices>
                            </ui-select>
                        </div>
                        
                        <div class="col-xs-12 col-md-6">
                            <p style="color: rgb(178, 34, 34);" ng-show="nueva_entidadPresupuesto_incorrecto">{$ msj_nueva_entidadPresupuesto_incorrecto $}</p>
                            <div class="input-group">
                                <input id="input_text_nueva_entidad" type="text" ng-model="nueva_entidad_presupuesto"
                                ng-change="nueva_entidadPresupuesto_incorrecto=false"
                                class="form-control" ng-class="{'invalid_control': nueva_entidadPresupuesto_incorrecto}"/>
                                <span class="input-group-addon btn btn-default" ng-click="agregar_nueva_entidad_presupuesto_a_multiselect()">
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
                                                <th style="white-space: initial;">Dedicación (horas semanales)</th>
                                                <th>Total semanas</th>
                                                <th>Valor hora</th>
                                                <th>UCC</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="gasto_personal in gastos_personal">
                                                <td>{$ $index + 1 $}</td>
                                                <td>{$ gasto_personal.nombre_completo_persona $}</td>
                                                <td>{$ gasto_personal.formacion $}</td>
                                                <td>{$ gasto_personal.nombre_rol $}</td>
                                                <td>
                                                    <input type="number" string-to-number name="gasto_personal_dedicacion_semanal_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}" min="0"
                                                    ng-model="gasto_personal.dedicacion_horas_semanales" ng-change="validar_dedicacion_semanal(gasto_personal)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_personal.dedicacion_semanal_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_personal.dedicacion_semanal_invalido"/>
                                                </td>
                                                <td>
                                                    <input type="number" string-to-number name="gasto_personal_total_semanas_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_personal.total_semanas" ng-change="validar_total_semanas(gasto_personal)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_personal.total_semanas_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_personal.total_semanas_invalido"/>
                                                </td>
                                                <td>
                                                    <input type="number" string-to-number name="gasto_personal_valor_hora_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_personal.valor_hora" ng-change="validar_valor_hora(gasto_personal)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_personal.valor_hora_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_personal.valor_hora_invalido"/>
                                                </td>
                                                <td ng-repeat="gasto in gasto_personal.gastos">
                                                    {{--El ng-if coloca el presupuesto de UCC de primero--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number name="gasto_personal_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_personal', gasto_personal, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'" type="number" string-to-number name="gasto_personal_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_personal', gasto_personal, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                </td>   
                                                <td class="min-width-180">
                                                    {$ gasto_personal.total|currency:$:2 $}
                                                </td>                                                        
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_personal_fecha_ejecucion_{$ gasto_personal.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_personal.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_personal)"
                                                        is-open="gasto_personal.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_personal.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_personal.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_personal.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_personal.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_personal.length>0">
                                                <td colspan="7" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_personal.ucc|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_personal.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_personal.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_equipos')">Agregar equipo&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <br />
                                <div id="contenedor_gastos_equipos" class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead id="table_head_gastos_equipos">
                                            <tr>
                                                <th>N°</th>
                                                <th>Equipo</th>
                                                <th>Justificación</th>
                                                <th>UCC</th>
                                                <th>CONADI</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_body_gastos_equipos">
                                            <tr ng-if="gastos_equipos.length==0">
                                                <td colspan="{$ 10 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de equipos</td>
                                            </tr>
                                            <tr ng-repeat="gasto_equipo in gastos_equipos">
                                                <td>{$ $index + 1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_equipo_concepto_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_equipo.concepto" ng-change="validar_concepto(gasto_equipo)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_equipo.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_equipo.concepto_invalido"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="gasto_equipo_justificacion_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_equipo.justificacion" ng-change="validar_justificacion(gasto_equipo)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_equipo.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_equipo.justificacion_invalido"/>
                                                </td>
                                                <td ng-repeat="gasto in gasto_equipo.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_equipo_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_equipos', gasto_equipo, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_equipo_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_equipos', gasto_equipo, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_equipo_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_equipos', gasto_equipo, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_equipo.total|currency:$:2 $}
                                                </td>                                                        
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_equipo_fecha_ejecucion_{$ gasto_equipo.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_equipo.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_equipo)"
                                                        is-open="gasto_equipo.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_equipo.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_equipo.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_equipo.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_equipo.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_equipo.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_equipos', gasto_equipo)" ng-disabled="gasto_equipo.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_equipos.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_equipos.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_equipos.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_equipos.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_equipos.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_software')">Agregar software&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <br />
                                <div id="contenedor_gastos_software" class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Software</th>
                                                <th>Justificación</th>
                                                <th>UCC</th>
                                                <th>CONADI</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_software.length==0">
                                                <td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de software</td>
                                            </tr>              
                                            <tr ng-repeat="gasto_software in gastos_software">
                                                <td>{$ $index + 1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_software_concepto_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_software.concepto" ng-change="validar_concepto(gasto_software)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_software.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_software.concepto_invalido"/>
                                                </td>                                                
                                                <td>
                                                    <input type="text" name="gasto_software_justificacion_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_software.justificacion" ng-change="validar_justificacion(gasto_software)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_software.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_software.justificacion_invalido"/>
                                                </td>                                                
                                                <td ng-repeat="gasto in gasto_software.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_software_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_software', gasto_software, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_software_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_software', gasto_software, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_software_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_software', gasto_software, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_software.total|currency:$:2 $}
                                                </td>                                                
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_software_fecha_ejecucion_{$ gasto_software.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_software.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_software)"
                                                        is-open="gasto_software.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_software.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_software.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_software.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_software.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_software.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_software', gasto_software)" ng-disabled="gasto_software.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_software.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_software.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_software.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_software.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_software.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_salidas_campo')">Agregar salida de campo&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
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
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>                      
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_salidas_campo.length==0">
                                                <td colspan="{$ 11 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de salidas de campo</td>
                                            </tr> 
                                            <tr ng-repeat="gasto_salida in gastos_salidas_campo">
                                                <td>{$ $index + 1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_salida_justificacion_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_salida.justificacion" ng-change="validar_justificacion(gasto_salida)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_salida.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_salida.justificacion_invalido"/>
                                                </td>             
                                                <td>
                                                    <input type="number" name="gasto_salida_cantidad_salidas_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_salida.cantidad_salidas" ng-change="validar_cantidad_salidas(gasto_salida)" string-to-number
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_salida.cantidad_salidas_invalido}"
                                                    uib-tooltip="Cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.cantidad_salidas_invalido"/>
                                                </td>
                                                <td>
                                                    <input type="number" name="gasto_salida_valor_unitario_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_salida.valor_unitario" ng-change="validar_valor_unitario(gasto_salida)" string-to-number
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_salida.valor_unitario_invalido}"
                                                    uib-tooltip="Cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-trigger="'mouseenter'" tooltip-enable="gasto_salida.valor_unitario_invalido"/>
                                                </td>
                                                <td ng-repeat="gasto in gasto_salida.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_salida_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_salidas_campo', gasto_salida, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_salida_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_salidas_campo', gasto_salida, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_salida_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_salidas_campo', gasto_salida, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_salida.total|currency:$:2 $}
                                                </td>                                                                    
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_salida_fecha_ejecucion_{$ gasto_salida.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_salida.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_salida)"
                                                        is-open="gasto_salida.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_salida.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_salida.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_salida.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_salida.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_salida.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_salidas_campo', gasto_salida)" ng-disabled="gasto_salida.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_salidas_campo.length>0">
                                                <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_salidas.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_salidas.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_salidas.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_salidas.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_materiales')">Agregar material / suministro&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
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
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_materiales.length==0">
                                                <td colspan="{$ 8 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de materiales / suministros</td>
                                            </tr>
                                            <tr ng-repeat="gasto_material in gastos_materiales">
                                                <td>{$ $index +1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_material_concepto_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_material.concepto" ng-change="validar_concepto(gasto_material)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_material.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_material.concepto_invalido"/>
                                                </td>                      
                                                <td>
                                                    <input type="text" name="gasto_material_justificacion_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_material.justificacion" ng-change="validar_justificacion(gasto_material)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_material.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_material.justificacion_invalido"/>
                                                </td>                         
                                                <td ng-repeat="gasto in gasto_material.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_material_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_materiales', gasto_material, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_material_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_materiales', gasto_material, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_material_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_materiales', gasto_material, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_material.total|currency:$:2 $}
                                                </td>                                                    
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_material_fecha_ejecucion_{$ gasto_material.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_material.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_material)"
                                                        is-open="gasto_material.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_material.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_material.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_material.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_material.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_material.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_materiales', gasto_material)" ng-disabled="gasto_material.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_materiales.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_materiales.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_materiales.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_materiales.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_materiales.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_servicios')">Agregar servicios técnicos&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <br />
                                <div class="table-responsive" id="contenedor_gastos_servicios">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Servicio técnico</th>
                                                <th>Justificación</th>
                                                <th>UCC</th>
                                                <th>CONADI</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_servicios.length==0">
                                                <td colspan="{$ 11 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de servicios técnicos</td>
                                            </tr>
                                            <tr ng-repeat="gasto_servicio in gastos_servicios">
                                                <td>{$ $index +1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_servicio_concepto_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_servicio.concepto" ng-change="validar_concepto(gasto_servicio)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_servicio.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_servicio.concepto_invalido"/>
                                                </td>                      
                                                <td>
                                                    <input type="text" name="gasto_servicio_justificacion_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_servicio.justificacion" ng-change="validar_justificacion(gasto_servicio)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_servicio.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_servicio.justificacion_invalido"/>
                                                </td>                         
                                                <td ng-repeat="gasto in gasto_servicio.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_servicio_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_servicios', gasto_servicio, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_servicio_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_servicios', gasto_servicio, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_servicio_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_servicios', gasto_servicio, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_servicio.total|currency:$:2 $}
                                                </td>                                                    
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_servicio_fecha_ejecucion_{$ gasto_servicio.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_servicio.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_servicio)"
                                                        is-open="gasto_servicio.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_servicio.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_servicio.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_servicio.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_servicio.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_servicio.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_servicios', gasto_servicio)" ng-disabled="gasto_servicio.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_servicios.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_servicios.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_servicios.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_servicios.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_servicios.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_bibliograficos')">Agregar recurso bibliográfico&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <br />
                                <div class="table-responsive" id="contenedor_gastos_bibliograficos">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Título</th>
                                                <th>Justificación</th>
                                                <th>UCC</th>
                                                <th>CONADI</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_bibliograficos.length==0">
                                                <td colspan="{$ 11 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de recursos bibliográficos</td>
                                            </tr>
                                            <tr ng-repeat="gasto_bibliografico in gastos_bibliograficos">
                                                <td>{$ $index +1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_bibliografico_concepto_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_bibliografico.concepto" ng-change="validar_concepto(gasto_bibliografico)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_bibliografico.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_bibliografico.concepto_invalido"/>
                                                </td>                      
                                                <td>
                                                    <input type="text" name="gasto_bibliografico_justificacion_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_bibliografico.justificacion" ng-change="validar_justificacion(gasto_bibliografico)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_bibliografico.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_bibliografico.justificacion_invalido"/>
                                                </td>                         
                                                <td ng-repeat="gasto in gasto_bibliografico.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_bibliografico_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_bibliograficos', gasto_bibliografico, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_bibliografico_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_bibliograficos', gasto_bibliografico, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_bibliografico_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_bibliograficos', gasto_bibliografico, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_bibliografico.total|currency:$:2 $}
                                                </td>                                                    
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_bibliografico_fecha_ejecucion_{$ gasto_bibliografico.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_bibliografico.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_bibliografico)"
                                                        is-open="gasto_bibliografico.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_bibliografico.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_bibliografico.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_bibliografico.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_bibliografico.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_bibliografico.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_bibliograficos', gasto_bibliografico)" ng-disabled="gasto_bibliografico.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_bibliograficos.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_bibliograficos.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_bibliograficos.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_bibliograficos.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_bibliograficos.total|currency:$ $}</td>
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
                                        <button type="button" class="btn btn-primary btn-block" ng-click="agregar_tipo_gasto('gastos_digitales')">Agregar recurso educativo digital&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <br />
                                <div class="table-responsive" id="contenedor_gastos_digitales">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Título</th>
                                                <th>Justificación</th>
                                                <th>UCC</th>
                                                <th>CONADI</th>
                                                <th ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">{$ entidad_fuente_presupuesto.nombre $}</th>
                                                <th>Total</th>
                                                <th>Fecha de ejecución</th>
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-if="gastos_digitales.length==0">
                                                <td colspan="{$ 11 + data.entidades_presupuesto_seleccionadas.length $}" class="text-left">Sin gastos de recursos educativos digitales</td>
                                            </tr>
                                            <tr ng-repeat="gasto_digital in gastos_digitales">
                                                <td>{$ $index +1 $}</td>
                                                <td>
                                                    <input type="text" name="gasto_digital_concepto_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_digital.concepto" ng-change="validar_concepto(gasto_digital)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_digital.concepto_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_digital.concepto_invalido"/>
                                                </td>                      
                                                <td>
                                                    <input type="text" name="gasto_digital_justificacion_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto_digital.justificacion" ng-change="validar_justificacion(gasto_digital)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto_digital.justificacion_invalido}"
                                                    uib-tooltip="Longitud mínima de 5 caracteres y máximo de 150" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_digital.justificacion_invalido"/>
                                                </td>                         
                                                <td ng-repeat="gasto in gasto_digital.gastos">
                                                    {{--ucc--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='UCC'" type="number" string-to-number 
                                                    name="gasto_digital_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_digitales', gasto_digital, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--conadi--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto=='CONADI'" type="number" string-to-number 
                                                    name="gasto_digital_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_digitales', gasto_digital, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>
                                                    {{--otras entidades--}}
                                                    <input ng-if="gasto.nombre_entidad_fuente_presupuesto!='UCC'&&gasto.nombre_entidad_fuente_presupuesto!='CONADI'" type="number" string-to-number 
                                                    name="gasto_digital_presupuesto_{$ gasto.id_entidad_fuente_presupuesto $}_{$ gasto.id_gasto $}_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                    ng-model="gasto.valor" ng-change="cambia_presupuesto_tipo_gasto('gastos_digitales', gasto_digital, gasto)"
                                                    class="form-control min-width-150" ng-class="{'invalid_control': gasto.gasto_invalido}"
                                                    uib-tooltip="La cantidad debe ser mayor o igual a cero" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto.gasto_invalido"/>                                                            
                                                </td>
                                                <td class="min-width-180">
                                                    {$ gasto_digital.total|currency:$:2 $}
                                                </td>                                                    
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="gasto_digital_fecha_ejecucion_{$ gasto_digital.id_detalle_gasto $}_{$ $index $}"
                                                        ng-model="gasto_digital.fecha_ejecucion" ng-change="validar_fecha_ejecucion(gasto_digital)"
                                                        is-open="gasto_digital.show_datepicker_fecha_ejecucion"
                                                        datepicker-options="dateOptions" uib-datepicker-popup="yyyy-MM-dd" datepicker-append-to-body="true"
                                                        clear-text="Borrar" close-text="Seleccionar" current-text="Fecha actual"
                                                        ng-click="gasto_digital.show_datepicker_fecha_ejecucion=true"
                                                        ng-readonly="true"
                                                        class="form-control white-readonly min-width-150" ng-class="{'invalid_control': gasto_digital.fecha_ejecucion_invalido}"
                                                        uib-tooltip="Ingresar fecha" tooltip-class="tooltip-invalid_control" tooltip-append-to-body="true" tooltip-enable="gasto_digital.fecha_ejecucion_invalido"/>
                                                        <span class="input-group-addon btn btn-default" ng-click="gasto_digital.show_datepicker_fecha_ejecucion=true">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td uib-tooltip="No se puede remover. Ya tiene desembolso cargado" tooltip-append-to-body="true" tooltip-enable="gasto_digital.tiene_desembolso_cargado">
                                                    <button type="button" class="btn btn-default" 
                                                    ng-click="remover_tipo_gasto('gastos_digitales', gasto_digital)" ng-disabled="gasto_digital.tiene_desembolso_cargado">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr ng-if="gastos_digitales.length>0">
                                                <td colspan="3" class="text-right"><strong>Total</strong></td>
                                                <td>{$ totales_gastos_digitales.ucc|currency:$ $}</td>
                                                <td>{$ totales_gastos_digitales.conadi|currency:$ $}</td>
                                                <td ng-repeat="entidad_fuente_presupuesto in data.entidades_fuente_presupuesto_seleccionadas">
                                                    {$ totales_gastos_digitales.entidades_fuente_presupuesto[entidad_fuente_presupuesto.id]|currency:$ $}
                                                </td>
                                                <td class="text-left">{$ totales_gastos_digitales.total|currency:$ $}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>{{--./Gastos recursos educativos digitales--}}
                    
                    <hr />
                    
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <button type="button" class="btn btn-primary btn-block" ng-click="guardar()" >Guardar cambios&nbsp;<i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                            <input type="submit" id="input_editar_proyecto" ng-hide="true"/>
                        </div>
                        <div class="col-xs-12 hidden-md hidden-lg">&nbsp;</div>
                        <div class="col-xs-12 col-md-4">
                            <a class="btn btn-default btn-block" href="/proyectos/listar">Cancelar</a>
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
        sgpi_app.value('id_proyecto', {{ $proyecto_id }});
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
