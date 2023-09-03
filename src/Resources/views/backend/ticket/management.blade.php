@extends('SulRadio::backend.layout.main')
@section('title', 'Tickets')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <label for="name">Id </label>
                            <div class="input-group mb-3">
                                <input type="text" name="identify" id="identify" class="form-control text-uppercase" placeholder="Id">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-file-document"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <label for="name">Assunto </label>
                            <div class="input-group mb-3">
                                <input type="text" name="subject" id="subject" class="form-control text-uppercase" placeholder="Assunto">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-file-document"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <label for="category_id">Categoria</label>
                            <div class="input-group mb-3">
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($category as $value)
                                        <option value="{{$value->id}}"> {{$value->name}} </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-ticket"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <label for="status_id">Ativo</label>
                            <div class="input-group mb-3">
                                <select name="active" id="active" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="-1">Não</option>

                                </select>
                                    <span class="input-group-text "><i class="fas fa-check-circle"></i></span>
                                </div>
                        </div>
                        <div class="col-2">
                            <label for="show_client">Mostra Cliente</label>
                            <div class="input-group mb-3">
                                <select name="show_client" id="show_client" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>

                                </select>
                                <span class="input-group-text "><i class="fas fa-check-circle"></i></span>
                            </div>
                        </div>

                        <div class="col-3">
                            <label for="status_id">Status</label>
                            <div class="input-group mb-3">
                                <select name="status_id" id="status_id" class="form-control" required>
                                    <option value="">Selecione</option>
                                    @foreach($status as $value)
                                        <option value="{{$value->id}}"> {{$value->name}} </option>
                                    @endforeach
                                </select>
                                <span class="input-group-text "><i class="mdi mdi-alert-box"></i></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="emissora_id">Emissora</label>
                            <div class="input-group mb-3">
                                <select name="emissora_id" id="emissora_id" class="form-control">
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="mdi mdi-radio"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <label for="participants_id">Solicitante</label>
                            <div class="input-group mb-3">
                                <select name="requester_id" id="requester_id" class="form-control select2">
                                    <option value="">Selecione</option>
                                    @foreach($users as $value)
                                        <option value="{{$value->id}}"> {{$value->name.' '.$value->lastname}} </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="participants_id">Responsável</label>
                            <div class="input-group mb-3">
                                <select name="participants_id" id="participants_id" class="form-control select2" multiple>

                                    @foreach($users as $value)
                                        <option value="{{$value->id}}"> {{$value->name.' '.$value->lastname}} </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button  class="btn btn-danger" id="remove">Remover</button>
                            <button  class="btn btn-info" id="changeCategory">Categoria</button>
                            <button  class="btn btn-dark" id="changeStatus">Status</button>
                            <button  class="btn btn-warning" id="changeParticipant">Responsáveis</button>
                            <button  class="btn btn-orange" id="changeRequester">Solicitante</button>
                            <button  class="btn btn-info" id="showClient">Exibir cliente</button>
                            <button  class="btn btn-danger" id="hideClient">Não Exibir cliente</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-2" >
                        <table id="table_ticket" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width: 75px">Ações</th>
                                <th>Assunto</th>
                                <th>Categoria</th>
                                <th>Ativo</th>
                                <th>Status</th>
                                <th>Emissora</th>
                                <th>Atualizado</th>
                                <th>Prazo&nbsp;Protocolo</th>
                                <th>Responsáveis</th>
                                <th>Solicitante</th>
                                <th>Mostra Cliente</th>
                            </tr>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="changeCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Mudar Categoria do ticket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 class="mdi mdi-account-circle mb-2">Seleciona uma categoria</h5>
                                <div class="input-group mb-3">
                                    <select name="change_category_id" id="change_category_id" class="form-control">
                                        <option value="">Selecione</option>
                                        @foreach($category as $value)
                                            <option value="{{$value->id}}"> {{$value->name}} </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-ticket"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="actionChangeCategory">Mudar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Mudar Status do ticket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 class="mdi mdi-account-circle mb-2">Seleciona o status</h5>
                                <div class="input-group mb-3">
                                    <select name="change_status_id" id="change_status_id" class="form-control" required>
                                        <option value="">Selecione</option>
                                        @foreach($status as $value)
                                            <option value="{{$value->id}}"> {{$value->name}} </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text "><i class="mdi mdi-alert-box"></i></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="actionChangeStatus">Mudar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeParticipantModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Mudar participantes do ticket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 class="mdi mdi-account-circle mb-2">Seleciona os participates</h5>
                                <div class="input-group mb-3">
                                    <select name="change_participants_id" id="change_participants_id" class="form-control select2" multiple>
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}"> {{$value->name.' '.$value->lastname}} </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="actionChangeParticipant">Mudar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="changeRequesterModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Mudar solicitante do ticket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 class="mdi mdi-account-circle mb-2">Seleciona o solicitante</h5>
                                <div class="input-group mb-3">
                                    <select name="change_requester_id" id="change_requester_id" class="form-control select2">
                                        <option value="">Selecione</option>
                                        @foreach($users as $value)
                                            <option value="{{$value->id}}"> {{$value->name.' '.$value->lastname}} </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="actionChangeRequester">Mudar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/bootstrap-switch.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/sweetalert2.css')}}">
    <style>
        .table td, .table th {
            padding: 0.5em;
        }
        #table_ticket_filter{
            display: none;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #e9ecef;
        }
        .select2-dropdown {
            border: 1px solid #e9ecef;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
            height: auto
        }.select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            height: auto
        }
        .select2-container .select2-selection--single {
            height: 32px;
        }
        .select2-container .select2-selection--multiple {
            min-height:  32px;
        }
        .note-toolbar-wrapper{height: inherit!important;}
        .note-toolbar{z-index: 5}
        .mouse-pointer{cursor: pointer}
        .mouse-pointer:hover{text-decoration: underline}
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/bootstrap-switch.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/sweetalert2.js')}}></script>
    <script>
        var hasEdit = '{{$hasEdit}}';
        $(document).ready(function () {
            var parseFilter = function (d){
                d.identify          = $("input[name='identify']").val();
                d.subject           = $("input[name='subject']").val();
                d.category_id       = $("select[name='category_id']").val();
                d.active            = $("select[name='active']").val();
                d.show_client       = $("select[name='show_client']").val();
                d.status_id         = $("select[name='status_id']").val();
                d.emissora_id       = $("select[name='emissora_id']").val();
                d.requester_id      = $("select[name='requester_id']").val();
                d.participants_id   = $("select[name='participants_id']").val();
                return d;
            }

            var table_ticket = $('#table_ticket').DataTable({
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Busca",
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
                searching: true,
                ajax: {
                    url: '{{ route('ticket.management.index') }}',
                    type: 'GET',
                    data: function (d) {
                        d._token            = $("input[name='_token']").val();
                        return parseFilter(d);
                    }
                },
                "order": [[ 6, "desc" ]],
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            edit_button += '<a target="_blank" href="' + data.ticket_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Visualizar</b></a><br> Id:'+data.id;
                            return edit_button
                        }
                    },
                    {data: "subject", 'name': 'subject'},
                    {data: "category_name", 'name': 'ticket_category.id', render:function (data, display, row){
                            return '<b style="color:'+row.category_color+'">'+data+'</b>'
                        }},
                    {data: "completed_at", 'name': 'completed_at', render:function (data, display, row){
                            return data ? '<b style="color:#d9534f">Não</b>' : '<b style="color:#4caf50">Sim</b>';
                        }},
                    {data: "status_name", 'name': 'ticket_status.id', render:function (data, display, row){
                            return '<b style="color:'+row.status_color+'">'+data+'</b>'
                        }},
                    {data: "emissora_nome", 'name': 'emissora.razao_social'},
                    {data: "updated_at", 'name': 'ticket.updated_at'},
                    {data: "end_forecast", 'name': 'ticket.end_forecast', render:function (data, display, row){
                            if(!data){
                                return '---';
                            }
                            var now = moment();
                            var endForest = moment(data, "DD/MM/YYYY");
                            var days = endForest.diff(now, 'days');
                            if(days<0){
                                return '<b class="text-danger">'+data+'</b>'
                            }else if(days<3){
                                return '<b class="text-warning">'+data+'</b>'
                            }else{
                                return '<b class="text-success">'+data+'</b>'
                            }
                        }},
                    {data: "participants", 'name': 'ticket_participant.user_id',searchable: false, orderable:false },
                    {data: "user_name", 'name': 'ticket.owner_id', searchable: false, orderable: false},
                    {data: "show_client", 'name': 'show_client', render:function (data, display, row){
                            return !data ? '<b style="color:#d9534f">Não</b>' : '<b style="color:#4caf50">Sim</b>';
                        }},
                ]
            });
            var totalRecords = 0;
            table_ticket.on( 'init', function () {
                totalRecords = table_ticket.settings().recordsTotal;
                console.log('Total de registros:', totalRecords);
            });

            $('#subject, #identify').keyup(function (){
               var text = $("input[name='subject']").val();
               if(text.length>3){
                   table_ticket.draw();
               }else if(text.length===0){
                   table_ticket.draw();
               }
            })
            $('#category_id, #active, #show_client, #status_id, #emissora_id, #participants_id, #requester_id').change(function (){
                table_ticket.draw();
            });

            $("#participants_id, #requester_id, #change_participants_id, #change_requester_id").select2({
                width: 'calc(100% - 38px)'
            });

            $("#emissora_id").select2({
                width: 'calc(100% - 38px)',
                minimumInputLength: 3,
                placeholder: 'Selecione',
                allowClear: true,
                tag: true,
                ajax: {
                    url: '{{route('broadcast.search')}}',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            ignore_client: true,
                            search_select: '1'
                        }
                        return query;
                    },
                    processResults: function (data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });
            $('#remove').click(function (){
                if(parseFilter({})<1){
                    swal("Atenção!", "Efetue filtros para nao remover, essa açao nao tem mais volta. ", "warning");
                    return false;
                }
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá remover '+table_ticket.page.info().recordsTotal+' ticket(s), e todos seus arquivos anexos essa ação é irreversivel.',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.delete')}}',
                        type: "GET",
                        data: parseFilter( {_token:$('input[name="_token"]').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Removendo aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets removidos com sucesso", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });

            $('#showClient').click(function (){
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá habilitar a visualização de '+table_ticket.page.info().recordsTotal+' ticket(s).',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.show.client')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Ajustando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets habilitados para os clientes.", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });
            $('#hideClient').click(function (){
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá desabilitar a visualização de'+table_ticket.page.info().recordsTotal+' ticket(s).',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.hide.client')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Ajustando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets desabilitados para os clientes.", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });

            $('#changeCategory').click(function (){
                $('#changeCategoryModal').modal('show');
            });
            $('#actionChangeCategory').click(function (){
                if($('#change_category_id').val().length<1){
                    swal("Atenção!", "Selecione uma categoria", "warning");
                    return;
                }
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá modificar '+table_ticket.page.info().recordsTotal+' ticket(s) para a categoria escolhida',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.change.category')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val(), 'category':$('#change_category_id').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Atualizando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets atualizados com sucesso", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });

            $('#changeStatus').click(function (){
                $('#changeStatusModal').modal('show')
            });
            $('#actionChangeStatus').click(function (){
                if($('#change_status_id').val().length<1){
                    swal("Atenção!", "Selecione o status", "warning");
                    return;
                }
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá modificar '+table_ticket.page.info().recordsTotal+' ticket(s) para o status escolhido',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.change.status')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val(), 'status':$('#change_status_id').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Atualizando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets atualizados com sucesso", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });

            $('#changeParticipant').click(function (){
                $('#changeParticipantModal').modal('show')
            });
            $('#actionChangeParticipant').click(function (){
                if($('#change_participants_id').val().length<1){
                    swal("Atenção!", "Selecione ao menos um participante", "warning");
                    return;
                }
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá modificar '+table_ticket.page.info().recordsTotal+' ticket(s) para os participantes escolhidos',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.change.participant')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val(), 'participants':$('#change_participants_id').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Atualizando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets atualizados com sucesso", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });

            $('#changeRequester').click(function (){
                $('#changeRequesterModal').modal('show');
            });
            $('#actionChangeRequester').click(function (){
                if($('#change_requester_id').val().length<1){
                    swal("Atenção!", "Selecione o solicitante", "warning");
                    return;
                }
                swal({
                    title: "Você têm certeza?",
                    text: 'Essa ação irá modificar '+table_ticket.page.info().recordsTotal+' ticket(s) para o solicitante escolhido',
                    type: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim!",
                    cancelButtonText: "Cancelar!",
                }).then((isConfirm) => {
                    if (isConfirm.dismiss==='cancel') return;
                    $.ajax({
                        url: '{{route('ticket.management.change.requester')}}',
                        type: "POST",
                        data: parseFilter( {_token:$('input[name="_token"]').val(), 'change_requester_id':$('#change_requester_id').val()}),
                        dataType: "json",
                        beforeSend: function() {
                            swal.fire({
                                html: '<h5>Atualizando aguarde...</h5>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function (data) {
                            swal("Sucesso!", "Tickets atualizados com sucesso", "success").then(() => {
                                table_ticket.draw();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Erro!", xhr.responseJSON.message, "error");
                        }
                    });
                });
            });
        });

    </script>

@endsection



