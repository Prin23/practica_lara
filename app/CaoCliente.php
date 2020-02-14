<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoCliente extends Model
{
    //Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'cao_cliente';

    /*
    * Metodo relacional con el modelo CaoFactura
    */
    public function invoices()
    {
    	//Retorna la relacion entre los modelos, mediante el campo "co_cliente"
    	return $this->hasMany(CaoFactura::class, 'co_cliente', 'co_cliente');
    }
}
