<?php

class RemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		
		$response= Password::remind(Input::only('email'), function($message)
		{
		    $message->subject('Password Reminder');
		});
		
		
		// if( $response == Password::INVALID_USER){
		// 	echo "hola mundo1";
		// 	die();
		// 	return Redirect::back()->with('error', Lang::get($response));
		// }else{
		// 	echo "hola mundo2";
		// 	die();
		// 	// return Redirect::back()->with('status', Lang::get($response));
		// }

		switch ($response)
		{
			case Password::INVALID_USER:
		        // echo "hola mundo1";
				return View::make('login',array('user'=>'1','mensaje' =>'Error el correo no existe'));

			case Password::REMINDER_SENT:
				// echo "hola mundo2";
				return View::make('login',array('user'=>'0','mensaje' =>'Correo valido'));
		}
		
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);
		
		return View::make('general.password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);
		
		// print_r($credentials);
		// die();
		
		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				// return View::make('general.password.reset')->with('token', $token);
				return View::make('general.password.reset',array('token'=> $credentials['token'],'user'=>'1','mensaje' =>Lang::get($response)));
				//return Redirect::back()->with(array('user'=>'1','mensaje' =>Lang::get($response)));

			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}

}
