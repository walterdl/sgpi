{{--Modal detalles recurso digitales--}}
<script type="text/ng-template" id="modal_mas_info_digital.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de recurso educativo digital - <strong>{$ gasto_digital.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_digital.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_digital.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_digital.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles recurso biblográfico--}}
<script type="text/ng-template" id="modal_mas_info_bibliografico.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de recurso bibliográfico - <strong>{$ gasto_bibliografico.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_bibliografico.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_bibliografico.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_bibliografico.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles servicio técnico--}}
<script type="text/ng-template" id="modal_mas_info_servicio.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de servicio técnico - <strong>{$ gasto_servicio.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_servicio.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_servicio.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_servicio.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles material--}}
<script type="text/ng-template" id="modal_mas_info_material.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de material y suministro - <strong>{$ gasto_material.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_material.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_material.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_material.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles salida de campo--}}
<script type="text/ng-template" id="modal_mas_info_salida_campo.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de salida de campo</h3>
    </div>
    <div class="modal-body">
        <div class="row is-flex">
            <div class="col-xs-12">
                <p><strong>Justificación:</strong>&nbsp;{$ gasto_salida_campo.justificacion $}</p>
            </div>
            <div class="col-xs-12">
                <p><strong>Número de salidas:</strong>&nbsp;{$ gasto_salida_campo.numero_salidas $}</p>
            </div>            
            <div class="col-xs-12">
                <p><strong>Valor unitario:</strong>&nbsp;{$ gasto_salida_campo.valor_unitario | currency:$:2 $}</p>
            </div>                        
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_salida_campo.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_salida_campo.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles software--}}
<script type="text/ng-template" id="modal_mas_info_software.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de software - <strong>{$ gasto_software.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_software.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_software.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_software.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles de equipo--}}
<script type="text/ng-template" id="modal_mas_info_equipo.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto de eequipo - <strong>{$ gasto_equipo.concepto $}</strong></h3>
    </div>
    <div class="modal-body">
        <p><strong>Justificación:</strong>&nbsp;{$ gasto_equipo.justificacion $}</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_equipo.gastos" style="white-space: nowrap">{$ gasto.nombre_entidad $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_equipo.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>    
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>         
</script>

{{--Modal detalles de participnate--}}
<script type="text/ng-template" id="modal_mas_info_participante.html">
    <div class="modal-header">
        <h3 class="modal-title">Gasto personal - <strong>{$ gasto_personal.nombre_completo $}</strong> ({$ gasto_personal.acronimo_id $}. {$ gasto_personal.identificacion $})</h3>
    </div>    
    <div class="modal-body">
        <div class="row is-flex">
            <div class="col-xs-12 col-sm-6">
                <p><strong>Formación:</strong>&nbsp;{$ gasto_personal.formacion $}</p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p><strong>Rol en el proyecto:</strong>&nbsp;{$ gasto_personal.nombre_rol $}</p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p><strong>Edad:</strong>&nbsp;{$ gasto_personal.edad $}</p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p><strong>Sexo:</strong>&nbsp;{$ gasto_personal.sexo $}</p>
            </div>
            <div class="col-xs-12 col-sm-6">
                <p><strong>Email:</strong>&nbsp;{$ gasto_personal.email $}</p>
            </div>            
            <div class="col-xs-12 col-sm-6" ng-if="gasto_personal.id_rol==4 || gasto_personal.id_rol==3">
                <p><strong>Grupo de investigación UCC:</strong>&nbsp;{$ gasto_personal.nombre_grupo_investigacion $}</p>
            </div>            
            <div class="col-xs-12 col-sm-6" ng-if="gasto_personal.id_rol==4 || gasto_personal.id_rol==3">
                <p><strong>Facultad UCC:</strong>&nbsp;{$ gasto_personal.nombre_facultad_ucc $}</p>
            </div>                        
            <div class="col-xs-12 col-sm-6" ng-if="gasto_personal.id_rol==4 || gasto_personal.id_rol==3">
                <p><strong>Sede UCC:</strong>&nbsp;{$ gasto_personal.nombre_sede_ucc $}</p>
            </div>                                    
            <div class="col-xs-12 col-sm-6" ng-if="gasto_personal.id_rol==5 || gasto_personal.id_rol==6">
                <p><strong>Entidad / grupo de investigación:</strong>&nbsp;{$ gasto_personal.entidad_o_grupo_investigacion $}</p>
            </div>                                                
            <div class="col-xs-12 col-sm-6" ng-if="gasto_personal.id_rol==6">
                <p><strong>Programa académico:</strong>&nbsp;{$ gasto_personal.programa_academico $}</p>
            </div>                                                            
        </div>
        <div class="table-responsive" id="contenedor_detalles_participante">
            <table class="table">
                <thead>
                    <tr>
                        <th ng-repeat="gasto in gasto_personal.gastos" style="white-space: nowrap">{$ gasto.entidad_fuente_presupuesto $}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ng-repeat="gasto in gasto_personal.gastos">
                            {$ gasto.valor | currency:$:2 $}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>        
</script>

{{--Modal de revisión de desembolso--}}
<script type="text/ng-template" id="modal_revision_desembolso.html">
    <div class="modal-header">
        <h3 class="modal-title">{$ titulo_modal $}</h3>
    </div>
    <div class="modal-body">
        <div bind-html-compile="gasto_html"></div>
        <br />
        <h4 class="text-center" ng-if="desembolso.hay_desembolso==0" class="nga-fast nga-stagger-fast nga-fade">No se ha cargado desembolso aún</h4>
        <div class="row" ng-if="desembolso.hay_desembolso==1" class="nga-slow nga-stagger-fast nga-fade">
            <div class="col-xs-12">
                Desembolso cargando en la fecha: {$ desembolso.updated_at $}
            </div>
            <div class="col-xs-12 col-sm-6">
                <a href="/file/desembolso/{$ desembolso.archivo $}" class="btn btn-primary">Descargar desembolso <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
                <br />
                <br />
            </div>
            <div class="col-xs-12">
                <label>Comentario del investigador</label>
                <textarea rows="3" ng-model="desembolso.comentario_investigador" class="form-control white-readonly" ng-readonly="true"></textarea>
            </div>            
            <div class="col-xs-12"><hr /></div>
            <div class="col-xs-12">
                <label>Estado de aprobación</label>
                <br />
                <label>
                    <input ng-model="desembolso.aprobado" type="checkbox" class="big-checkbox" ng-disabled="true"> 
                    <span ng-if="desembolso.aprobado" style="font-weight: 500;">Aprobado</span><span ng-if="!desembolso.aprobado" style="font-weight: 500;">No aprobado</span>
                </label>
                <br />
                <br />
            </div>
            <div class="col-xs-12">
                <label>Comentario de revisión</label>
                <textarea rows="3" ng-model="desembolso.comentario_revision" class="form-control white-readonly" ng-readonly="true"
                uib-tooltip="Ingresar un comentario de revisión" tooltip-enable="true"></textarea>
                <br />
            </div>                                    
            <div class="col-xs-12">
                <label>Orden de servicio</label>
                <input type="text" ng-model="desembolso.codigo_aprobacion" class="form-control white-readonly" ng-readonly="true" />
            </div>                        
        </div>
        <div class="overlay-2" ng-show="show_velo">
            <div style="display:table; width:100%; height:100%;">
                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                    <!--Contenido definido dinámicamente desde controlador-->
                </div>
            </div>                                
        </div>          
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$dismiss()">Cerrar</button>
    </div>         
</script>

{{--Modal que presenta la carga de un producto de cualquier fecha--}}
<script type="text/ng-template" id="modal_producto.html">
    <div class="modal-header">
        <h3 class="modal-title" bind-html-compile="titulo_modal"></h3>
    </div>
    <div class="modal-body">
        <h4 class="text-center" ng-if="existe_archivo==0" class="nga-fast nga-stagger-fast nga-fade">Producto no cargado aún</h4>
        <div ng-if="existe_archivo==1" class="nga-fast nga-stagger-fast nga-fade">
            Archivo cargado en la fecha: {$ updated_at $}
            <br />
            <br />
            <a href="/file/producto_fecha_proyectada_radicacion/{$ nombre_archivo $}" class="btn btn-primary">Descargar archivo <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
            <br />
            <br />
            <label>Descripción</label>
            <textarea class="form-control white-readonly" rows="3" ng-model="descripcion" ng-readonly="true"></textarea>
        </div>
        <div class="overlay-2" ng-show="show_velo">
            <div style="display:table; width:100%; height:100%;">
                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                    <!--Contenido definido dinámicamente desde controlador-->
                </div>
            </div>                                
        </div>          
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="$close()">Cerrar</button>
    </div>
</script>    