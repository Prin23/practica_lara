<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissaoSistema extends Model
{
    //Nombre de la tabla, se debe especificar por que no cumple con las convenciones de laravel
    protected $table = 'permissao_sistema';

    /*
    * Metodo relacional con el modelo CaoUsuario
    */
    public function user()
    {
    	//Devuelve la relacion de forma inversa
    	return $this->belongsTo(CaoUsuario::class, 'co_usuario');
    }
}
