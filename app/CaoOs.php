<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoOs extends Model
{
    //Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'cao_os';

    /*
    * Metodo relacional con el modelo CaoFactura
    */
    public function invoices()
    {
    	//Retorna la relacion entre los modelos, mediante el campo "co_os"
    	return $this->hasOne(CaoFactura::class, 'co_os', 'co_os');
    }

    /*
    * Metodo relacional con el modelo CaoUsuario
    */
    public function user()
    {
        //Devuelve la relacion de forma inversa
        return $this->belongsTo(CaoUsuario::class, 'co_usuario', 'co_usuario');
    }
}
