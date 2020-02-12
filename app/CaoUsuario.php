<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoUsuario extends Model
{	
	//Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'cao_usuario';

    /*
    * Metodo relacional con el modelo PermissaoSistema
    */
    public function permission()
    {
    	//Retorna la relacion entre los modelos, mediante el campo "co_usuario"
    	return $this->hasOne(PermissaoSistema::class, 'co_usuario', 'co_usuario');
    }
}
