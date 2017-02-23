<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}
	
	/*
	|--------------------------------------------------------------------------
	| dashboard()
	|--------------------------------------------------------------------------
	| Punto de entrada para la presentación del dashboard adecuando al tipo de usuario autenticado.
	| Esta función llama a la funcion que renderiza la vista del dashboard del usuario. 
	*/
	public function dashboard(){
		
		$cuser = Auth::user(); // cuser = current user
		
		if($cuser->id_rol == 1 || $cuser->id_rol == 2 || $cuser->id_rol == 3)
		{
			$styles = ['app/css/dashboard/main.css'];
			return View::make('dashboard', array(
				'styles' => $styles
				));
		}
	}
}
