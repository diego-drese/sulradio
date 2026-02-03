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
                                    <th>Prazo Vencimento</th>
                                    <th>Prazo&nbsp;Protocolo</th>
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
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" type="text/css" />

    <style>
        .table td, .table th {
            padding: 0.5em;
        }
        #table_ticket_filter{
            display: none;
        }
        .dt-button {
            all: unset;
        }

        /* Reaplica Bootstrap */
        .dt-button.btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            line-height: 1.5;
            border-radius: 0.25rem;
            cursor: pointer;
            color: #fff;
            background-image: none;
            font-size: 12px;
        }
        button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled) {
            border: 1px solid #666;
            background-color: initial;
            background-image: initial;
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='#f9f9f9', EndColorStr='#e0e0e0');
            text-decoration: underline;
        }
        .dt-buttons {
            display: flex;
            gap: 6px;
        }

        .dataTables_length,
        .dataTables_paginate {
            margin: 0;
        }

        .dataTables_length select {
            height: 32px;
        }

    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>

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
                layout: {
                    topStart: {
                        buttons: [ 'excel']
                    }
                },
                serverSide: true,
                processing: true,
                autoWidth: false,
                orderCellsTop: true,
                stateSave: true,
                searching: true,
                pageLength: 10, // valor inicial
                lengthMenu: [
                    [10, 25, 50, 100, 200, 400, 800],
                    [10, 25, 50, 100, 200, 400, 800]
                ],
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
                "order": [[ 5, "desc" ]],
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
                    {data: "renewal_alert", 'name': 'ticket.renewal_alert', render:function (data, display, row){
                            if(!data){
                                return '---';
                            }
                            var now = moment();
                            var endForest = moment(data, "DD/MM/YYYY");
                            var days = endForest.diff(now, 'days');
                            if(days<180){
                                return '<b class="text-danger">'+data+'</b>'
                            }else if(days<365){
                                return '<b class="text-warning">'+data+'</b>'
                            }else{
                                return '<b class="text-success">'+data+'</b>'
                            }
                        }},
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
                ],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<span class="fa fa-file-excel"></span> <b>Excel</b>',
                        className: 'btn btn-primary form-control-sm',
                        title: 'Tickets',
                        exportOptions: {
                            columns: ':not(:first-child)' // ignora coluna Ações
                        }
                    }
                ],
                dom: `
    <'row mb-2'
        <'col-md-12 d-flex justify-content-end align-items-center gap-2'Bl>
    >
    <'table-scrollable't>
    <'row'
        <'col-md-6'i>
        <'col-md-6 d-flex justify-content-end'p>
    >
`,




            });
            table_ticket.buttons('dt-buttons').container().addClass('btn-group-sm');

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