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
                        <table id="table_profiles" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead class="">
                            <tr>
                                <th role="row">#</th>
                                <th>Razão Social</th>
                                <th>Serviço</th>
                                <th>Localidade</th>
                                <th>Status SEAD</th>
                                <th style="width: 200px">Ações</th>
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
                ajax: '{{ route('emissora.index') }}',
                columns: [
                    {data: "emissoraID", 'name': 'emissoraID', searchable: false},
                    {data: "razao_social", 'name': 'emissora.razao_social'},
                    {data: "desc_servico", 'name': 'servico.desc_servico'},
                    {data: "desc_municipio", 'name': 'municipio.desc_municipio', render:function (data, display, row){
                        if(!row.desc_municipio){
                            return '---'
                        }
                        return row.desc_municipio+' ('+row.ufID+')';
                        }},
                    {data: "desc_status_sead", 'name': 'status_sead.desc_status_sead', render:function (data, display, row){
                        if(data=="ATIVO"){
                            return '<span class="badge badge-success mr-1 ">ATIVO</span>';
                        }else if(data=='INATIVO'){
                            return '<span class="badge badge-danger mr-1 ">INATIVO</span>';
                        }else if(data=='CONCORRENCIA'){
                            return '<span class="badge badge-info mr-1 ">CONCORRENCIA</span>';
                        }
                        return '---';
                    }},
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            edit_button += '<a href="' + data.atos_oficiais + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Atos Oficiais</b></a>';
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Processos</b></a>';
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Societários</b></a>';
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Atos junta comercial</b></a>';
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Contatos</b></a>';
                            edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Endereços</b></a>';
                            return edit_button
                        }
                    }
                ]
            });
        });
    </script>

@endsection