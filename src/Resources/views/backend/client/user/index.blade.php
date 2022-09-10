@extends('SulRadio::backend.layout.main')
@section('title', 'Usuários')
@section('content')
    <div class="row">
        <div class="col-12">
            @include('SulRadio::backend.client.header')
            <div class="card">
                <div class="card-body">
                    <div class="card-title  text-right">
                        <div class="btn-group">
                            <a href="{{route('client.index', [$client->id])}}" class="btn btn-primary m-r-5">
                                <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                            </a>
                            @if($hasAdd)
                                <a href="{{route('client.user.create', [$client->id])}}" class="btn btn-primary">
                                    <span class="fa fa-plus"></span> <b>Adicionar</b>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_user" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Status</th>
                                <th>Função</th>
                                <th>Último Login</th>
                                <th>Último Email</th>
                                <th>Reenviar Email</th>
                                <th style="width: 120px">Ações</th>
                            </tr>
                            <tr>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar nome">
                                </th>
                                <th>
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Email">
                                </th>
                                <th>
                                    ---
                                </th>

                                <th>
                                    <select class="form-control fieldSearch">
                                        <option value="">Todos</option>
                                        <option value="1">Sim</option>
                                        <option value="0">Não</option>
                                    </select>
                                </th>
                                <th>
                                    ---
                                </th>
                                <th>
                                    ---
                                </th>
                                <th>
                                    ---
                                </th>
                                <th>
                                    ---
                                </th>
                                <th style="width: 60px">
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
                    url: '{{ route('client.user', [$client->id]) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {data: "name", 'name': 'name'},
                    {data: "email", 'name': 'email'},
                    {data: "profile_name", 'name': 'profile_name', searchable: false, orderable: false,},
                    {data: "active", 'name': 'active', orderable: false,
                        render: function (data, display, row) {
                            if (data == "1") {
                                return '<span class="badge badge-success mr-1 ">SIM</span>';
                            } else if (data == '0') {
                                return '<span class="badge badge-danger mr-1 ">NÃO</span>';
                            }
                            return '---';
                        }
                    },
                    {data: "function_name", 'name': 'function_name', searchable: false, orderable: false,},
                    {data: "last_login_at", 'name': 'last_login_at'},
                    {data: "last_notification_at", 'name': 'last_notification_at'},
                    {data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            @if($hasUserNotify)
                            if(data.last_login_at=='---'){
                                edit_button += '<a href="' + data.notify_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Nova conta</b></a>';
                            }else {
                                edit_button += '<a href="' + data.notify_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Reset Senha</b></a>';
                            }
                            @endif
                           return edit_button
                        }
                    },
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            @if($hasEdit)
                                edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            @endif
                            return edit_button
                        }
                    }
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