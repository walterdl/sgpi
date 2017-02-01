$(document).ready(function () {
	$("#contenedor_proyectos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});    
	$("#contenedor_objetivos_especificos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	$("#contenedor_entidades_grupos_inv").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	                	
	$("#contenedor_participantes").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	 
	$("#contenedor_productos_x_tipo").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	                			
	$("#contenedor_entidades_fuente_pres").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	  	
})

// COnfigura el idioma de tatable de proyectos
sgpi_app.run(function(DTDefaultOptions) {
    
    DTDefaultOptions.setLanguage({
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Sin proyectos",
            "sInfo": "Mostrando proyectos del _START_ al _END_ de un total de _TOTAL_ proyectos",
            "sInfoEmpty": "0 de 0, sin registros a mostrar",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        });
});

sgpi_app.controller('general_controller', function($scope, $http) {
    
    $scope.msj_velo_general = '<h4 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo_general = true;
    $scope.chart_proys_final_aprobado = null;
    $scope.chart_total_mujeres_hombres = null;
    $scope.sedes_ucc = [];
    $scope.facultades_correspondientes = [];
    $scope.grupos_inv_correspondientes = [];
    $scope.data.sede_seleccionada = null;
    $scope.data.facultad_seleccionada = null;
    $scope.data.grupo_investigacion_seleccionado = null;
    $scope.chart_proys_x_clasificacion = null;
    $scope.chart_proys_x_area = null;
    $scope.proyectos = [];
    $scope.dtOptions = {
        paginationType: 'full_numbers',
        displayLength: 5,
        hasBootstrap: true,
        lengthMenu: [[5, 10, 20], [5, 10, 20]]
    };
    $scope.show_velo_tabla_proyectos = false;
    $scope.tipo_filtro = null;
    
    // ejecuta ajax por la informacipón inicial de la vista
    $http({
        url: '/indicadores/data_inicial_admin',
        method: 'GET'
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            $scope.sedes_ucc = data.sedes_ucc;
            $scope.facultades_x_sedes = data.facultades_x_sedes;
            $scope.grupos_inv_x_facultades = data.grupos_inv_x_facultades;
            $scope.crear_chart_proys_final_aprobado(data.cantidad_proyectos_final_aprobado);
            $scope.crear_chart_total_mujeres_hombres(data.cantidad_mujeres_hombres);
            
            $scope.crear_chart_proys_x_clasificacion(data.cantidad_proys_x_clasificacion);
            $scope.crear_chart_proys_x_area(data.cantidad_proys_x_area);
            
            $scope.show_velo_general = false;
        }
        else
        {
            $scope.msj_velo_general = '<h4 class="text-center">Error al consultar los datos iniciales. Código de error: ' + data.codigo + '</h4>';
            alertify.error('Error al consultar los datos iniciales. Código de error: ' + data.codigo);
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.msj_velo_general = '<h4 class="text-center">Error XHR o de servidor al consultar los datos iniciales. Código de estado: ' + status + '</h4>';
        alertify.error('Error XHR o de servidor al consultar los datos iniciales. Código de estado: ' + status);
    });
    
    
	/*
	|--------------------------------------------------------------------------
	| crear_chart_proys_final_aprobado()
	|--------------------------------------------------------------------------
    | Crea la torta gráfica que presenta la cantidad de proyectos con final de proyecto aprobado
	*/         
    $scope.crear_chart_proys_final_aprobado = function(data) {
        
        data = [
                {
                    categoria: 'Aprobados',
                    value: data.finales_aprobados
                },
                {
                    categoria: 'No aprobados',
                    value: data.finales_no_aprobados
                }
            ];        
        if($scope.chart_proys_final_aprobado == null){
            
            $scope.chart_proys_final_aprobado =  AmCharts.makeChart("proyectos_final_aprobado",
                {
                	"type": "pie",
                	"angle": 12,
                	"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                	"depth3D": 15,
                	"innerRadius": "20%",
                	"colors": [
                		"#72ACD9",
                		"#AFE5FF",
                		"#B0DE09",
                		"#0D8ECF",
                		"#2A0CD0",
                		"#CD0D74",
                		"#CC0000",
                		"#00CC00",
                		"#0000CC",
                		"#DDDDDD",
                		"#999999",
                		"#333333",
                		"#990000"
                	],
                	"titleField": "categoria",
                	"valueField": "value",
                	"allLabels": [],
                	"balloon": {},
                	"legend": {
                		"enabled": true,
                		"markerType": "circle",
                		"valueText": "[[value]] ([[percents]]%)",
                		"align": "center",
                        "valueAlign": "left"
                	},
                	"titles": [
                		{
                			"id": "Title-1",
                			"size": 15,
                			"text": "Proyectos  de inv. con final aprobado / no aprobado"
                		}
                	],
                	"dataProvider": data,
                	"responsive": {
                        "enabled": true
                    }
                }
            );       
    
            $scope.chart_proys_final_aprobado.addListener("animationFinished", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_final_aprobado.addListener("rendered", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_final_aprobado.legend.addListener('hideItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_final_aprobado.legend.addListener('showItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_final_aprobado.addListener('drawn', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });                    
        }
        else{
            $scope.chart_proys_final_aprobado.dataProvider = data;
            $scope.chart_proys_final_aprobado.validateData();
            $scope.chart_proys_final_aprobado.animateAgain();
        }        
    };    
    
	/*
	|--------------------------------------------------------------------------
	| crear_chart_total_mujeres_hombres()
	|--------------------------------------------------------------------------
    | Crea la torta gráfica que presenta la cantidad total de mujeres y hombres en todos los proyectos de investigación
	*/         
    $scope.crear_chart_total_mujeres_hombres = function(data) {
        
        data = [
                {
                    categoria: 'Hombres',
                    value: data.hombres
                },
                {
                    categoria: 'Mujeres',
                    value: data.mujeres
                }
            ];        
        if($scope.chart_total_mujeres_hombres == null){
            
            $scope.chart_total_mujeres_hombres =  AmCharts.makeChart("totaL_mujeres_hombres",
                {
                	"type": "pie",
                	"angle": 12,
                	"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                	"depth3D": 15,
                	"innerRadius": "20%",
                	"colors": [
                		"#72ACD9",
                		"#AFE5FF",
                		"#B0DE09",
                		"#0D8ECF",
                		"#2A0CD0",
                		"#CD0D74",
                		"#CC0000",
                		"#00CC00",
                		"#0000CC",
                		"#DDDDDD",
                		"#999999",
                		"#333333",
                		"#990000"
                	],
                	"titleField": "categoria",
                	"valueField": "value",
                	"allLabels": [],
                	"balloon": {},
                	"legend": {
                		"enabled": true,
                		"markerType": "circle",
                		"valueText": "[[value]] ([[percents]]%)",
                		"align": "center",
                        "valueAlign": "left"
                	},
                	"titles": [
                		{
                			"id": "Title-1",
                			"size": 15,
                			"text": "Total de mujeres / hombres en proyectos de investigación"
                		}
                	],
                	"dataProvider": data,
                	"responsive": {
                        "enabled": true
                    }
                }
            );       
    
            $scope.chart_total_mujeres_hombres.addListener("animationFinished", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_total_mujeres_hombres.addListener("rendered", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_total_mujeres_hombres.legend.addListener('hideItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_total_mujeres_hombres.legend.addListener('showItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_total_mujeres_hombres.addListener('drawn', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });                    
        }
        else{
            $scope.chart_total_mujeres_hombres.dataProvider = data;
            $scope.chart_total_mujeres_hombres.validateData();
            $scope.chart_total_mujeres_hombres.animateAgain();
        }        
    };        
    
	/*
	|--------------------------------------------------------------------------
	| crear_chart_proys_x_clasificacion()
	|--------------------------------------------------------------------------
    | Crea la torta gráfica que presenta la cantidad de proyectos agrupados por la clasificación de su grupo de investigación ejecutor
	*/             
    $scope.crear_chart_proys_x_clasificacion = function(data) {
        
        var chartData = [];
        if(data.length == 0)
            chartData.push({
                categoria: 'Sin proyectos',
                value: 0
            });
        else
        {
            data.forEach(function(item) {
                chartData.push({
                    categoria: item.nombre_clasificacion_grupo_investigacion,
                    value: item.cantidad
                });                
            });
        }
        if($scope.chart_proys_x_clasificacion == null){
            
            $scope.chart_proys_x_clasificacion =  AmCharts.makeChart("proys_x_clasificacion",
                {
                	"type": "pie",
                	"angle": 12,
                	"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                	"depth3D": 15,
                	"innerRadius": "20%",
                	"colors": [
                		"#72ACD9",
                		"#AFE5FF",
                		"#B0DE09",
                		"#0D8ECF",
                		"#2A0CD0",
                		"#CD0D74",
                		"#CC0000",
                		"#00CC00",
                		"#0000CC",
                		"#DDDDDD",
                		"#999999",
                		"#333333",
                		"#990000"
                	],
                	"titleField": "categoria",
                	"valueField": "value",
                	"allLabels": [],
                	"balloon": {},
                	"legend": {
                		"enabled": true,
                		"markerType": "circle",
                		"valueText": "[[value]] ([[percents]]%)",
                		"align": "center",
                        "valueAlign": "left"
                	},
                	"titles": [
                		{
                			"id": "Title-1",
                			"size": 15,
                			"text": "Total de proyectos por clasificación del grupo de investigación ejecutor"
                		}
                	],
                	"dataProvider": chartData,
                	"responsive": {
                        "enabled": true
                    }
                }
            );       
    
            $scope.chart_proys_x_clasificacion.addListener("animationFinished", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_clasificacion.addListener("rendered", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_clasificacion.legend.addListener('hideItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_clasificacion.legend.addListener('showItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_clasificacion.addListener('drawn', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });                    
        }
        else{
            $scope.chart_proys_x_clasificacion.dataProvider = chartData;
            $scope.chart_proys_x_clasificacion.validateData();
            $scope.chart_proys_x_clasificacion.animateAgain();
        }                
    };
    
	/*
	|--------------------------------------------------------------------------
	| crear_chart_proys_x_area()
	|--------------------------------------------------------------------------
    | Crea la torta gráfica que presenta la cantidad de proyectos agrupados por el área colciencias de su grupo de investigación ejecutor
	*/                 
    $scope.crear_chart_proys_x_area = function(data) {
        
        var chartData = [];
        if(data.length == 0)
            chartData.push({
                categoria: 'Sin proyectos',
                value: 0
            });
        else
        {
            data.forEach(function(item) {
                chartData.push({
                    categoria: item.nombre_area,
                    value: item.cantidad
                });                
            });
        }
        if($scope.chart_proys_x_area == null){
            
            $scope.chart_proys_x_area =  AmCharts.makeChart("proys_x_area",
                {
                	"type": "pie",
                	"angle": 12,
                	"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                	"depth3D": 15,
                	"innerRadius": "20%",
                	"colors": [
                		"#72ACD9",
                		"#AFE5FF",
                		"#B0DE09",
                		"#0D8ECF",
                		"#2A0CD0",
                		"#CD0D74",
                		"#CC0000",
                		"#00CC00",
                		"#0000CC",
                		"#DDDDDD",
                		"#999999",
                		"#333333",
                		"#990000"
                	],
                	"titleField": "categoria",
                	"valueField": "value",
                	"allLabels": [],
                	"balloon": {},
                	"legend": {
                		"enabled": true,
                		"markerType": "circle",
                		"valueText": "[[value]] ([[percents]]%)",
                		"align": "center",
                        "valueAlign": "left"
                	},
                	"titles": [
                		{
                			"id": "Title-1",
                			"size": 15,
                			"text": "Total de proyectos por área Colciencias"
                		}
                	],
                	"dataProvider": chartData,
                	"responsive": {
                        "enabled": true
                    }
                }
            );       
    
            $scope.chart_proys_x_area.addListener("animationFinished", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_area.addListener("rendered", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_area.legend.addListener('hideItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_area.legend.addListener('showItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_proys_x_area.addListener('drawn', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });                    
        }
        else{
            $scope.chart_proys_x_area.dataProvider = chartData;
            $scope.chart_proys_x_area.validateData();
            $scope.chart_proys_x_area.animateAgain();
        }          
    };
    
	/*
	|--------------------------------------------------------------------------
	| cambia_filtro_sede()
	|--------------------------------------------------------------------------
    | ng-change para el filtro de sede
    | Alimenta el ui-select de facultades en función de la sede seleccionada
	*/             
    $scope.cambia_filtro_sede = function() {
        
        $scope.validar_filtro_sede();
        
        if($scope.data.sede_seleccionada == null || $scope.data.sede_seleccionada == undefined)
            return;
        
        $scope.data.facultad_seleccionada = null;
        $scope.data.grupo_investigacion_seleccionado = null;
        $scope.facultades_correspondientes = $scope.facultades_x_sedes[$scope.data.sede_seleccionada.id].facultades;
    };
    
	/*
	|--------------------------------------------------------------------------
	| cambia_filtro_facultad()
	|--------------------------------------------------------------------------
    | ng-change para el filtro de facultad
    | Alimenta el ui-select de grupos de investigación en función de la facultad seleccionada
	*/                 
    $scope.cambia_filtro_facultad = function() {
        
        $scope.validar_filtro_facultad();
        
        if($scope.data.facultad_seleccionada == null || $scope.data.facultad_seleccionada == undefined)
            return;        
        
        $scope.data.grupo_investigacion_seleccionado = null;
        $scope.grupos_inv_correspondientes = $scope.grupos_inv_x_facultades[$scope.data.facultad_seleccionada.id].grupos_investigacion;        
    };
    
    // validadores de filtros
    // retorna true si el valor del filtro es incorrecto
    $scope.validar_filtro_sede = function() {
        
        if($scope.filtro_sedes_deshabilitado){
            $scope.filtro_sede_invalido = false;
            return false;
        }
        else{
            if($scope.data.sede_seleccionada == null || $scope.data.sede_seleccionada == undefined){
                $scope.filtro_sede_invalido = true;
                return true;                
            }
            else{
                $scope.filtro_sede_invalido = false;
                return false;                                
            }
        }
    };
    
    $scope.validar_filtro_facultad = function() {
        
        if($scope.filtro_facultades_deshabilitado){
            $scope.filtro_facultad_invalido = false;
            return false;
        }
        else{
            if($scope.data.facultad_seleccionada == null || $scope.data.facultad_seleccionada == undefined){
                $scope.filtro_facultad_invalido = true;
                return true;                
            }
            else{
                $scope.filtro_facultad_invalido = false;
                return false;                                
            }
        }        
    };
    
    $scope.validar_filtro_grupo_investigacion = function() {
        
        if($scope.filtro_grupos_deshabilitado){
            $scope.filtro_grupo_invalido = false;
            return false;
        }
        else{
            if($scope.data.grupo_investigacion_seleccionado == null || $scope.data.grupo_investigacion_seleccionado == undefined){
                $scope.filtro_grupo_invalido = true;
                return true;                
            }
            else{
                $scope.filtro_grupo_invalido = false;
                return false;                                
            }
        }           
    };
    
	/*
	|--------------------------------------------------------------------------
	| filtrar_proyectos()
	|--------------------------------------------------------------------------
    | ng-click para boton que filtra y consulta los proyectos
    | Ejecuta la función de consulta de proyectos filtrados
    | Valida que se halla seleccionado un valor valido de las listas de filtros
	*/                     
    $scope.filtrar_proyectos = function() {
        
        // en función del tipo de filtro seleccionado, valida la selección de sus filtros
        var validacion_incorrecta = false;
        if($scope.tipo_filtro == 'sede')
        {
            validacion_incorrecta |= $scope.validar_filtro_sede();
        }
        else if($scope.tipo_filtro == 'facultad')
        {
            validacion_incorrecta |= $scope.validar_filtro_facultad();
        }
        else if($scope.tipo_filtro == 'grupo')
        {
            validacion_incorrecta |= $scope.validar_filtro_grupo_investigacion();
        }
        else{
            validacion_incorrecta |= $scope.validar_filtro_sede();
            validacion_incorrecta |= $scope.validar_filtro_facultad();
            validacion_incorrecta |= $scope.validar_filtro_grupo_investigacion();
        }
        
        if(validacion_incorrecta)
            return;
        else
            $scope.consultar_proyectos();
        
    };
    
	/*
	|--------------------------------------------------------------------------
	| consultar_proyectos()
	|--------------------------------------------------------------------------
    | Ejecuta consulta ajax por proyectos con un determinado filtro aplicado
	*/             
    $scope.consultar_proyectos = function() {
        
        $scope.msj_velo_tabla_proyectos = '<h4 class="text-center">Cargando proyectos...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
        $scope.show_velo_tabla_proyectos = true;
        $scope.consultado_proyectos = true;
        
        $http({
            url: '/proyectos/consultar_proyectos_filtrados',
            method: 'GET',
            params: {
                filtro: $scope.tipo_filtro,
                id_sede: $scope.tipo_filtro == 'sede' ? $scope.data.sede_seleccionada.id : null,
                id_facultad: $scope.tipo_filtro == 'facultad' ? $scope.data.facultad_seleccionada.id : null,
                id_grupo_investigacion: $scope.tipo_filtro == 'grupo' ? $scope.data.grupo_investigacion_seleccionado.id : null
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1)
            {
                $scope.proyectos = data.proyectos;
                $scope.show_velo_tabla_proyectos = false;
            }
            else
            {
                $scope.msj_velo_tabla_proyectos = '<h4 class="text-center">Un error ha ocurrido en la consulta de los proyectos. Volver a intentarlo. Código de error: ' + data.codigo + '</h4>';
            }
        })
        .error(function(data, status) {
            $scope.msj_velo_tabla_proyectos = '<h4 class="text-center">Un error XHR o de servidor ha ocurrido en la consulta de los proyectos. Volver a intentarlo. Código de estado: ' + status + '</h4>';
        })
        .finally(function() {
            $scope.consultado_proyectos = false;
        });
    };            
    
	/*
	|--------------------------------------------------------------------------
	| cambia_tipo_filtro()
	|--------------------------------------------------------------------------
    | ng-change para el radio button de tipo de filtro
    | Ejecuta deshabilitacin de ui-select como:
    | -Deshabilita el ui-select de facultades y grupos si se selecciona sede, borrando la selección de facultad y grupo
    | -Deshabilita el ui-select de grupos si se selecciona facultad, borrando la seleccion de grupo
	*/                     
    $scope.cambia_tipo_filtro = function(tipo_filtro) {
        
        if(tipo_filtro == 'sede'){
            $scope.filtro_sedes_deshabilitado = false;
            $scope.filtro_facultades_deshabilitado = true;
            $scope.filtro_grupos_deshabilitado = true;
            
            $scope.data.facultad_seleccionada = null;
            $scope.data.grupo_investigacion_seleccionado = null;
            
            $scope.tipo_filtro = 'sede';
        }
        else if(tipo_filtro == 'facultad'){
            $scope.filtro_sedes_deshabilitado = false;
            $scope.filtro_facultades_deshabilitado = false;
            $scope.filtro_grupos_deshabilitado = true;            
            
            $scope.data.grupo_investigacion_seleccionado = null;
            
            $scope.tipo_filtro = 'facultad';
        }
        else if(tipo_filtro == 'grupo'){
            $scope.filtro_sedes_deshabilitado = false;
            $scope.filtro_facultades_deshabilitado = false;
            $scope.filtro_grupos_deshabilitado = false;                        
            
            $scope.tipo_filtro = 'grupo';
        }
        else{
            $scope.filtro_sedes_deshabilitado = true;
            $scope.filtro_facultades_deshabilitado = true;
            $scope.filtro_grupos_deshabilitado = true;                        
            
            $scope.data.sede_seleccionada = null;
            $scope.data.facultad_seleccionada = null;
            $scope.data.grupo_investigacion_seleccionado = null;            
            
            // $scope.filtro_sede_invalido = false;
            // $scope.filtro_facultad_invalido = false;
            // $scope.filtro_grupo_invalido = false;
            
            $scope.tipo_filtro = null;
        }
        
        $scope.validar_filtro_sede();
        $scope.validar_filtro_facultad();            
        $scope.validar_filtro_grupo_investigacion();        
    };    
    
    // consulta por todos los proyectos de investigación
    $scope.cambia_tipo_filtro(null); // null especifica que no se establece ningun filtro
    $scope.consultar_proyectos();
    
	/*
	|--------------------------------------------------------------------------
	| mas_info()
	|--------------------------------------------------------------------------
	| Genera el evento de "mas_informacion_seleccionado"
	| Copia el id del proyecto seleccionado a data
	| Usado para que se consulte los detalles del proyecto y se presenten en el box de mas informacion
	*/                
    $scope.mas_info = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('mas_informacion_seleccionado');        
    };        
    
});