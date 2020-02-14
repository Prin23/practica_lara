<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\CaoFactura;
use Faker\Factory as Faker;
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
    		//Calculo de "Comissao"
    		$comissao = $receita * ($invoice->comissao_cn/100);
    		//Calculo de "Lucro"
    		$lucro = $receita - ($invoice->os->user->salary->brut_salario + $comissao);

    		//Si el usuario no esta en la matriz seteo los valores inciales
    		if (!array_key_exists($invoice->os->co_usuario, $data)) {
    			$data[$invoice->os->co_usuario]['no_usuario'] = $invoice->os->user->no_usuario;
    			$data[$invoice->os->co_usuario]['total_receita'] = $receita;
    			$data[$invoice->os->co_usuario]['total_fixo'] = $invoice->os->user->salary->brut_salario;
    			$data[$invoice->os->co_usuario]['total_comissao'] = $comissao;
    			$data[$invoice->os->co_usuario]['total_lucro'] = $lucro;
    		} else {
    			$data[$invoice->os->co_usuario]['total_receita'] = $data[$invoice->os->co_usuario]['total_receita'] + $receita;
    			$data[$invoice->os->co_usuario]['total_comissao'] = $data[$invoice->os->co_usuario]['total_comissao'] + $comissao;
    			$data[$invoice->os->co_usuario]['total_lucro'] = $data[$invoice->os->co_usuario]['total_lucro'] + $lucro;
    		}


    		if (!array_key_exists($invoice->data_emissao->format('Y-m'), $data[$invoice->os->co_usuario])) {
    			//Si el mes para el usuario no esta en la matriz seteo los valores inciales
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['mes'] = ucfirst($invoice->data_emissao->formatLocalized('%B de %Y'));
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['receita'] = $receita;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['fixo'] = $invoice->os->user->salary->brut_salario;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['comissao'] = $comissao;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['lucro'] = $lucro;
    		} else {
    			//De lo contrario sumo los valores nuevos con los que ya se encuentran
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['receita'] += $receita;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['comissao'] += $comissao;
    			$data[$invoice->os->co_usuario][$invoice->data_emissao->format('Y-m')]['lucro'] += $lucro;
    		}
    	});

    	foreach ($data as $key => $user) {
    		$multiple = 0;

    		//Recorro los datos del usuario
    		foreach ($user as $k => $v) {
    			//Cuento los campos que sea de tipo arreglo, que son los meses 
    			if (is_array($v)) {
    				$multiple ++;

    				//Formateo los valores por mes
					$v['receita'] = $this->formatNumber($v['receita']);
					$v['fixo'] = $this->formatNumber($v['fixo']);
					$v['comissao'] = $this->formatNumber($v['comissao']);
					$v['lucro'] = $this->formatNumber($v['lucro']);
    				
    				$user[$k] = $v;
    			}
    		}

    		//Calculo el total "Custo Fixo"
    		$multiple != 0 ? $user['total_fixo'] *= $multiple : $user['total_fixo'] *= 1;

    		//Formateo los totales
    		$user['total_receita'] = $this->formatNumber($user['total_receita']);
    		$user['total_fixo'] = $this->formatNumber($user['total_fixo']);
    		$user['total_comissao'] = $this->formatNumber($user['total_comissao']);
    		$user['total_lucro'] = $this->formatNumber($user['total_lucro']);

    		//Ordeno el arreglo por key, para mostrar la informacion de forma ordenada
    		ksort($user);

    		$data[$key] = $user;
    	}
    	
    	return response()->json($data, 200);
    }

    public function chartPie(Request $request)
    {
    	//Arreglos donde guardare toda la informacion formateada
    	$data_users = [];
    	$total = 0; // El total representa el 100% en base al cual se calculara el porcentaje de participacion de cada consultor
    	$faker = Faker::create();//Implemento faker para generar colores

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
    	$invoices->load('os.user');

    	//Recorro las facturas mientras formateo la data
    	$invoices->each(function($invoice) use (&$data_users, &$total, $faker) {
    		//Calculo de "Receita Liquida"
    		$receita = $invoice->valor - ($invoice->valor * ($invoice->total_imp_inc/100));

    		//Si el usuario no esta en la matriz seteo los valores inciales
    		if (!array_key_exists($invoice->os->co_usuario, $data_users)) {
    			$data_users[$invoice->os->co_usuario] = ['value' => $receita, 'label' => $invoice->os->user->no_usuario, 'color' => $faker->hexColor];
    		} else {
    			$data_users[$invoice->os->co_usuario]['value'] += $receita;
    		}

    		$total += $receita;

    	});

    	//calculo de porcentajes
    	foreach ($data_users as $key => $user) {
    		$user['value'] = ($user['value'] * 100)/$total;

    		$data_users[$key] = $user;
    	}


    	return response()->json($data_users, 200);
    }

    public function chartBar(Request $request)
    {
    	//Arreglos donde guardare toda la informacion formateada
    	$data = [];
    	$users_data = [];

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
    	$invoices->load('os.user');

    	//Recorro las facturas mientras formateo la data
    	$invoices->each(function($invoice) use (&$data, &$users_data) {
    		//Calculo de "Receita Liquida"
    		$receita = $invoice->valor - ($invoice->valor * ($invoice->total_imp_inc/100));

    		if (!array_key_exists($invoice->os->co_usuario, $users_data)) {
    			$users_data[$invoice->os->co_usuario] = ['name' => $invoice->os->user->no_usuario, 'salary' => $invoice->os->user->salary->brut_salario];
    		}

    		if (!array_key_exists($invoice->data_emissao->format('Y-m'), $data) || !array_key_exists($invoice->os->co_usuario, $data[$invoice->data_emissao->format('Y-m')])) {
    			$data[$invoice->data_emissao->format('Y-m')]['mes'] = ucfirst($invoice->data_emissao->formatLocalized('%B de %Y'));
    			$data[$invoice->data_emissao->format('Y-m')][$invoice->os->co_usuario]['no_usuario'] = $invoice->os->user->no_usuario;
    			$data[$invoice->data_emissao->format('Y-m')][$invoice->os->co_usuario]['receita'] = $receita;
    			$data[$invoice->data_emissao->format('Y-m')][$invoice->os->co_usuario]['fixo'] = $invoice->os->user->salary->brut_salario;
    		} else {
    			//De lo contrario sumo los valores nuevos con los que ya se encuentran
    			$data[$invoice->data_emissao->format('Y-m')][$invoice->os->co_usuario]['receita'] += $receita;
    		}
    	});


    	//igualo la cantidad de datos
    	foreach ($data as $key => $mounth) {
    		
    		foreach ($users_data as $k => $value) {
    			if (!array_key_exists($k, $mounth)) {
    				$mounth[$k] = [
    					'receita' => 0,
    					'no_usuario' => $value['name'],
    					'fixo' => $value['salary']
    				];
    			}
    		}

			$data[$key] = $mounth;
    	}

    	//Calculo de promedio de coso fijo
    	foreach ($data as $key => $value) {
    		$avg = 0;
    		$count = 0;

    		foreach ($value as $k => $v) {
    			if (is_array($v)) {
    				$avg += $v['fixo'];
    				$count ++;
    			}
    		}

    		$data[$key]['avg'] = $avg/$count;
    	}

    	ksort($data);

        //formateo de data
        $rdata = [];
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if (is_array($v)) {
                    
                    if (!array_key_exists($k, $rdata)) {
                        $rdata[$k] = [$v['no_usuario'], $v['receita']];
                    } else {
                        array_push($rdata[$k], $v['receita']);
                    }
                } elseif ($k == 'avg') {
                    if (!array_key_exists($k, $rdata)) {
                        $rdata[$k] = ['avg', $v];
                    } else {
                        array_push($rdata[$k], $v);
                    }
                } elseif ($k == 'mes') {
                    if (!array_key_exists($k, $rdata)) {
                        $rdata[$k] = [$v];
                    } else {
                        array_push($rdata[$k], $v);
                    }
                }
            }
        }

    	return response()->json($rdata, 200);
    }

    private function formatNumber($number)
    {
    	if ($number < 0) {
			$number = '- R$ '.number_format(abs($number), 2, ',', '.');
		} else {
			$number = 'R$ '.number_format($number, 2, ',', '.');
		}

		return $number;
    }
}
