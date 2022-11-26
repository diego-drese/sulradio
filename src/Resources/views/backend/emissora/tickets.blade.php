@extends('SulRadio::backend.layout.main')
@section('title', 'Documentos')
@section('content')
    <div class="row">
        @include('SulRadio::backend.emissora_header.header')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a style="float: right;" href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
                        <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                    </a>
                    <div class="table-responsive mt-2" >
                        <table id="table_ticket" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 75px">Ações</th>
                                <th>Assunto</th>
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
    <style>
        .table td, .table th {
            padding: 0.5em;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        $(document).ready(function () {
            var emissoraId='{{$emissoraID}}';
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
                searching: false,
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
                    url: '{{ route('emissora.tickets',[$emissoraID]) }}',
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
                "order": [[ 1, "desc" ]],
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a href="' + data.ticket_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Visualizar</b></a>';
                            return edit_button
                        }
                    },
                    {data: "subject", 'name': 'subject'},

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


        });
    </script>

@endsection