@extends('Admin::layouts.backend.main')
@section('title', 'Movimentações')
@section('content')
	<div class="row">
		<div class="col-12">
			@if($hasAdmin)
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-3 form-group  ">
								<label for="user">Usuário</label>
								<div class="input-group mb-3">
									<select id="user" class="form-control filter_table" name="user">
										<option value=""> Todos </option>
										@foreach($users as $value)
											<option {{$value->id==$user->id ? 'selected' : ''}} value="{{$value->id}}">{{$value->name.' '.$value->lastname }}</option>
										@endforeach
									</select>

								</div>
							</div>
							<div class="col-md-3 form-group  ">
								<label for="status">Status</label>
								<div class="input-group mb-3">
									<select name="status" id="status" class="form-control filter_table" >
										<option value=""> Todos </option>
										@foreach($status as $key=>$value)
											<option value="{{$key}}">{{$value}}</option>
										@endforeach
									</select>

								</div>
							</div>
							<div class="col-md-3 form-group">
								<label for="type">Tipo</label>
								<div class="input-group mb-3">
									<select name="type" id="type" class="form-control filter_table" >
										<option value=""> Todos </option>
										@foreach($types as $key=>$value)
											<option value="{{$key}}">{{$value}}</option>
										@endforeach
									</select>

								</div>
							</div>

							<div class="col-md-3 form-group ">
								<label for="zone">Área</label>
								<div class="input-group mb-3">
									<select name="zone" id="zone" class="form-control filter_table" >
										<option value=""> Todos </option>
										@foreach($zones as $key=>$value)
											<option value="{{$key}}">{{$value}}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-6 form-group ">
								<label for="period">Periodo</label>
								<input type="text" name="period" id="period" class="form-control shawCalRanges" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
			@endif
			<div class="card card-body">
				<div class="table-responsive">
					<table id="logs" class="table table-striped table-bordered" role="grid"
					       aria-describedby="file_export_info">
						<thead>
						<tr>
							<th>Assunto</th>
							<th>Content</th>
							<th>Criado</th>
							<th>Atualizado</th>
							@if($hasAdmin)
								<th>Usuário</th>
								<th>Status</th>
								<th>Tipo</th>
								<th>Área</th>
							@endif
						</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('style_head')
	<link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
	<link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/daterangepicker.css')}}">
@endsection
@section('script_footer_end')
	<script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
	<script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>
	<script>
		$(document).ready(function () {
			var daterangepicker = $('#period').daterangepicker({
				startDate: moment().subtract(30, 'days'),
				autoUpdateInput:true,
				endDate:  moment(),
				valueDefault: null,
				locale: {
					"format": "dd/mm/yy"
				},
				alwaysShowCalendars: true,
				ranges: {
					'Últimos 03 Dias': [moment().subtract(3, 'days'), moment()],
					'Últimos 05 Dias': [moment().subtract(5, 'days'), moment()],
					'Últimos 07 Dias': [moment().subtract(7, 'days'), moment()],
					'Últimos 15 Dias': [moment().subtract(15, 'days'), moment()],
					'Últimos 30 Dias': [moment().subtract(30, 'days'), moment()],
					'Últimos 60 Dias': [moment().subtract(60, 'days'), moment()],
					'Últimos 90 Dias': [moment().subtract(90, 'days'), moment()],
					'Últimos 180 Dias': [moment().subtract(90, 'days'), moment()],
				},
				locale:{
					dateFormat: 'dd/mm/yy',
					dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
					dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
					dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
					monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
					monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
					nextText: 'Proximo',
					prevText: 'Anterior',
					applyLabel: 'Aplicar',
					cancelLabel: 'Cancelar',
					weekLabel: 'Sem',
					customRangeLabel: 'Período de',
				}
			}, function(start, end) {
				$('#period').val(start.format('L')+' - '+ end.format('L'))

			});

			var table = $('#logs').DataTable({
				language: {
					"sEmptyTable": "Nenhum registro encontrado",
					"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
					"sInfoFiltered": "(Filtrados de _MAX_ registros)",
					"sInfoPostFix": "",
					"sInfoThousands": ".",
					"sLengthMenu": "_MENU_ resultados por página",
					"sLoadingRecords": "Carregando...",
					"sProcessing": "Processando...",
					"sZeroRecords": "Nenhum registro encontrado",
					"sSearch": "Pesquisar",
					"oPaginate": {
						"sNext": "Próximo",
						"sPrevious": "Anterior",
						"sFirst": "Primeiro",
						"sLast": "Último"
					},
					"oAria": {
						"sSortAscending": ": Ordenar colunas de forma ascendente",
						"sSortDescending": ": Ordenar colunas de forma descendente"
					}
				},
				serverSide: true,
				processing: true,
				autoWidth: false,
				orderCellsTop: true,
				searching: false,
				ajax: {
					url: '{{ route('notifications.ticket') }}',
					type: 'GET',
					data: function (d) {
						d._token = $("input[name='_token']").val();
						if($('#user').val()){
							d.user_id = $('#user').val();
						}if($('#status').val()){
							d.status = $('#status').val();
						}if($('#type').val()){
							d.type = $('#type').val();
						}if($('#zone').val()){
							d.zone = $('#zone').val();
						}if($('#period').val()){
							d.period = $('#period').val();
						}
						return d;
					}
				},
				columns: [
					{
						data: "subject", 'name': 'subject', render: function (data, display, row) {
							return data;
						}
					}, {
						data: "content", 'name': 'content', render: function (data, display, row) {
							return data;
						}
					}, {
						data: "created", 'name': 'created', render: function (data, display, row) {
							return data;
						}
					}, {
						data: "updated", 'name': 'updated', render: function (data, display, row) {

							return data;
						}
					}
					@if($hasAdmin)
					,{
						data: "updated", 'name': 'updated', render: function (data, display, row) {

							return data;
						}
					}, {
						data: "status_name", 'name': 'status_name', render: function (data, display, row) {

							return data;
						}
					}, {
						data: "type_name", 'name': 'type_name', render: function (data, display, row) {

							return data;
						}
					}, {
						data: "zone_name", 'name': 'zone_name', render: function (data, display, row) {
							return data;
						}
					}
					@endif
				]
			});

			$('.filter_table, #period').change(function (){
				table.draw();
			})
		});
	</script>

@endsection