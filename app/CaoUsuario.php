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

    /*
    * Metodo relacional con el modelo CaoSalario
    */
    public function salary()
    {
        //Retorna la relacion entre los modelos, mediante el campo "co_usuario"
        return $this->hasOne(CaoSalario::class, 'co_usuario', 'co_usuario');
    }

    /*
    * Metodo relacional con el modelo CaoOs
    */
    public function os()
    {
        //Retorna la relacion entre los modelos, mediante el campo "co_os"
        return $this->hasMany(CaoOs::class, 'co_usuario', 'co_usuario');
    }
}
