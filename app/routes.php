<?php

URL::forceSchema('https'); // Configura las urls generadas por url() a travéz de https

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Ruta ver_info_php()
|--------------------------------------------------------------------------
| Muestra la configuración de php usada
|
*/
Route::get('ver_info_php', function(){
	echo phpinfo();	
	
});


/*
|--------------------------------------------------------------------------
| Ruta postRemind()
|--------------------------------------------------------------------------
| despues de recordar
|
*/
Route::post('postRemind', 'RemindersController@postRemind');
Route::get('password/reset/{token}', 'RemindersController@getReset');
Route::post('postReset', 'RemindersController@postReset');


/*
|--------------------------------------------------------------------------
| Ruta pass()
|--------------------------------------------------------------------------
| Envia correo electronico
|
*/
Route::get('pass', function(){
	
	Mail::send('emails.welcome', array('key' => 'value'), function($message)
	{
	    $message->from('sgpiucc@gmail.com', 'SGPI');
	
	    // $message->to('brando.polis@hotmail.com');
	    //$message->to('brando.polis@hotmail.com', 'John Smith')->subject('Welcome!');
	    $message->to('jose_.devia@hotmail.com')->cc('jbhenao17@misena.edu.co');
	
	});
	
});

	
/*
|--------------------------------------------------------------------------
| Ruta \
|--------------------------------------------------------------------------
| Ruta raíz que redirije a login solo para aquellos no autenticados
|
*/
Route::get('/', array('before' => 'validate', function(){

	return View::make('login');
}));


/*
|--------------------------------------------------------------------------
| Ruta \
|--------------------------------------------------------------------------
| Ruta raíz que redirije a login solo para aquellos no autenticados
|
*/
Route::get('login', array('before' => 'validate', function(){
	return View::make('login');
}));


/*
|--------------------------------------------------------------------------
| Ruta home
|--------------------------------------------------------------------------
| Ruta raíz de dashboard para usuarios autenticados
|
*/
Route::get('home', array('before' => 'auth', 'uses' => 'HomeController@dashboard'));


/*
|--------------------------------------------------------------------------
| Ruta salir
|--------------------------------------------------------------------------
| Dedicada para cierre de sesión
|
*/
Route::get('salir', function()
{
	Auth::logout();
	return View::make('login'); 
});


/*
|--------------------------------------------------------------------------
| Grupo de rutas de los usuarios autenticados
|--------------------------------------------------------------------------
| Se contiene todas las rutas de todos los tipos de usuarios
| protegidas por filtro de autenticación
*/
Route::group(array('before' => 'auth'), function(){

	
	/*********Detecta si el usuario no tiene un rol correcto**********/
	if(empty(Auth::user()->id_rol)){
		return View::make('login');
	}
	
	/*********Rutas administrador**********/
	else if(Auth::user()->id_rol == 1){
		
		// Sirve las imágenes de perfil
		Route::get('file/imagen_perfil/{nombre_foto?}', 'FileController@get_imagen_perfil');
		
		// Sirve los formatos guía de los documentos
		Route::get('file/formato', 'FileController@get_formato_documento');
		
		// sirve archivos de presupuesto
		Route::get('file/presupuesto/{nombre_archivo}', 'FileController@get_presupuesto');
		
		// sirve archivos de presentación de proyecto
		Route::get('file/presentacion_proyecto/{nombre_archivo}', 'FileController@get_presentacion_proyecto');		
		
		// sirve archivos de acta de inicio
		Route::get('file/acta_inicio/{nombre_archivo}', 'FileController@get_acta_inicio');				
		
		// Sirve archivos de los productos
		Route::get('file/producto_fecha_proyectada_radicacion/{nombre_archivo}', 'FileController@get_archivo_fecha_proyectada_radicacion');
		Route::get('file/producto_fecha_publicacion/{nombre_archivo}', 'FileController@get_archivo_fecha_publicacion');
		
		// sirve los archivos de desembolso
		Route::get('file/desembolso/{nombre_archivo}', 'FileController@get_desembolso');
		
		// sirve los archivos de informes de avance
		Route::get('file/informe_avance/{nombre_archivo}', 'FileController@get_informe_avance');
		
		// sirve los archivos de acta de finalización
		Route::get('file/acta_finalizacion/{nombre_archivo}', 'FileController@get_acta_finalizacion');
		
		// sirve los archivos de memoria académica
		Route::get('file/memoria_academica/{nombre_archivo}', 'FileController@get_memoria_academica');
		
		// sirve los archivos de prórroga
		Route::get('file/prorroga/{nombre_archivo}', 'FileController@get_prorroga');		
		

		// Grupos de investigación
		Route::get('grupos', 'GruposInvestigacionController@listar');
	    Route::get('grupos/listar', 'GruposInvestigacionController@listar');
	    Route::get('grupos/grupos_investigacion_x_sede', 'GruposInvestigacionController@get_grupos_investigacion');
	    Route::get('grupos/lineas_investigacion_x_grupo_inv', 'GruposInvestigacionController@get_lineas_investigacion_x_grupo_inv');
	    Route::get('grupos/registrar', 'GruposInvestigacionController@crear');
	    Route::get('grupos/data_inicial_vista_crear', 'GruposInvestigacionController@data_inicial_vista_crear');
	    Route::post('grupos/guardar_nuevo_grupo_inv', 'GruposInvestigacionController@guardar_nuevo_grupo_inv');
	    Route::get('grupos/consultar_nombre_grupo', 'GruposInvestigacionController@consultar_nombre_grupo');
	    Route::get('grupos/editar', 'GruposInvestigacionController@editar');
	    Route::get('grupos/data_inicial_vista_editar', 'GruposInvestigacionController@data_inicial_vista_editar');
	    Route::get('grupos/validar_edicion_grupo_inv', 'GruposInvestigacionController@validar_edicion_grupo_inv');
	    Route::post('grupos/guardar_edicion_grupo_inv', 'GruposInvestigacionController@guardar_edicion_grupo_inv');
	    Route::get('grupos/validar_eliminacion_grupo_inv', 'GruposInvestigacionController@validar_eliminacion_grupo_inv');
	    
	    // Líneas de investigación
	    Route::get('lineas_investigacion/listar', 'LineasInvestigacionController@index');
	    Route::get('lineas_investigacion/data_inicial_vista_listar', 'LineasInvestigacionController@data_inicial_vista_listar');
	    Route::post('lineas_investigacion/guardar_edicion', 'LineasInvestigacionController@guardar_edicion');
	    Route::get('lineas_investigacion/eliminar', 'LineasInvestigacionController@eliminar');
	    Route::post('lineas_investigacion/crear', 'LineasInvestigacionController@crear');
	    
	    // Usuarios y propio perfil
	    Route::get('usuarios/listar', 'UsuariosController@listar');
	    Route::get('usuarios/datos_iniciales_listar_usuarios', 'UsuariosController@usuarios_para_admin');
	    
	    Route::get('/usuarios/cambiarEstado', 'UsuariosController@cambiarEstado');
	    Route::get('/usuarios/editar/{id}', 'UsuariosController@editarVer');
	    Route::get('/usuarios/datos/editar', 'UsuariosController@usuarioEditar');
	    
	    Route::get('usuarios/mas_info_usuario', 'UsuariosController@mas_info_usuario');
	    Route::get('usuarios/registrar', 'UsuariosController@crear');
	    Route::get('usuarios/data_inicial_vista_crear', 'UsuariosController@data_inicial_vista_crear');
	    Route::get('usuarios/validar_crear_usuario', 'UsuariosController@validar_crear_usuario');
	    Route::post('usuarios/guardar_nuevo_usuario', 'UsuariosController@guardar_nuevo_usuario');
	    Route::post('usuarios/actualizar_usuario', 'UsuariosController@actualizar_usuario');
	    Route::get('usuarios/buscar_datos_basicos', 'UsuariosController@buscar_datos_basicos');
	    Route::get('usuarios/editar_propio_perfil', 'UsuariosController@editar_propio_perfil');
	    Route::get('usuario/editar', 'UsuariosController@editar');
	    
	    // Propio perfil
		Route::get('usuarios/propio_perfil', 'UsuariosController@ver_propio_perfil');
		Route::get('usuarios/data_inicial_propio_perfil', 'UsuariosController@data_inicial_propio_perfil');
		Route::post('usuarios/guardar_edicion_propio_perfil', 'UsuariosController@guardar_edicion_propio_perfil');
		Route::post('usuarios/cambiar_contrasenia', 'UsuariosController@cambiar_contrasenia');
		Route::post('usuarios/validar_username_identificacion', 'UsuariosController@validar_username_identificacion');
		
		// Proyectos
		Route::get('proyectos/listar', 'GestionProyectosController@listar_proyectos_administrador');
		Route::get('proyectos/proyectos_administrador', 'GestionProyectosController@proyectos_administrador');
		Route::get('proyectos/mas_info_proyecto', 'GestionProyectosController@mas_info_proyecto');
		Route::get('proyectos/productos_de_proyecto', 'GestionProyectosController@productos_de_proyecto');		
		Route::get('proyectos/verificar_existencia_archivo_fecha_proyectada_postular', 'GestionProyectosController@consultar_producto_fecha_proyectada_postular');
		Route::get('proyectos/verificar_existencia_archivo_fecha_publicacion', 'GestionProyectosController@consultar_producto_fecha_publicacion');		
		Route::get('proyectos/gastos_de_proyecto', 'GestionProyectosController@gastos_de_proyecto');		
		Route::get('proyectos/revision_desembolso', 'GestionProyectosController@revision_desembolso');
		Route::post('proyectos/guardar_revision_desembolso', 'GestionProyectosController@guardar_revision_desembolso');
	    
	    //Retorna 404 para ruta de administrador no encontrada
		App::missing(function($e) 
		{ 
		    $url = Request::fullUrl(); 
		    Log::warning("404 for URL: $url"); 
		     return View::make('nofound');
		});
	}
	
	/*********Rutas coordinador**********/
	else if (Auth::user()->id_rol == 2) {

		// Ruta que sirve las imágenes de perfil
		Route::get('file/imagen_perfil/{nombre_foto?}', 'FileController@get_imagen_perfil');
		
		// Sirve los formatos guía de los documentos
		Route::get('file/formato/', 'FileController@get_formato_documento');		
		
	    // Propio perfil
		Route::get('usuarios/propio_perfil', 'UsuariosController@ver_propio_perfil');
		Route::get('usuarios/data_inicial_propio_perfil', 'UsuariosController@data_inicial_propio_perfil');
		Route::post('usuarios/guardar_edicion_propio_perfil', 'UsuariosController@guardar_edicion_propio_perfil');
		Route::post('usuarios/cambiar_contrasenia', 'UsuariosController@cambiar_contrasenia');
		Route::post('usuarios/validar_username_identificacion', 'UsuariosController@validar_username_identificacion');		
		
        //Retorna 404 para ruta de administrador no encontrada
		App::missing(function($e) 
		{ 
		    $url = Request::fullUrl(); 
		    Log::warning("404 for URL: $url"); 
		     return View::make('nofound');
		});
	}
	
	/*********Rutas investigador**********/
	else if (Auth::user()->id_rol == 3) {
		
		// Ruta que sirve las imágenes de perfil
		Route::get('file/imagen_perfil/{nombre_foto?}', 'FileController@get_imagen_perfil');
		
		// Sirve los formatos guía de los documentos
		Route::get('file/formato/', 'FileController@get_formato_documento');
		
		// sirve archivos de presupuesto
		Route::get('file/presupuesto/{nombre_archivo}', 'FileController@get_presupuesto');
		
		// sirve archivos de presentación de proyecto
		Route::get('file/presentacion_proyecto/{nombre_archivo}', 'FileController@get_presentacion_proyecto');		
		
		// sirve archivos de acta de inicio
		Route::get('file/acta_inicio/{nombre_archivo}', 'FileController@get_acta_inicio');				
		
		// Sirve archivos de los productos
		Route::get('file/producto_fecha_proyectada_radicacion/{nombre_archivo}', 'FileController@get_archivo_fecha_proyectada_radicacion');
		Route::get('file/producto_fecha_publicacion/{nombre_archivo}', 'FileController@get_archivo_fecha_publicacion');
		
		// sirve los archivos de desembolso
		Route::get('file/desembolso/{nombre_archivo}', 'FileController@get_desembolso');
		
		// sirve los archivos de informes de avance
		Route::get('file/informe_avance/{nombre_archivo}', 'FileController@get_informe_avance');
		
		// sirve los archivos de acta de finalización
		Route::get('file/acta_finalizacion/{nombre_archivo}', 'FileController@get_acta_finalizacion');
		
		// sirve los archivos de memoria académica
		Route::get('file/memoria_academica/{nombre_archivo}', 'FileController@get_memoria_academica');
		
		// sirve los archivos de prórroga
		Route::get('file/prorroga/{nombre_archivo}', 'FileController@get_prorroga');
		
		// Propio perfil
		Route::get('usuarios/propio_perfil', 'UsuariosController@ver_propio_perfil');
		Route::get('usuarios/data_inicial_propio_perfil', 'UsuariosController@data_inicial_propio_perfil');
		Route::post('usuarios/guardar_edicion_propio_perfil', 'UsuariosController@guardar_edicion_propio_perfil');
		Route::post('usuarios/cambiar_contrasenia', 'UsuariosController@cambiar_contrasenia');
		Route::post('usuarios/validar_username_identificacion', 'UsuariosController@validar_username_identificacion');
		
		// proyectos
		Route::get('proyectos/listar', 'GestionProyectosController@listar_proyectos_investigador_principal');
		Route::get('proyectos/proyectos_investigador_principal', 'GestionProyectosController@proyectos_investigador_principal');
		Route::get('proyectos/registrar', 'ProyectosController@crear');
		Route::get('proyectos/data_inicial_crear_proyecto', 'ProyectosController@data_inicial_crear_proyecto');
		Route::get('usuarios/buscar_datos_basicos', 'UsuariosController@buscar_datos_basicos'); // permite buscar datos básicos de un participante del proyecto
		Route::post('proyectos/registrar_nuevo_proyecto', 'ProyectosController@registrar_nuevo_proyecto');
		Route::get('proyectos/productos_de_proyecto', 'GestionProyectosController@productos_de_proyecto');
		Route::post('proyectos/cargar_producto_fecha_proyectada_radicacion', 'GestionProyectosController@guardar_producto_fecha_proyectada_radicacion');
		Route::get('proyectos/verificar_existencia_archivo_fecha_proyectada_postular', 'GestionProyectosController@consultar_producto_fecha_proyectada_postular');
		Route::get('proyectos/verificar_existencia_archivo_fecha_publicacion', 'GestionProyectosController@consultar_producto_fecha_publicacion');
		Route::post('proyectos/cargar_producto_fecha_publicacion', 'GestionProyectosController@guardar_producto_fecha_publicacion');
		Route::get('proyectos/gastos_de_proyecto', 'GestionProyectosController@gastos_de_proyecto');
		Route::get('proyectos/revision_desembolso', 'GestionProyectosController@revision_desembolso');
		Route::post('/proyectos/cargar_desembolso', 'GestionProyectosController@guardar_desembolso');
		Route::get('proyectos/informe_avance', 'GestionProyectosController@consultar_informe_avance');
		Route::post('proyectos/cargar_informe_avance', 'GestionProyectosController@guardar_informe_avance');
		Route::get('proyectos/final_proyecto', 'GestionProyectosController@consultar_final_proyecto');
		Route::post('proyectos/cargar_final_proyecto', 'GestionProyectosController@guardar_final_proyecto');
		Route::get('proyectos/prorroga', 'GestionProyectosController@consultar_prorroga');
		Route::post('proyectos/cargar_prorroga', 'GestionProyectosController@guardar_prorroga');
		Route::get('proyectos/mas_info_proyecto', 'GestionProyectosController@mas_info_proyecto');
		
		/*
		---------------------------------------------------------------------------
		RUTAS EDITAR PROYECTO
		*/
		Route::get('proyectos/editar/{pagina}/{id}', 'ProyectosController@editarVer');
		Route::get('proyectos/datos_iniciales_editar_proyecto', 'ProyectosController@datos_iniciales_editar_proyecto');
		
		
		
		// Retorna 404 para ruta de administrador no encontrada
		App::missing(function($e) 
		{ 
		    $url = Request::fullUrl(); 
		    Log::warning("404 for URL: $url"); 
		     return View::make('nofound');
		});
	}
});


/*
|--------------------------------------------------------------------------
| Rutas de usuarios temporalmente afuera para pruebas
|--------------------------------------------------------------------------
*/
// Route::get('proyectos/registrar_nuevo_proyecto', 'ProyectosController@registrar_nuevo_proyecto');


/*
|--------------------------------------------------------------------------
| Ruta check
|--------------------------------------------------------------------------
| Autentica los usuarios
|
*/
Route::post('check', 'AuthController@check');


/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
| Retorna la página 404
|
*/
App::missing(function($e) 
{ 
    $url = Request::fullUrl(); 
    Log::warning("404 for URL: $url"); 
    return Response::make('<h1>404 not found!</h1><br> <a href="/">ir a Login</a>', 404); 
});


/*
|--------------------------------------------------------------------------
| Germina la bd con los primeros registros necesarios para crear el primer usuario admin
|--------------------------------------------------------------------------
|
*/
Route::get('sembrar_bd', 'BaseController@sembrar_bd');


/*
|--------------------------------------------------------------------------
| Trunca la BD
|--------------------------------------------------------------------------
|
*/
Route::get('truncar_bd', 'BaseController@truncar_bd');


/*
|--------------------------------------------------------------------------
| test
|--------------------------------------------------------------------------
| Prueba impresiones
*/
Route::get('test', function(){
	
	$proyecto = Proyecto::find(1);
	echo $proyecto->updated_at->format('Y-m-d');
});

