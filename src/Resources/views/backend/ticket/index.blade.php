@extends('SulRadio::backend.layout.main')
@section('title', 'Tickets')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group bt-switch mr-2" title="Ativos">
                        <input id="active" name="active" type="checkbox" title="Ativos"
                               value="1" data-on-color="success" data-off-color="danger"
                               data-on-text='<i class="fas fa-check-circle"></i>'
                               data-off-text='<i class="fas fa-times-circle"></i>'>
                    </div>
                    <div class="btn-group ">
                        @if($hasAdd)
                            <a href="{{route('ticket.create')}}" class="btn btn-primary">
                                <span class="fa fa-plus"></span> <b>Adicionar</b>
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive mt-2" >
                        <table id="table_ticket" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 75px">Ações</th>
                                <th>Assunto</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Emissora</th>
                                <th>Atualizado</th>
                                <th>Término</th>
                                <th>Responsáveis</th>
                                <th>Solicitante</th>
                            </tr>
                            <tr>
                                <th style="width: 60px">
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Assunto">
                                </th>

                                <th role="row">
                                    <select class="form-control fieldSearch">
                                        <option value="">Todos</option>
                                        @foreach($category as $value)
                                            <option value="{{$value->id}}" style="color: {{$value->color}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th role="row">
                                    <select class="form-control fieldSearch">
                                        <option value="">Todos</option>
                                        @foreach($status as $value)
                                            <option value="{{$value->id}}" style="color: {{$value->color}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar nome">
                                </th>
                                <th role="row">
                                    ---
                                </th>
                                <th role="row">
                                    ---
                                </th>
                                <th role="row">
                                    <select class="form-control fieldSearch">
                                        <option value="">Todos</option>
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th role="row">
                                    <select class="form-control fieldSearch">
                                        <option value="">Todos</option>
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/bootstrap-switch.css')}}">
    <style>
        .table td, .table th {
            padding: 0.5em;
        }
        #table_ticket_filter{
            display: none;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/bootstrap-switch.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {
            if(localStorage.getItem('active')==1){
                $('#active').attr('checked', true);
            }

            $(".bt-switch input[type='checkbox']").bootstrapSwitch();
            $('#active').on('switchChange.bootstrapSwitch', function (event, state) {
                if($("#active").is(':checked')) {
                    localStorage.setItem('active', 1);
                } else {
                    localStorage.setItem('active', 0);
                }
                table_ticket.draw();
            });

            var table_ticket = $('#table_ticket').DataTable({
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Busca",
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
                stateSave: true,
                searching: true,
                stateLoaded: function (settings, data) {
                    setTimeout(function () {
                        var dataExtra = settings.ajax.data({});
                        var searchCols = settings.aoPreSearchCols;
                        if (searchCols && searchCols.length) {
                            for (var i = 0; i < searchCols.length; i++) {
                                if(searchCols[i]['bRegex']){
                                    var id = searchCols[i]['sSearch'].replace('^', "").replace('$','')
                                    $('#table_ticket thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(id);
                                }else{
                                    $('#table_ticket thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                                }

                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('ticket.index') }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        d.active =  0;
                        if($('#active:checkbox:checked').length > 0){
                            d.active =  1;
                        }
                        return d;
                    }
                },
                "order": [[ 6, "desc" ]],
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a href="' + data.ticket_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Visualizar</b></a>';
{{--                            @if($hasEdit)--}}
{{--                                edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';--}}
{{--                            @endif--}}
                                return edit_button
                        }
                    },
                    {data: "subject", 'name': 'subject'},
                    // {data: "priority_name", 'name': 'ticket_priority.id', render:function (data, display, row){
                    //     return '<b style="color:'+row.priority_color+'">'+data+'</b>'
                    // }},
                    {data: "category_name", 'name': 'ticket_category.id', render:function (data, display, row){
                            return '<b style="color:'+row.category_color+'">'+data+'</b>'
                    }},
                    {data: "status_name", 'name': 'ticket_status.id', render:function (data, display, row){
                            return '<b style="color:'+row.status_color+'">'+data+'</b>'
                    }},
                    {data: "emissora_nome", 'name': 'emissora.razao_social'},
                    {data: "updated_at", 'name': 'ticket.updated_at'},
                    {data: "end_forecast", 'name': 'ticket.end_forecast', render:function (data, display, row){
                            if(!data){
                                return '---';
                            }
                            var now = moment();
                            var endForest = moment(data, "DD/MM/YYYY");
                            var days = endForest.diff(now, 'days');
                            if(days<0){
                                return '<b class="text-danger">'+data+'</b>'
                            }else if(days<3){
                                return '<b class="text-warning">'+data+'</b>'
                            }else{
                                return '<b class="text-success">'+data+'</b>'
                            }
                    }},
                    {data: "participants", 'name': 'ticket_participant.user_id'},
                    {data: "user_name", 'name': 'ticket.owner_id'},
                ]
            });

            $('#table_ticket thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (table_ticket.column(i).search() !== this.value) {
                        if(Number(this.value)){
                            table_ticket.column(i).search('^'+this.value+'$', true, false).draw();
                        }else{
                            table_ticket.column(i).search(this.value, true).draw();
                        }
                    }
                });
            });

            $('#clearFilter').click(function () {
                table_ticket.state.clear();
                window.location.reload();
            });

            $(".select2").select2({
                width: '100%',
                placeholder: 'Selecione',
                allowClear: true
            });
        });
    </script>

@endsection