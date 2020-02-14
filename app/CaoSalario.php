<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoSalario extends Model
{
    //Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'cao_salario';

    /*
    * Metodo relacional con el modelo CaoUsuario
    */
    public function user()
    {
    	//Devuelve la relacion de forma inversa
    	return $this->belongsTo(CaoUsuario::class, 'co_usuario', 'co_usuario');
    }
}
