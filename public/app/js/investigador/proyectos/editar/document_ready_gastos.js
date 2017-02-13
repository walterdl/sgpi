$(document).ready(function() {
	
	$(window).keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
    
    $('[data-toggle="tooltip"]').tooltip();
    
	$('#contenedor_gastos_personal').perfectScrollbar();
	$('#contenedor_gastos_equipos').perfectScrollbar();
	$('#contenedor_gastos_software').perfectScrollbar();
	$('#contenedor_gastos_salidas').perfectScrollbar();
	$('#contenedor_gastos_materiales').perfectScrollbar();
	$('#contenedor_servicios_tecnicos').perfectScrollbar();
	$('#contenedor_recursos_bibliograficos').perfectScrollbar();
	$('#contenedor_recursos_digitales').perfectScrollbar();	
	
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        $('#contenedor_gastos_personal').perfectScrollbar('update');
        $("#contenedor_gastos_equipos").perfectScrollbar('update');
    	$('#contenedor_gastos_software').perfectScrollbar('update');
    	$('#contenedor_gastos_salidas').perfectScrollbar('update');
    	$('#contenedor_gastos_materiales').perfectScrollbar('update');
    	$('#contenedor_servicios_tecnicos').perfectScrollbar('update');
    	$('#contenedor_recursos_bibliograficos').perfectScrollbar('update');
    	$('#contenedor_recursos_digitales').perfectScrollbar('update');
    })
    .on('hidden.bs.collapse', function(){
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });    	
});