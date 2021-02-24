@extends('Admin::layouts.backend.main')
@section('title', 'Atos junta comercial')
@section('content')
    <div class="row">
       @include('SulRadio::backend.emissora_header.header')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  align-items-center m-b-10">
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
                                    <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                                </a>
                                @if($hasAdd)
                                    <a href="{{route('emissora.atos.comercial.create',$emissora->emissoraID)}}"
                                       class="btn btn-primary">
                                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableProcessos" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 80px">Ações</th>
                                <th>Tipo</th>
                                <th>Data</th>

                            </tr>
                            <tr>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Id"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Tipo"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Buscar por ano"></th>
                                <th>
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
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
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {

            var tableProcessos = $('#tableProcessos').DataTable({
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
                stateSave: true,
                stateLoaded: function (settings, data) {
                    setTimeout(function () {
                        var dataExtra = settings.ajax.data({});
                        var searchCols = settings.aoPreSearchCols;
                        if (searchCols && searchCols.length) {
                            for (var i = 0; i < searchCols.length; i++) {
                                $('#tableProcessos thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('emissora.atos.comercial.index', $emissoraID) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            if (!hasEdit) return '---';
                            var edit_button = "";
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            return edit_button
                        }
                    },
                    {data: "ato_jcID", 'name': 'ato_jcID'},
                    {data: "desc_tipo_ato_juridico", 'name': 'emissora_tipo_ato_juridico.desc_tipo_ato_juridico'},
                    {data: "data_arquivo_junta", 'name': 'data_arquivo_junta'},

                ]
            });

            $('#tableProcessos thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (tableProcessos.column(i).search() !== this.value) {
                        tableProcessos.column(i).search(this.value).draw();
                    }
                });
            });

            $('#clearFilter').click(function () {
                tableProcessos.state.clear();
                window.location.reload();
            })

        });
    </script>

@endsection