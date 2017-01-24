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

{{--Modal desembolso--}}
<script type="text/ng-template" id="modal_desembolso.html">
    <div class="modal-header">
        <h3 class="modal-title">{$ titulo_modal $}</h3>
    </div>
    <div class="modal-body">
        <div bind-html-compile="gasto_html"></div>
        <div class="alert alert-warning" role="alert" ng-show="show_descargar_desembolso">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<strong>Desembolso cargado, </strong>si vuelve a cargar se sobrescribe el archivo cargado
            <br />
            <a class="btn btn-primary" href="/file/desembolso/{$ nombre_archivo $}">Descargar desembolso <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
        </div> 
        <br />
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <label>Cargar archivo de desembolso</label>
                <span class="error-text" ng-show="documento_invalido">Archivo inválido. Cargar un archivo de máximo 20 MB</span>
                <input type="file"
                	ngf-select ng-model="documento_desembolso" ngf-max-size="20MB"
                	ngf-change="validar_documento($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
                	ng-disabled="cargando_doc"
                	class="form-control" ng-class="{'invalid_control': documento_invalido}"/>            
            </div>
            <div class="col-xs-12 col-sm-6">
                <label>&nbsp;</label>
                <a class="btn btn-default btn-block" href="/file/formato?nombre_formato=desembolso">Descargar formato de desembolso <i class="fa fa-cloud-download" aria-hidden="true"></i></a>
            </div>
            <div class="col-xs-12">
                <br />
                <label>Comentario de desembolso</label>
                <textarea rows="3" class="form-control" ng-model="comentario_investigador"></textarea>
            </div>
            <div class="col-xs-12">
                <br />
                <button class="btn btn-primary btn-block" ng-click="cargar_desembolso()" ng-disabled="cargando_doc">Cargar desembolso <i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
            </div>            
        </div>
        <br />
        <div ng-show="show_barra_progreso" class="nga-fast nga-stagger-fast nga-fade">
            <label for="progreso">Progreso</label>&nbsp;&nbsp;<span ng-show="casi_terminado">Casi terminado, por favor esperar</span>
            <uib-progressbar class="progress-striped active" max="total_archivo" value="carga_actual" type="info"><i>{$ porcentaje_carga $}%</i></uib-progressbar>
        </div>        
        <hr />
        <h4>Estado de revisión: <strong>{$ estado_revision $}</strong></h4>
        <h4 ng-show="show_datos_revision">Código de aprobación: <strong>{$ codigo_aprobacion $}</strong></h4>
        <div ng-show="show_datos_revision">
            <label>Comentario de revisión:</label>
            <textarea rows="3" class="form-control white-readonly" ng-readonly="true" ng-model="comentario_revision"></textarea>
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
        <button type="button" class="btn btn-default" ng-click="cancelar()">Cancelar</button>
    </div>         
</script>

{{--Modal carga de documento de fecha proyectada de radicación--}}
<script type="text/ng-template" id="modal_fecha_proyectada_radicacion.html">
    <div class="modal-header">
        <h3 class="modal-title">Producto proyectado a radicar (fecha <strong>{$ fecha_proyectada_radicacion $}</strong>)</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning" role="alert" ng-show="ya_hay_archivo">
            <strong>Ya se ha cargado este archivo</strong>, si vuelve a cargar se sobrescribe el archivo actual<br />
            <br />
            <a href="/file/producto_fecha_proyectada_radicacion/{$ nombre_archivo $}" class="btn btn-primary" ng-click="descargar_actual()">Descargar archivo&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
        </div>
        <label for="documento_fecha_proyectada_radicacion">Cargar documento</label>
        <span class="error-text" ng-show="documento_invalido">Archivo inválido. Cargar un archivo de máximo 20 MB</span>
        <input id="documento_fecha_proyectada_radicacion" type="file" name="documento_fecha_proyectada_radicacion"
        	ngf-select ng-model="documento_fecha_proyectada_radicacion" ngf-max-size="20MB"
        	ngf-change="validar_documento($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
        	ng-disabled="cargando_doc"
        	class="form-control" ng-class="{'invalid_control': documento_invalido}"/>
        <br />
        <label for="descripcion_documento">Descripción (opcional)</label>
        <textarea class="form-control" rows="3" ng-model="descripcion" ng-readonly="cargando_doc"></textarea>
        <br />
        <div ng-show="show_barra_progreso" class="nga-fast nga-stagger-fast nga-fade">
            <label for="progreso">Progreso</label>&nbsp;&nbsp;<span ng-show="casi_terminado">Casi terminado, por favor esperar</span>
            <uib-progressbar class="progress-striped active" max="total_archivo" value="carga_actual" type="info"><i>{$ porcentaje_carga $}%</i></uib-progressbar>
        </div>
        <br />
        <button type="button" class="btn btn-primary btn-block" ng-click="cargar_doc()" ng-disabled="cargando_doc">Cargar documento&nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
        <div class="overlay-2" ng-show="show_velo">
            <div style="display:table; width:100%; height:100%;">
                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                    <!--Contenido definido dinámicamente desde controlador-->
                </div>
            </div>                                
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cancelar()">Cancelar</button>
    </div>
</script>

{{--Modal carga de documento de fecha de publicación--}}
<script type="text/ng-template" id="modal_fecha_publicacion.html">
    <div class="modal-header">
        <h3 class="modal-title">Producto publicado (fecha <strong>{$ fecha_publicacion $}</strong>)</h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-warning" role="alert" ng-show="ya_hay_archivo">
            <strong>Ya se ha cargado este archivo</strong>, si vuelve a cargar se sobrescribe el archivo actual<br />
            <br />
            <a href="/file/producto_fecha_publicacion/{$ nombre_archivo $}" class="btn btn-primary" ng-click="descargar_actual()">Descargar archivo&nbsp;<i class="fa fa-cloud-download" aria-hidden="true"></i></a>
        </div>
        <label for="documento_fecha_proyectada_radicacion">Cargar documento</label>
        <span class="error-text" ng-show="documento_invalido">Archivo inválido. Cargar un archivo de máximo 20 MB</span>
        <input id="documento_fecha_publicacion" type="file" name="documento_publicacion"
        	ngf-select ng-model="documento_fecha_publicacion" ngf-max-size="20MB"
        	ngf-change="validar_documento($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event)"
        	ng-disabled="cargando_doc"
        	class="form-control" ng-class="{'invalid_control': documento_invalido}"/>
        <br />
        <label for="descripcion_documento">Descripción (opcional)</label>
        <textarea class="form-control" rows="3" ng-model="descripcion" ng-readonly="cargando_doc"></textarea>
        <br />
        <div ng-show="show_barra_progreso" class="nga-fast nga-stagger-fast nga-fade">
            <label for="progreso">Progreso</label>&nbsp;&nbsp;<span ng-show="casi_terminado">Casi terminado, por favor esperar</span>
            <uib-progressbar class="progress-striped active" max="total_archivo" value="carga_actual" type="info"><i>{$ porcentaje_carga $}%</i></uib-progressbar>
        </div>
        <br />
        <button type="button" class="btn btn-primary btn-block" ng-click="cargar_doc()" ng-disabled="cargando_doc">Cargar documento&nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
        <div class="overlay-2" ng-show="show_velo">
            <div style="display:table; width:100%; height:100%;">
                <div style="display:table-cell; vertical-align: middle;" ng-bind-html="msj_operacion">
                    <!--Contenido definido dinámicamente desde controlador-->
                </div>
            </div>                                
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" ng-click="cancelar()">Cancelar</button>
    </div>
</script>    

