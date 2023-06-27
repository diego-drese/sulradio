@extends('SulRadio::backend.layout.main')
@section('title', 'Municípios')
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
                                    <a href="{{route('municipio.create')}}" class="btn btn-primary">
                                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_municipio" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 120px">Ações</th>
                                <th>Nome</th>
                                <th>UF</th>
                            </tr>
                            <tr>
                                <th style="width: 60px">
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar nome">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar nome">
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
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {
            var table_municipio = $('#table_municipio').DataTable({
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
                                $('#table_municipio thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('municipio.index') }}',
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
                                return edit_button
                        }
                    },
                    {data: "desc_municipio", 'name': 'desc_municipio'},
                    {data: "uf_extenso", 'name': 'uf.uf_extenso'},


                ]
            });

            $('#table_municipio thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    if (table_municipio.column(i).search() !== this.value) {
                        table_municipio.column(i).search(this.value, true).draw();
                    }
                });
            });

            $('#clearFilter').click(function () {
                table_municipio.state.clear();
                window.location.reload();
            })
        });
    </script>

@endsection
