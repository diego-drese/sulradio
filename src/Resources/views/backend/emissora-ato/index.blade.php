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
                                    <a href="{{route('emissora.atos.oficiais.create',$emissora->emissoraID)}}" class="btn btn-primary">
                                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                                    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_profiles" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead class="">
                            <tr>
                                <th role="row">#</th>
                                <th>Tipo do Ato</th>
                                <th>Número do ato</th>
                                <th>Data do Ato</th>
                                <th>Data DOU</th>
                                <th style="width: 60px">Ações</th>
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
            $('#table_profiles').DataTable({
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
                ajax: '{{ route('emissora.atos.oficiais.index', $emissoraID) }}',
                columns: [
                    {data: "atoID", 'name': 'atoID', searchable: false},
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
        });
    </script>

@endsection