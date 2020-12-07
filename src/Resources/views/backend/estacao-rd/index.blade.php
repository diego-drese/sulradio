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

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_emissoras" class="table table-striped table-bordered">
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
                                <th>Fistel Geradora</th>
                            </tr>
                            <tr>
                                <th style="width: 60px">
                                    <spa class="btn btn-primary btn-xs m-r-5" id="clearFilter">
                                        <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                                    </spa>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary "  >
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
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary "  >
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
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <select class="fieldSearch form-control text-primary "  >
                                        <option value="">(Todos)</option>
                                        <option value="Comercial">Comercial</option>
                                        <option value="Educativo">Educativo</option>
                                        <option value="Educativo publico">Educativo publico</option>
                                        <option value="Publica">Publica</option>
                                    </select>
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>
                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
                                </th>

                                <th role="row">
                                    <input type="text" autocomplete="off" class="fieldSearch form-control text-primary"  placeholder="Bucar Id">
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

        $(document).ready(function () {
            $(".select2").select2({
                width: '100%',
                placeholder: 'Selecione',
                allowClear: true
            });
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
                    url: '{{ route('anatel.emissoras') }}',
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
                                return edit_button
                        }
                    },
                    {data: "fistel", 'name': 'fistel'},
                    {data: "uf", 'name': 'uf'},
                    {data: "municipio", 'name': 'municipio'},
                    {data: "servico", 'name': 'servico', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "canal", 'name': 'canal', render:function (data){
                        return data ? data : '---';
                    }},
                    {data: "frequencia", 'name': 'frequencia', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "entidade.finalidade", 'name': 'entidade.finalidade', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "classe", 'name': 'classe', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "status", 'name': 'status', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "entidade.entidade_nome_entidade", 'name': 'entidade.entidade_nome_entidade', render:function (data){
                            return data ? data : '---';
                    }},
                    {data: "entidade.habilitacao_numfistelgeradora", 'name': 'entidade.habilitacao_numfistelgeradora', render:function (data){
                            return data ? data : '---';
                    }},
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