<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\CaoFactura;
use Illuminate\Http\Request;

class CaoFacturaController extends Controller
{
	function __construct() 
	{
		setlocale(LC_TIME, 'pt_BR.utf8');
	}

    public function relatorio(Request $request)
    {
    	//Arreglo donde guardare toda la informacion formateada
    	$data = [];

    	//Creo objetos carbon para manipular las fechas de comienzo y fin
    	$date_start = Carbon::createFromFormat('Y-m', $request->get('start_year').'-'.$request->get('start_month'))->startOfMonth();
    	$date_end = Carbon::createFromFormat('Y-m', $request->get('end_year').'-'.$request->get('end_month'))->endOfMonth();

    	/*Selecciono las facturas segun los criterios:
    	* 1) Que esten dentro del rango de fechas de inicio y fin
    	* 2) Que pertenezcan a los usuarios seleccionados
    	*/
    	$invoices = CaoFactura::whereBetween('data_emissao', [$date_start->format('Y-m-d'), $date_end->format('Y-m-d')])
    		->whereHas('os', function($query) use ($request) {
    			$query->whereIn('co_usuario', $request->get('co_usuarios'));
    		})
    		->get();

    	//Cargo las relaciones de las facturas obtenidas
    	$invoices->load('os.user.salary');

    	//Recorro las facturas mientras formateo la data
    	$invoices->each(function($invoice) use (&$data) {
    		//Calculo de "Receita Liquida"
    		$receita = $invoice->valor - ($invoice->valor * ($invoice->total_imp_inc/100));
    		//Calculo de Comissao
    		$comissao = $receita * ($invoice->comissao_cn/100);
    		//Calculo de Lucro
    		$lucro = $receita - ($invoice->os->user->salary->brut_salario + $comissao);

    		//Si el usuario no esta en la matriz seteo los valores inciales
    		if (!in_array($invoice->os->co_usuario, $data)) {
    			$data[$invoice->os->co_usuario]['total_receita'] = $receita;
    			$data[$invoice->os->co_usuario]['total_fixo'] = $invoice->os->user->salary->brut_salario;
    			$data[$invoice->os->co_usuario]['total_comissao'] = $comissao;
    			$data[$invoice->os->co_usuario]['total_lucro'] = $lucro;
    		}

    		if (!in_array($invoice->data_emissao->format('Y-m'), $data[$invoice->os->co_usuario])) {
    			//Si el mes para el usuario no esta en la matriz seteo los valores inciales
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['mes'] = ucfirst($invoice->data_emissao->formatLocalized('%B'));
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['receita'] = $receita;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['fixo'] = $invoice->os->user->salary->brut_salario;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['comissao'] = $comissao;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['lucro'] = $lucro;
    		} else {
    			//De lo contrario sumo los valores nuevos con los que ya se encuentran
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['receita'] += $receita;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['comissao'] += $comissao;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['lucro'] += $lucro;

    			$data[$invoice->os->co_usuario]['total_receita'] += $receita;
    			$data[$invoice->os->co_usuario]['total_comissao'] += $comissao;
    			$data[$invoice->os->co_usuario]['total_lucro'] += $lucro;
    		}
    	});
    	
    	return response()->json($data, 200);
    }
}
