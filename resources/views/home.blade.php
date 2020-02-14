@extends('layouts.app')

@section('title', 'CAOL - Controle de Atividades Online - Agence Interativa')

@section('css')
	<link rel="stylesheet" href="{{ asset('plugins/c3/c3.min.css') }}">
	<style type="text/css">
		.overflow-table {
			max-height: 70vh;
			overflow-y: scroll;
		}
	</style>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs" id="comercialTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="consultorTab" data-toggle="tab" href="#consultorContent" role="tab" aria-controls="consultorContent" aria-selected="true">
						Por Consultor
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="clienteTab" data-toggle="tab" href="#clienteContent" role="tab" aria-controls="clienteContent" aria-selected="false">
						Por Cliente
					</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="row my-1">
		<div class="col-md-8">
			<form id="period" class="form-inline">
				<label>Período</label>
				
				<select name="start_month" class="ml-1">
					<option value="01">Jan</option>
					<option value="02">Fev</option>
					<option value="03">Mar</option>
					<option value="04">Abr</option>
					<option value="05">Mai</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Ago</option>
					<option value="09">Set</option>
					<option value="10">Out</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>

				<select name="start_year" class="ml-1 mr-2">
					<option value="2003">2003</option>
					<option value="2004">2004</option>
					<option value="2005">2005</option>
					<option value="2006">2006</option>
					<option value="2007">2007</option>
				</select>

				<label> a </label>
				
				<select name="end_month" class="ml-1">
					<option value="01">Jan</option>
					<option value="02">Fev</option>
					<option value="03">Mar</option>
					<option value="04">Abr</option>
					<option value="05">Mai</option>
					<option value="06">Jun</option>
					<option value="07">Jul</option>
					<option value="08">Ago</option>
					<option value="09">Set</option>
					<option value="10">Out</option>
					<option value="11">Nov</option>
					<option value="12">Dec</option>
				</select>

				<select name="end_year" class="ml-1">
					<option value="2003">2003</option>
					<option value="2004">2004</option>
					<option value="2005">2005</option>
					<option value="2006">2006</option>
					<option value="2007">2007</option>
				</select>
			</form>
		</div>

		<div class="col-md-4 d-flex justify-content-end">
			<button id="relatorio" type="button" class="btn btn-secondary mr-1">
				<i class="fa fa-file-alt"></i>
				Relatório
			</button>
			<button id="grafico" type="button" class="btn btn-secondary mr-1">
				<i class="fa fa-chart-bar"></i>
				Gráfico
			</button>
			<button id="pizza" type="button" class="btn btn-secondary">
				<i class="fa fa-chart-pie"></i>
				Pizza
			</button>	
		</div>
	</div>

	<div class="row my-1">
		<div class="col-md-12">
			<div class="tab-content" id="comercialContent">
				<div class="tab-pane fade show active" id="consultorContent" role="tabpanel" aria-labelledby="consultor-tab">
					<h3>Consultores</h3>
					
					<div class="row">
						<div class="col-md-5 overflow-table">
							<table id="consultores-table" class="my-3 table table-hover" width="100">
								<thead>
									<tr>
										<th width="10">
											<i class="fa fa-check"></i>
										</th>
										<th>Nome Completo</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="2" class="text-center">Não há dados para mostrar</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="consultores-info" class="col-md-7 overflow-table">
							<canvas id="chart-area"></canvas>
						</div>
					</div>
				</div>

				<div class="tab-pane fade" id="clienteContent" role="tabpanel" aria-labelledby="cliente-tab">
					<h3>Clientes</h3>

					<div class="row">
						<div class="col-md-5 overflow-table">
							<table id="clientes-table" class="my-3 table table-hover" width="100">
								<thead>
									<tr>
										<th width="10">
											<i class="fa fa-check"></i>
										</th>
										<th>Nome Completo</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="2" class="text-center">Não há dados para mostrar</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
	<script src="{{ asset('plugins/c3/d3.min.js') }}"></script>
	<script src="{{ asset('plugins/c3/c3.min.js') }}"></script>
	<script type="text/javascript">
		$('#comercial').addClass('active');

		$(document).ready(function() {
			{{-- Cuando el documento cargue ejecuto la funcion que carga datos en la tabla de consultores --}}
			reloadConsultoresTable();

			$('#relatorio').on('click', function(e) {
				e.preventDefault();

				if($('#consultorTab').hasClass('active')) {
					let dataForm = {};

					$('#period select').each(function(i, el) {
						dataForm[el.name] = el.value;
					});

					let dataTable = $('[name="consultoresCo[]"]:checked').map(function(){
								    	return this.value;
								    }).get();

					dataForm['co_usuarios'] = dataTable;

					calculateRelatorio(dataForm, 'consultores');
				} else if($('#clienteTab').hasClass('active')) {
					console.log('debo ejecutar la peticion en relacion a los clientes');
				}
			});

			$('#pizza').on('click', function(e) {
				e.preventDefault();
				let dataForm = {};

				if($('#consultorTab').hasClass('active')) {

					$('#period select').each(function(i, el) {
						dataForm[el.name] = el.value;
					});

					let dataTable = $('[name="consultoresCo[]"]:checked').map(function(){
								    	return this.value;
								    }).get();

					dataForm['co_usuarios'] = dataTable;
				} else if($('#clienteTab').hasClass('active')) {
					console.log('debo ejecutar la peticion en relacion a los clientes');
				}

				drawPizza(dataForm, 'consultores');
			});

			$('#grafico').on('click', function(e) {
				e.preventDefault();
				let dataForm = {};

				if($('#consultorTab').hasClass('active')) {

					$('#period select').each(function(i, el) {
						dataForm[el.name] = el.value;
					});

					let dataTable = $('[name="consultoresCo[]"]:checked').map(function(){
								    	return this.value;
								    }).get();

					dataForm['co_usuarios'] = dataTable;
				} else if($('#clienteTab').hasClass('active')) {
					console.log('debo ejecutar la peticion en relacion a los clientes');
				}

				drawGrafico(dataForm, 'consultores');
			});
		});

		{{-- Esta funcion se encarga de vacias los datos en la tabla de consultores --}}
		function reloadConsultoresTable() {
			$('#consultores-table tbody').html(`<tr>
				<td colspan="2" class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando dados</td>
			</tr>`);

			$.ajax({
				headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				url: "{{ route('caoUsuario.list') }}",
				type: 'GET'
			}).done(function(resp) {
				let html = '';

				resp.users.forEach(function(e, i) {
					html += `<tr>
						<td>
							<input type="checkbox" name="consultoresCo[]" value="${e.co_usuario}" multiple>
						</td>
						<td>${e.no_usuario}</td>
					</tr>`;
				});

				$('#consultores-table tbody').html(html);
			}).fail(function(resp) {
				$('#consultores-table tbody').html(`<tr><td colspan="2" class="text-center">Não há dados para mostrar</td></tr>`);
			});
		}

		{{-- Esta funcion ejecuta la peticion para el calculo del relatorio --}}
		function calculateRelatorio(data, type = 'consultores') {
			$.ajax({
				headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				url: "{{ route('caoFactura.relatorio') }}",
				type: 'POST',
				data: data
			}).done(function(resp) {
				drawRelatorio(resp, type);
			}).fail(function(resp) {
				console.log(resp);
			});
		}

		{{-- Esta funcion dibuja la tabla del relatorio dependiendo del tipo de usuario (clientes o consultores) --}}
		function drawRelatorio(data, type) {
			if (type == 'consultores') {
				let html = ``;
				
				for (var i in data) {
					html += `<table class="table table-hover" width="100">
						<thead>
							<tr><th colspan="5">${data[i].no_usuario}</th></tr>
							<tr>
								<th class="text-center">Período</th>
								<th class="text-center">Receita Líquida</th>
								<th class="text-center">Custo Fixo</th>
								<th class="text-center">Comissão</th>
								<th class="text-center">Lucro</th>
							</tr>
						</thead>
						<tbody>`;

					for (var x in data[i]) {
						if (data[i][x] instanceof Object) {
							html += `<tr>
								<td>${data[i][x].mes}</td>
								<td class="text-left">${data[i][x].receita}</td>
								<td class="text-left">${data[i][x].fixo}</td>
								<td class="text-left">${data[i][x].comissao}</td>
								<td class="text-left">${data[i][x].lucro}</td>
							</tr>`;
						}
					}

					html +=`</tbody>
						<tfoot>
							<tr>
								<td><strong>SALDO</strong></td>
								<td class="text-left">${data[i].total_receita}</td>
								<td class="text-left">${data[i].total_fixo}</td>
								<td class="text-left">${data[i].total_comissao}</td>
								<td class="text-left">${data[i].total_lucro}</td>
							</tr>
						</tfoot>
					</table>`;
				}

				$('#consultores-info').html(html);
			} else if (type == 'clientes') {

			}
		}

		{{-- Esta funcion dibuja el grafico de torta --}}
		function drawPizza(data, $type = 'consultores') {
			$.ajax({headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				url: "{{ route('caoFactura.chart-pie') }}",
				type: 'POST',
				data: data
			}).done(function(resp) {
				$('#consultores-info').html(`<div id="chart-area"></div>`);
				var data = {
					columns: [],
					type: 'donut',
					tooltip: {
						show: true
					}
				};

				var color = {
					pattern: [],
				}

				for (var i in resp) {
					data.columns.push([resp[i].label, resp[i].value]);
					color.pattern.push(resp[i].color);
				}

				var chart = c3.generate({
					bindto: '#chart-area',
					data: data,
					donut: {
						label: {
					   		show: true
						},
						width: 150,
					},
					color: color
				});
			}).fail(function(resp){
				console.log(resp);
			});
		}

		{{-- Esta funcion dibuja el grafico de barras --}}
		function drawGrafico(data, $type = 'consultores') {
			$.ajax({headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
				url: "{{ route('caoFactura.chart-bar') }}",
				type: 'POST',
				data: data
			}).done(function(resp) {
				$('#consultores-info').html(`<div id="chart-area"></div>`);

				var data = {
					columns: [],
					type: 'bar',
					tooltip: {
						show: true
					},
					types: {
						avg: 'line'
					}
				};

				for (var i in resp) {
					if (i != 'mes') {
						data.columns.push(resp[i]);
					}
				}

				console.log(data);

				var chart = c3.generate({
					bindto: '#chart-area',
					data: data
				});
			}).fail(function(resp){
				console.log(resp);
			});
		}
	</script>
@endsection