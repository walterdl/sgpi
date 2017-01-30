sgpi_app.controller('mas_info_proyecto_controller', function ($scope, $http){
    
    $scope.mas_info_poryecto_consultada = false;
    $scope.total_dias_proyecto = 0;
    $scope.dias_proyecto_transcurridos = 0;
    $scope.porcentaje_tiempo = 0;
    $scope.chart_desembolsos_mas_info = null;        
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento 'mas_informacion_seleccionado'
	| Detecta cuando la opción de más información de proyecto se ha sleccionado
	| Ejecuta consulta ajax por la información detallada del proyecto
	|--------------------------------------------------------------------------
	*/        
    $scope.$on('mas_informacion_seleccionado', function (event) {
        
        $scope.data.msj_mas_info_proy = '<h4 class="text-center">Cargando más información del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></<h4>';
        $scope.visibilidad.show_velo_mas_info_proy = true;
        
        $http({
            url: '/proyectos/mas_info_proyecto',
            method: 'GET',
            params: {
                'id_proyecto': $scope.data.id_proyecto
            }
        })
        .success(function(data){
            console.log(data);
            if(data.consultado == 1)
            {
                $scope.mas_info_proyecto_consultada = true;
                $scope.datos_generales_proyecto = data.datos_generales_proyecto;
                $scope.objetivos_especificos = data.objetivos_especificos;
                $scope.entidades_grupos_investigacion = data.entidades_grupos_investigacion;
                $scope.investigadores = data.investigadores;
                $scope.entidades_fuente_presupuesto = data.entidades_fuente_presupuesto;
                $scope.cantidad_productos = data.cantidad_productos;
                $scope.documentos_iniciales = data.documentos_iniciales;
                $scope.crear_chart_desembolso(data.desembolsos_aprobados);
                $scope.total_dias_proyecto = data.total_dias_proyecto;
                $scope.dias_proyecto_transcurridos = data.dias_proyecto_transcurridos;
                $scope.porcentaje_tiempo = Math.min(100, parseInt(100.0 * $scope.dias_proyecto_transcurridos / $scope.total_dias_proyecto ));                                
            }
            else
            {
                $scope.mas_info_proyecto_consultada = false;
                alertify.error('Error al consultar más información del proyecto. Código de error: ' + data.codigo);
            }
        })
        .error(function(data, status){
            console.log(data);            
            $scope.mas_info_proyecto_consultada = false;
            alertify.error('Error XHR o de servidor al consultar más información del proyecto. Código de estado: ' + status);            
        })
        .finally(function() {
            $scope.visibilidad.show_velo_mas_info_proy = false;
        });
    });
    
	/*
	|--------------------------------------------------------------------------
	| crear_chart_desembolso()
	|--------------------------------------------------------------------------
    | ncrea la torta gráfica que presenta la cantidad de desembolsos aprobados y no aporbados
	*/         
    $scope.crear_chart_desembolso = function(data) {
        
        data = [
                {
                    categoria: 'Aprobados',
                    value: data.aprobados
                },
                {
                    categoria: 'No aprobados',
                    value: data.no_aprobados
                }
            ];        
        if($scope.chart_desembolsos_mas_info == null){
            
            $scope.chart_desembolsos_mas_info =  AmCharts.makeChart("desembolsos_aprobados_mas_info",
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
                			"text": "Desembolsos aprobados / no aprobados"
                		}
                	],
                	"dataProvider": data,
                	"responsive": {
                        "enabled": true
                    }
                }
            );       
    
            $scope.chart_desembolsos_mas_info.addListener("animationFinished", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_desembolsos_mas_info.addListener("rendered", function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_desembolsos_mas_info.legend.addListener('hideItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_desembolsos_mas_info.legend.addListener('showItem', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });
            
            $scope.chart_desembolsos_mas_info.addListener('drawn', function(event){
                $('a[title="JavaScript charts"]').css('display', 'none');
            });                    
        }
        else{
            $scope.chart_desembolsos_mas_info.dataProvider = data;
            $scope.chart_desembolsos_mas_info.validateData();
            $scope.chart_desembolsos_mas_info.animateAgain();
        }        
    };        
    
	/*
	|--------------------------------------------------------------------------
	| abre_cierra_acordion()
	|--------------------------------------------------------------------------
	| Simple controlador de evento para click de anchor que abre o cierra acordion. 
	| Establece el icono glyphicon adecuado al acordion
	*/     
    $scope.abre_cierra_acordion = function(id_acordion) {
        if($('#' + id_acordion).hasClass('in')){
            $('#' + id_acordion).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#' + id_acordion).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };    
});
