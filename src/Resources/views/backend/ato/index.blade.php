@extends('Admin::layouts.backend.main')
@section('title', 'Atos oficiais')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  align-items-center m-b-10">
                        <div class="ml-auto">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_emissora" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead class="">
                            <tr>
                                <th style="width: 80px">Ações</th>
                                <th role="row">Emissora</th>
                                <th>Serviço</th>
                                <th>Tipo do Ato</th>
                                <th>Categoria</th>
                                <th>Número do ato</th>
                                <th>Data do Ato</th>
                                <th>Data DOU</th>
                                <th>Estado</th>
                            </tr>
                            <tr>
                                <th>
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Emissora"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Serviço"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Tipo"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Categoria"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Numero"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Buscar por ano"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Buscar por ano"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Estado"></th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {
            var table_emissora = $('#table_emissora').DataTable({
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
                        var searchCols = settings.aoPreSearchCols;
                        if (searchCols && searchCols.length) {
                            for (var i = 0; i < searchCols.length; i++) {
                                $('#table_emissora thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('atos.index') }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a href="' + data.edit_url + '" target="_blank" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            return edit_button
                        }
                    },
                    {data: "razao_social", 'name': 'emissora.razao_social'},
                    {data: "desc_servico", 'name': 'servico.desc_servico'},
                    {data: "desc_tipo_ato", 'name': 'tipo_ato.desc_tipo_ato'},
                    {data: "desc_categoria", 'name': 'ato_categoria.desc_categoria'},
                    {data: "numero_ato", 'name': 'ato.numero_ato'},
                    {data: "data_ato", 'name': 'ato.data_ato'},
                    {data: "data_dou", 'name': 'ato.data_dou'},
                    {data: "uf_extenso", 'name': 'uf.uf_extenso'},

                ]
            });

            $('#table_emissora thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (table_emissora.column(i).search() !== this.value) {
                        table_emissora.column(i).search(this.value).draw();
                    }
                });
            });

            $('#clearFilter').click(function () {
                table_emissora.state.clear();
                window.location.reload();
            })
        });
    </script>

@endsection