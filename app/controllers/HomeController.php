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
		
		if($cuser->id_rol == 1)
			return $this->adminDashboard();
			
		else if($cuser->id_rol == 2)
			return $this->coordinadorDashboard();
			
		else if($cuser->id_rol == 3)	
			return $this->investigadorDashboard();
	}
	
	/*
	|--------------------------------------------------------------------------
	| adminDashboard()
	|--------------------------------------------------------------------------
	| Presenta el dashboard del administrador
	*/
	private function adminDashboard(){
		
		$styles = ['app/css/dashboard/adminDashboard.css'];
		return View::make('dashboard', array(
			'styles' => $styles
			));
	}
	
	/*
	|--------------------------------------------------------------------------
	| coordinadorDashboard()
	|--------------------------------------------------------------------------
	| Presenta el dashboard del coordinador
	*/
	private function coordinadorDashboard(){
		$styles = ['app/css/dashboard/adminDashboard.css'];
		return View::make('dashboard', array(
			'styles' => $styles
			));
		// return View::make('home');
	}
	
	/*
	|--------------------------------------------------------------------------
	| investigadorDashboard()
	|--------------------------------------------------------------------------
	| Presenta el dashboard del investigador
	*/
	private function investigadorDashboard(){
		$styles = ['app/css/dashboard/adminDashboard.css'];
		return View::make('dashboard', array(
			'styles' => $styles
			));
		// return View::make('home');
	}

}
