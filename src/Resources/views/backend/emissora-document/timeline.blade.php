@extends('Admin::layouts.backend.main')
@section('title', 'Documento timeline')
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
                                <a href="{{route('emissora.document.index', [$emissora->emissoraID])}}" class="btn btn-primary m-r-5">
                                    <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                                </a>

                            </div>
                        </div>
                    </div>
                    <ul class="timeline">
                    @foreach($timeline as $key=>$item)
                        <li class="{{$key%2==0 ? 'timeline-item' : 'timeline-item timeline-inverted'}}">
                            <div class="timeline-badge danger">
                                <span class="font-12">
                                    <img alt="user" src="{{$item->user_picture ? $item->user_picture : '/vendor/oka6/admin/assets/images/users/user_avatar.svg'}}" class="img-fluid">

                                </span>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4 class="timeline-title">{{$item->action}} por {{$item->user_name}}</h4>
                                </div>
                                <div class="timeline-body">
                                    <p>
                                        id: {{$item->document_id}}
                                    </p><p>
                                        Nome: {{$item->document_name}}
                                    </p>
                                    <p>
                                        Tipo: {{$item->document_type_name}}
                                    </p>
                                    <p>
                                        Tamanho: {{$item->file_size}}
                                    </p>
                                    <p>
                                        Data: {{$item->date}}
                                    </p>
                                    <p>
                                        <a href="{{$item->download}}" target="_blank">Baixar</a>
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    </ul>
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
                        return '<a href="'+row['download']+'" target="_blank">'+data+'</a>';
                    }},
                    {data: "file_size", 'name': 'document.file_size'},
                    {data: "document_type_name", 'name': 'document_type.name'},
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            if (!hasEdit) return '---';
                            var edit_button = "";
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
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