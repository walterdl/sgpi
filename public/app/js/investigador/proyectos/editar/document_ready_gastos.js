var contador_mouseover_contenedor_gastos_personal = 0;
$(document).ready(function() {
	
	$(window).keydown(function(event) {
		if(event.target.id != 'input_text_nueva_entidad')	
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
	$('#contenedor_gastos_servicios').perfectScrollbar();
	$('#contenedor_gastos_bibliograficos').perfectScrollbar();
	$('#contenedor_gastos_digitales').perfectScrollbar();	
	
	function actualizar_perfect_scrollbars(){
        $('#contenedor_gastos_personal').perfectScrollbar('update');
        $("#contenedor_gastos_equipos").perfectScrollbar('update');
    	$('#contenedor_gastos_software').perfectScrollbar('update');
    	$('#contenedor_gastos_salidas').perfectScrollbar('update');
    	$('#contenedor_gastos_materiales').perfectScrollbar('update');
    	$('#contenedor_gastos_servicios').perfectScrollbar('update');
    	$('#contenedor_gastos_bibliograficos').perfectScrollbar('update');
    	$('#contenedor_gastos_digitales').perfectScrollbar('update');	
	}
		
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