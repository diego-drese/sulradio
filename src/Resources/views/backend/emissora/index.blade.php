@extends('Admin::layouts.backend.main')
@section('title', 'Emissoras')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center m-b-10">
                        <h4 class="card-title">&nbsp;</h4>
                        <div class="ml-auto">
                            <div class="btn-group">
                                @if($hasAdd)
                                    <a href="{{route('emissora.create')}}" class="btn btn-primary">
                                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_emissoras" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 120px">Ações</th>
                                <th>Razão Social</th>
                                <th>Serviço</th>
                                <th>Localidade</th>
                                <th>Cliente SEAD</th>
                            </tr>
                            <tr>
                                <th style="width: 60px">
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar Razão social"></th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary"
                                           id="data_protocolo" placeholder="Buscar serviço"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Localidade"></th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Anexo"></th>

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
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        var hasEditClient = '{{$hasEditClient}}';
        $(document).ready(function () {
            var table_emissoras = $('#table_emissoras').DataTable({
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
                                $('#table_emissoras thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('emissora.index') }}',
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

                            @if($hasEdit)
                                edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            @endif
                            @if($hasAtosOficiais)
                                edit_button += '<a href="' + data.atos_oficiais + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Atos Oficiais</b></a>';
                            @endif
                            @if($hasProcessos)
                                edit_button += '<a href="' + data.processos + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Processos</b></a>';
                            @endif
                            @if($hasSocios)
                                edit_button += '<a href="' + data.socios + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Societários</b></a>';
                            @endif
{{--                        @if($hasAtosJunta)--}}
{{--                            edit_button += '<a href="' + data.atos_comerciais + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Atos junta comercial</b></a>';--}}
{{--                        @endif--}}
                            @if($hasContato)
                                edit_button += '<a href="' + data.contatos + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Contatos</b></a>';
                            @endif
                            @if($hasEndereco)
                                edit_button += '<a href="' + data.endereco + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Endereços</b></a>';
                            @endif
                            @if($hasDocument)
                                edit_button += '<a href="' + data.documents + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Documentos Cliente</b></a>';
                            @endif
                            @if($hasDocumentLegal)
                                edit_button += '<a href="' + data.documents_legal + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Documentos Jurídico</b></a>';
                            @endif
                            @if($hasDocumentEngineering)
                                edit_button += '<a href="' + data.documents_engineering + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Documentos Engenharia</b></a>';
                            @endif
                                return edit_button
                        }
                    },
                    {data: "razao_social", 'name': 'emissora.razao_social'},
                    {data: "desc_servico", 'name': 'servico.desc_servico'},
                    {
                        data: "desc_municipio",
                        'name': 'municipio.desc_municipio',
                        render: function (data, display, row) {
                            if (!row.desc_municipio) {
                                return '---'
                            }
                            return row.desc_municipio + ' (' + row.desc_uf + ')';
                        }
                    },
                    {
                        data: "company_name",
                        'name': 'client.company_name',
                        render: function (data, display, row) {
                            if (data) {
                                if(hasEditClient){
                                    return '<a href="'+row['client']+'" target="_blank" class="badge badge-success mr-1">'+data+'</a>';
                                }else{
                                    return '<span class="badge badge-success mr-1">'+data+'</span>';
                                }

                            } else {
                                return '<span class="badge badge-danger mr-1" title="Esta emissora nao possui cliente.">---</span>';
                            }
                        }
                    },

                ]
            });

            $('#table_emissoras thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (table_emissoras.column(i).search() !== this.value) {
                        table_emissoras.column(i).search(this.value, true).draw();
                    }
                });
            });

            $('#clearFilter').click(function () {
                table_emissoras.state.clear();
                window.location.reload();
            })
        });
    </script>

@endsection