@extends('Admin::layouts.backend.main')
@section('title', 'Documentos')
@section('content')
    <div class="row">
        <div class="col-6">
            @include('SulRadio::backend.emissora.header')
        </div>
        <div class="col-6">
            @include('SulRadio::backend.client.header')
        </div>
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
                                    <a href="{{route('emissora.document.create',$emissora->emissoraID)}}"
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
                                <th>Nome</th>
                                <th>Validade</th>
                                <th>Arquivo</th>
                                <th>Tamanho</th>
                                <th>Tipo de documento</th>
                                <th style="width: 80px">Ações</th>
                            </tr>
                            <tr>

                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Nome"></th>
                                <th> --- </th>
                                <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Buscar tipo dea rquivo"></th>
                                <th> --- </th>
                                <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar por tipo"></th>
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
        var hasTimeLine = '{{$hasTimeLine}}';
        function copyUrl(ele){
            var copyText    = document.createElement('textarea');
            var id          = ele.id.split('-')[1];
            /* Select the text field */
            copyText.value = $('#document-'+id).attr('href');
            document.body.appendChild(copyText);
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand("copy");
            document.body.removeChild(copyText);

            /* Alert the copied text */
            toastr.success('Copiado', copyText.value)
        }
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
                    url: '{{ route('emissora.document.index', $emissoraID) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {data: "name", 'name': 'document.name'},
                    {data: "validated", 'name': 'document.validated', render: function(data){
                        if(data){
                            return data;
                        }
                        return '---';
                    }},
                    {data: "file_type", 'name': 'document.file_type', render: function(data, display, row){
                        return '<a id="document-'+row.id+'" href="'+row['download']+'" target="_blank">'+data+'</a>  <a href="#" onclick="copyUrl(this)"  id="copy-'+row.id+'" title="copiar"><span class="mdi mdi-content-copy font-24 text-success" title="Copiar"></span></a>';
                    }},
                    {data: "file_size", 'name': 'document.file_size'},
                    {data: "document_type_name", 'name': 'document_type.name'},
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            if (hasEdit) {
                                edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            }
                            if (hasTimeLine) {
                                edit_button += '<a href="' + data.timeline + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Tmeline</b></a>';
                            }


                            return edit_button
                        }
                    }
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