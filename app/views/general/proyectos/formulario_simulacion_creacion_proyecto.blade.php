<!DOCTYPE html>
<html>
    <head>
        <title>Crea proyecto SGPI</title>
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data" action="/proyectos/registrar_nuevo_proyecto">
            <input type="hidden" name="codigo_fmi" value="FMI-77"/>
            <input type="hidden" name="subcentro_costo" value="Subcentro "/>
            <input type="hidden" name="nombre_proyecto" value="Desarrollo e implementación de sistema de gestión de proyectos de investigación"/>
            <input type="hidden" name="fecha_inicio" value="2016-08-11"/>
            <input type="hidden" name="duracion_meses" value="12"/>
            <input type="hidden" name="fecha_final" value="2017-08-11"/>
            <input type="hidden" name="convocatoria" value="Modalidad de grado UCC"/>
            <input type="hidden" name="anio_convocatoria" value="2016"/>
            <input type="hidden" name="objetivo_general" value="Desarrollar sistema de gestión de proyectos de investigación"/>
            <input type="hidden" name="objetivo_especifico_0" value="Levantar requerimientos"/>
            <input type="hidden" name="objetivo_especifico_1" value="Realizar arquitectura y diseño del sistema"/>
            <input type="hidden" name="objetivo_especifico_2" value="Desarrollar sistema"/>
            <input type="hidden" name="objetivo_especifico_3" value="Aplicar pruebas semi-automatizadas"/>
            <input type="hidden" name="objetivo_especifico_4" value="Implementar sistema"/>
            <input type="hidden" name="cantidad_objetivos_especificos" value="5"/>
            <input type="hidden" name="cantidad_participantes" value="2"/>
            <input type="hidden" name="identificacion_investigador_principal" value="1151949168"/>
            <input type="hidden" name="nombres_1" value="José Brandon"/>
            <input type="hidden" name="apellidos_1" value="Henao Tunjo"/>
            <input type="hidden" name="identificacion_1" value="23455"/>
            <input type="hidden" name="formacion_1" value="Pregado"/>
            <input type="hidden" name="id_rol_1" value="5"/>
            <input type="hidden" name="tipo_identificacion_1" value="1"/>
            <input type="hidden" name="sexo_1" value="m"/>
            <input type="hidden" name="edad_1" value="23"/>
            <input type="hidden" name="email_1" value="brando.polis@hotmail.com"/>
            <input type="hidden" name="entidad_externa_1" value="Grupo SENA"/>
            <input type="hidden" name="cantidad_productos" value="1"/>
            <input type="hidden" name="id_tipo_producto_general_0" value="2"/>
            <input type="hidden" name="id_tipo_producto_especifico_0" value="4"/>
            <input type="hidden" name="nombre_producto_0" value="Sistema SGPI"/>
            <input type="hidden" name="encargado_producto_0" value="1151949168"/>
            <input type="hidden" name="fecha_proyectada_radicar_0" value="2017-02-08"/>
            <input type="hidden" name="fecha_remision_0" value="2017-02-25"/>
            <input type="hidden" name="fecha_confirmacion_editorial_0" value="2017-03-12"/>
            <input type="hidden" name="fecha_recepcion_evaluacion_0" value="2017-02-25"/>
            <input type="hidden" name="fecha_respuesta_evaluacion_0" value="2017-02-24"/>
            <input type="hidden" name="fecha_aprobacion_publicacion_0" value="2017-03-04"/>
            <input type="hidden" name="fecha_publicacion_0" value="2017-03-25"/>
            <input type="hidden" name="cantidad_gastos_equipos" value="1"/>
            <input type="hidden" name="cantidad_gastos_software" value="2"/>
            <input type="hidden" name="cantidad_gastos_salidas" value="0"/>
            <input type="hidden" name="cantidad_gastos_materiales" value="0"/>
            <input type="hidden" name="cantidad_gastos_servicios_tecnicos" value="0"/>
            <input type="hidden" name="cantidad_gastos_bibliograficos" value="0"/>
            <input type="hidden" name="cantidad_gastos_digitales" value="0"/>
            <input type="hidden" name="nuevas_entidad_presupuesto[]" value="0x_Grupo de financiamiento SENA"/>
            <input type="hidden" name="gasto_personal_dedicacion_semanal_1151949168" value="16"/>
            <input type="hidden" name="gasto_personal_total_semanas_1151949168" value="50"/>
            <input type="hidden" name="gasto_personal_valor_hora_1151949168" value="300000"/>
            <input type="hidden" name="gasto_personal_presupuesto_ucc_1151949168" value="150000"/>
            <input type="hidden" name="gasto_personal_presupuesto_externo_0x_1151949168" value="0"/>
            <input type="hidden" name="gasto_personal_fecha_ejecucion_1151949168" value="2017-02-25"/>
            <input type="hidden" name="gasto_personal_dedicacion_semanal_23455" value="8"/>
            <input type="hidden" name="gasto_personal_total_semanas_23455" value="50"/>
            <input type="hidden" name="gasto_personal_valor_hora_23455" value="300000"/>
            <input type="hidden" name="gasto_personal_presupuesto_ucc_23455" value="150000"/>
            <input type="hidden" name="gasto_personal_presupuesto_externo_0x_23455" value="0"/>
            <input type="hidden" name="gasto_personal_fecha_ejecucion_23455" value="2017-05-21"/>
            <input type="hidden" name="gasto_equipo_nombre_0" value="Equipo HP core i6"/>
            <input type="hidden" name="gasto_equipo_justificacion_0" value="Necesario para desarrollo"/>
            <input type="hidden" name="gasto_equipo_presupuesto_ucc_0" value="700000"/>
            <input type="hidden" name="gasto_equipo_presupuesto_conadi_0" value="250000"/>
            <input type="hidden" name="gasto_equipo_presupuesto_externo_0x_0" value="089900"/>
            <input type="hidden" name="gasto_equipo_fecha_ejecucion_0" value="2017-07-23"/>
            <input type="hidden" name="gasto_software_nombre_0" value="Licencia Cloud9"/>
            <input type="hidden" name="gasto_software_justificacion_0" value="Para entorno de desarrollo de sistema"/>
            <input type="hidden" name="gasto_software_presupuesto_ucc_0" value="700000"/>
            <input type="hidden" name="gasto_software_presupuesto_conadi_0" value="0"/>
            <input type="hidden" name="gasto_software_presupuesto_externo_0x_0" value="300000"/>
            <input type="hidden" name="gasto_software_fecha_ejecucion_0" value="2017-04-22"/>
            <input type="hidden" name="gasto_software_nombre_1" value="Licencia Windows 10"/>
            <input type="hidden" name="gasto_software_justificacion_1" value="SO preferido por el equipo"/>
            <input type="hidden" name="gasto_software_presupuesto_ucc_1" value="0"/>
            <input type="hidden" name="gasto_software_presupuesto_conadi_1" value="700000"/>
            <input type="hidden" name="gasto_software_presupuesto_externo_0x_1" value="200001"/>
            <input type="hidden" name="gasto_software_fecha_ejecucion_1" value="2017-08-20"/>            
            <label for="">presupuesto</label>
            <input type="file" name="presupuesto"/>
            <br />
            <label for="">acta_inicio</label>
            <input type="file" name="acta_inicio"/>
            <br />
            <label for="">presentacion_proyecto</label>
            <input type="file" name="presentacion_proyecto"/>
            <br />            
            <input type="submit" value="Enviar"/>
        </form> 
    </body>
</html>