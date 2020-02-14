<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaoFactura extends Model
{
    //Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'cao_fatura';

    //Campos que el ORM debe tratar como objetos Carbon (implementacion de DateTime en Laravel)
    protected $dates = ['data_emissao'];

    /*
    * Metodo relacional con el modelo CaoCliente
    */
    public function client()
    {
    	//Retorna la relacion entre los modelos, mediante el campo "co_cliente"
    	return $this->belongsTo(CaoCliente::class, 'co_cliente', 'co_cliente');
    }

    /*
    * Metodo relacional con el modelo CaoOs
    */
    public function os()
    {
    	//Retorna la relacion entre los modelos, mediante el campo "co_cliente"
    	return $this->belongsTo(CaoOs::class, 'co_os', 'co_os');
    }
}
