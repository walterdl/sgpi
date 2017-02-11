$(document).ready(function() {
	
	$(window).keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});
	
    $('.collapse').on('shown.bs.collapse', function(){
        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
    })
    .on('hidden.bs.collapse', function(){
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });    
    
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
	$("#contenedor_gastos_personal").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true,
			// updateOnContentResize: true
		},
		autoHideScrollbar: false,
		autoDraggerLength: true
	}); 
	$("#contenedor_gastos_equipos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	$("#contenedor_gastos_software").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	$("#contenedor_gastos_salidas").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});	
	
	$("#contenedor_gastos_materiales").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	
	$("#contenedor_servicios_tecnicos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	
	$("#contenedor_recursos_bibliograficos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});	
	
	$("#contenedor_recursos_digitales").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});	
    // $('#tabs > li').css("pointer-events", "none");
    $('#tabs > li a').click(function (e) {
		e.preventDefault();
	});
});