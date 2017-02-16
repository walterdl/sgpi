var contador_mouseover_contenedor_gastos_personal = 0;
$(document).ready(function() {
	
    // $('#tabs > li').css("pointer-events", "none");
    $('#tabs > li a').click(function (e) {
		e.preventDefault();
	});	
	
	$(window).keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
	
	// previene que se ingrese números decimales
	// $('input[type="number"]').keydown(function(event) {
	// 	if (event.keyCode == 190 || event.keyCode == 188 || event.keyCode == 69 || event.keyCode == 107 || event.keyCode ==  109) { // no permite "enter", ".", "+", "-" y ","
	// 		event.preventDefault();
	// 		return false;
	// 	}
	// });	
	
	function actualizar_perfect_scrollbars(){
        $('#contenedor_gastos_personal').perfectScrollbar('update');
        $("#contenedor_gastos_equipos").perfectScrollbar('update');
    	$('#contenedor_gastos_software').perfectScrollbar('update');
    	$('#contenedor_gastos_salidas').perfectScrollbar('update');
    	$('#contenedor_gastos_materiales').perfectScrollbar('update');
    	$('#contenedor_servicios_tecnicos').perfectScrollbar('update');
    	$('#contenedor_recursos_bibliograficos').perfectScrollbar('update');
    	$('#contenedor_recursos_digitales').perfectScrollbar('update');	
	}	
    
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#contenedor_objetivos_esp').mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		autoHideScrollbar: true
    });
    $('#contenedor_tabla_participantes').mCustomScrollbar({
		axis:"y",
		theme: 'dark'
    });
	$("#contenedor_productos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	}); 
	
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
        actualizar_perfect_scrollbars();
    })
    .on('hidden.bs.collapse', function(){
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });    
    
    $(window).bind('resize', function () {
    	actualizar_perfect_scrollbars();
    });    
    
   $( "#contenedor_gastos_personal" ).mouseover(function() {
		if(contador_mouseover_contenedor_gastos_personal == 0)
		{
			$('#contenedor_gastos_personal').perfectScrollbar('update');
			contador_mouseover_contenedor_gastos_personal = 1;
		}
   });     
    
});