@extends('Admin::layouts.backend.main')
@section('title', 'Atos oficiais')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  align-items-center m-b-10">
                        <h4 class="card-title">
                            {{$emissora->razao_social}}<br/>
                            @if($emissora->desc_status_sead=="ATIVO")
                                <span class="font-10 badge badge-success">ATIVO</span>
                            @elseif($emissora->desc_status_sead=="INATIVO")
                                <span class="font-10 badge badge-danger">INATIVO</span>
                            @elseif($emissora->desc_status_sead=="CONCORRENCIA")
                                <span class="font-10 badge badge-info">CONCORRENCIA</span>
                            @else
                                ---
                            @endif <br/>
                            <span class="font-10">{{$emissora->desc_municipio}} ({{$emissora->desc_uf}})</span>
                        </h4>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
                                    <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                                </a>
                                @if($hasAdd)
                                    <a href="{{route('emissora.atos.oficiais.create',$emissora->emissoraID)}}"
                                       class="btn btn-primary">
                                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_emissora" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead class="">
                            <tr>
                                <th role="row">#</th>
                                <th>Tipo do Ato</th>
                                <th>Número do ato</th>
                                <th>Data do Ato</th>
                                <th>Data DOU</th>
                                <th style="width: 80px">Ações</th>
                            </tr>
                            <tr>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Bucar Id"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Bucar Tipo"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Bucar Numero"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Bucar por ano"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Bucar por ano"></th>
                                <th>
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
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
                    url: '{{ route('emissora.atos.oficiais.index', $emissoraID) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {data: "atoID", 'name': 'atoID'},
                    {data: "desc_tipo_ato", 'name': 'tipo_ato.desc_tipo_ato'},
                    {data: "numero_ato", 'name': 'ato.numero_ato'},
                    {data: "data_ato", 'name': 'ato.data_ato'},
                    {data: "data_dou", 'name': 'ato.data_dou'},
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            return edit_button
                        }
                    }
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