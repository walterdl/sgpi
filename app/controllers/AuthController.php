<?php
 
class AuthController extends BaseController {

    public function check(){

        $userdata = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        ); 

        if(Auth::attempt($userdata))
            return Redirect::to('home');
        else
            return Redirect::to('/')->with('login_errors', true);
        
    }
    
}