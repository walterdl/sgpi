<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuarios';
	
	protected $fillable = [
            'id_persona',
            'id_rol',
            'id_estado',
            'id_grupo_investigacion_ucc',
            'username',
            'password',
            'email'
            ];
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	 
	 
	protected $hidden = array('password', 'remember_token');
	
    public function persona(){ 
        return $this->belongsTo('Persona', 'id_persona'); 
    }
    
    public function rol() { 
        return $this->belongsTo('Rol', 'id_rol'); 
    }
    
    public function estado() { 
        return $this->belongsTo('Estado', 'id_estado'); 
    }

}
