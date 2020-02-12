@extends('layouts.app')

@section('title', 'CAOL - Controle de Atividades Online - Agence Interativa')

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
					<option value="1">Jan</option>
					<option value="2">Fev</option>
					<option value="3">Mar</option>
					<option value="4">Abr</option>
					<option value="5">Mai</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Ago</option>
					<option value="9">Set</option>
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
					<option value="1">Jan</option>
					<option value="2">Fev</option>
					<option value="3">Mar</option>
					<option value="4">Abr</option>
					<option value="5">Mai</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Ago</option>
					<option value="9">Set</option>
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
			<button id="relatorio" type="button" class="btn btn-secondary mr-1">Relatório</button>
			<button id="grafico" type="button" class="btn btn-secondary mr-1">Gráfico</button>
			<button id="pizza" type="button" class="btn btn-secondary">Pizza</button>	
		</div>
	</div>

	<div class="row my-1">
		<div class="col-md-12">
			<div class="tab-content" id="comercialContent">
				<div class="tab-pane fade show active" id="consultorContent" role="tabpanel" aria-labelledby="consultor-tab">
					<h2>Consultores</h2>
				</div>
				<div class="tab-pane fade" id="clienteContent" role="tabpanel" aria-labelledby="cliente-tab">
					<h2>Clientes</h2>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$('#comercial').addClass('active');
	</script>
@endsection