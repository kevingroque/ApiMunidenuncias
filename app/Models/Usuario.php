<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    
    protected $table = 'usuarios';
	public $timestamps = false;
	protected $hidden = ['password'];
	
	 public function denuncias(){
        return $this->hasMany('App\Models\Denuncia');
    }
}
