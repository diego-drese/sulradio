@extends('Admin::layouts.backend.main')
@section('title', 'Estações')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="callout callout-success">
                            <h5>Empresa: <b>{{$client->company_name}}</b></h5>
                            <h6>Nome: <b>{{$client->name}}</b></h6>
                            <h6>{{$client->document_type}}: <b>{{$client->document}}</b></h6>
                        </div>
                    </div>
                    <div class="card-title  text-right">
                        <div class="btn-group">
                            <a href="{{route('client.index', [$client->_id])}}" class="btn btn-primary m-r-5">
                                <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_user" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 90px">Ações</th>
                                <th>Fistel</th>
                                <th style="width: 50px">Uf</th>
                                <th>Município</th>
                                <th>Serviço</th>
                                <th>Canal</th>
                                <th>Frequência</th>
                                <th>Finalidade</th>
                                <th>Classe</th>
                                <th>Status</th>
                                <th>Entidade</th>
                                <th title="Ultima data de atualização">Data</th>
                            </tr>
                            <tr>
                                <th style="width: 60px">
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary ">
                                        <option value="">Todos</option>
                                        <option value="AC">AC</option>
                                        <option value="AL">AL</option>
                                        <option value="AM">AM</option>
                                        <option value="AP">AP</option>
                                        <option value="BA">BA</option>
                                        <option value="CE">CE</option>
                                        <option value="DF">DF</option>
                                        <option value="ES">ES</option>
                                        <option value="GO">GO</option>
                                        <option value="MA">MA</option>
                                        <option value="MG">MG</option>
                                        <option value="MS">MS</option>
                                        <option value="MT">MT</option>
                                        <option value="PA">PA</option>
                                        <option value="PB">PB</option>
                                        <option value="PE">PE</option>
                                        <option value="PI">PI</option>
                                        <option value="PR">PR</option>
                                        <option value="RJ">RJ</option>
                                        <option value="RN">RN</option>
                                        <option value="RO">RO</option>
                                        <option value="RR">RR</option>
                                        <option value="RS">RS</option>
                                        <option value="SC">SC</option>
                                        <option value="SE">SE</option>
                                        <option value="SP">SP</option>
                                        <option value="TO">TO</option>
                                    </select>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary ">
                                        <option value="">Todos</option>
                                        <option value="FM">FM</option>
                                        <option value="GTVD">GTVD</option>
                                        <option value="OM">OM</option>
                                        <option value="PBTVD">PBTVD</option>
                                        <option value="RTV">RTV</option>
                                        <option value="RTVD">RTVD</option>
                                        <option value="TV">TV</option>
                                        <option value="TVA">TVA</option>
                                    </select>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary ">
                                        <option value="">(Todos)</option>
                                        <option value="Comercial">Comercial</option>
                                        <option value="Educativo">Educativo</option>
                                        <option value="Educativo publico">Educativo publico</option>
                                        <option value="Publica">Publica</option>
                                    </select>
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary ">
                                        <option value="">Todos</option>
                                        <option value="A">A</option>
                                        <option value="A1">A1</option>
                                        <option value="A2">A2</option>
                                        <option value="A3">A3</option>
                                        <option value="A4">A4</option>
                                        <option value="B">B</option>
                                        <option value="B1">B1</option>
                                        <option value="B2">B2</option>
                                        <option value="C">C</option>
                                        <option value="E">E</option>
                                        <option value="E2">E2</option>
                                        <option value="E3">E3</option>
                                    </select>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"
                                           placeholder="Buscar">
                                </th>
                                <th role="row">
                                    ---
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
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #e9ecef;
        }
        .select2-dropdown {
            border: 1px solid #e9ecef;
        }
        .select2-container .select2-selection--single {
            height: 32px;
        }
        .callout {
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            background-color: #ffffff;
            border-left: 5px solid #e9ecef;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .callout a {
            color: #495057;
            text-decoration: underline;
        }

        .callout a:hover {
            color: #e9ecef;
        }

        .callout p:last-child {
            margin-bottom: 0;
        }

        .callout.callout-danger {
            border-left-color: #bd2130;
        }

        .callout.callout-warning {
            border-left-color: #d39e00;
        }

        .callout.callout-info {
            border-left-color: #117a8b;
        }

        .callout.callout-success {
            border-left-color: #1e7e34;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {
            var table_user = $('#table_user').DataTable({
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
                                $('#table_user thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('client.user', [$client->_id]) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            return '<a class="btn btn-primary btn-xs text-white" target="_blank" href="https://sistemas.anatel.gov.br/se/public/view/b/form.php?id='+data.id+'&state='+data.state+'">MOSAICO</a>';
                        }
                    },
                    {data: "fistel", 'name': 'fistel'},
                    {data: "uf", 'name': 'uf'},
                    {data: "municipio", 'name': 'municipio'},
                    {
                        data: "servico", 'name': 'servico', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "canal", 'name': 'canal', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "frequencia", 'name': 'frequencia', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "entidade.finalidade", 'name': 'entidade.finalidade', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "classe", 'name': 'classe', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "status", 'name': 'status', render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "entidade.entidade_nome_entidade",
                        'name': 'entidade.entidade_nome_entidade',
                        render: function (data) {
                            return data ? data : '---';
                        }
                    },
                    {
                        data: "updated_at", 'name': 'updated_at', render: function (data) {
                            return data ? data : '---';
                        }
                    },

                ]
            });

            $('#table_user thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (table_user.column(i).search() !== this.value) {
                        table_user.column(i).search(this.value, true).draw();
                    }
                });
            });

            $('#clearFilter').click(function () {
                table_user.state.clear();
                window.location.reload();
            })
        });
    </script>

@endsection