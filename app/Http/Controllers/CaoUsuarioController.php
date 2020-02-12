<?php

namespace App\Http\Controllers;

use App\CaoUsuario;
use Illuminate\Http\Request;

class CaoUsuarioController extends Controller
{
	/**
	* listUserByType
	*
	* Lista los usuarios de pendiendo del tipo enviado en la peticion
	* por default muestra los consultores
	*
	* @param Request $request Objeto request con el que se manejan las peticiones
	*
	* @return response
	**/
    public function listUsersByType(Request $request)
    {
    	$users = CaoUsuario::whereHas('permission', function ($query) {
    		$query->where('co_sistema', 1)
    			->where('in_ativo', 'S')
    			->whereIn('co_tipo_usuario', [0, 1, 2]);
    	})->get();

    	return response()->json([
    		'users' => $users
    	], 200);
    }
}
