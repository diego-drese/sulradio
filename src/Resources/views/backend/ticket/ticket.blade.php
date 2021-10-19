@extends('SulRadio::backend.layout.main')
@section('title', $data->subject)
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    {!! $data->html !!}
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mdi mdi-comment-text-outline"> Comentários</h4>
                    <ul class="list-unstyled m-t-10 border-bottom comment-content">
                    @foreach($comments as $comment)
                            <div class="border p-2 m-t-5">
                                <li class="media comment">
                                    @if($comment->user_picture)
                                        <img class="avatar-default m-r-15" src="{{$comment->user_picture}}" style="width: 60px;height: 60px;padding: 0;">
                                    @else
                                        <img class="avatar-default m-r-15" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg" style="width: 60px;height: 60px;padding: 0;">
                                    @endif
                                    <br>
                                    <div class="media-body collapse"  id="command-{{$comment->id}}" aria-expanded="false">
                                        <h5 class="mt-0 mb-1"><b>{{$comment->user_name}}</b> -  {{$comment->created_at}}</h5>
                                        <div id="comment-{{$comment->id}}">
                                            {!! $comment->html !!}
                                        </div>
                                    </div>
                                </li>

                                <div class="openComment m-t-5 m-b-5">
                                    <a role="button" class="collapsed" data-toggle="collapse" href="#command-{{$comment->id}}" aria-expanded="false" aria-controls="command-{{$comment->id}}"> </a>
                                    @if($comment->send_client)
                                        <span class="text-danger mdi mdi-send client-notified mouse-pointer" id="notify-comment-{{$comment->notification->id}}"> Cliente Notificado</span>
                                    @elseif(($hasAdmin || $hasSendNotification) && count($usersEmissora))
                                        <span class="text-success mdi mdi-send  mouse-pointer client-notify" data-toggle="modal" data-target="#sendEmailModal" id="send-comment-{{$comment->id}}"> Notificar Cliente</span>
                                    @endif
                                </div>

                            @if(isset($comment->notification) && !empty($comment->notification->answered))
                                <ul class="list-unstyled m-t-10 p-l-10 bg-light">
                                    @foreach($comment->notification->answered as $answered)
                                        <li class="media comment border-top p-t-10 p-b-10 bg-light">
                                            @if($answered->user_picture)
                                                <img class="avatar-default m-r-15" src="{{$answered->user_picture}}" style="width: 60px;height: 60px;padding: 0;">
                                            @else
                                                <img class="avatar-default m-r-15" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg" style="width: 60px;height: 60px;padding: 0;">
                                            @endif
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1"><b>{{$answered->user_name}}</b> <i>{{$answered->user_email}}</i></h5>
                                                <h6 class="mt-0 mb-1"><b>{{$answered->status}}</b> {{$answered->updated_at}} </h6>
                                                @if($answered->answer)
                                                    <h6 class="card-title mdi mdi-comment-text-outline"> Resposta</h6>
                                                    <div class="bg-white overflow-auto p-2 list-group-item">
                                                        {!! $answered->answer !!}
                                                    </div>
                                                @else
                                                    <div>
                                                        Aguardando resposta.
                                                    </div>
                                                @endif
                                                @foreach($answered->attach as $key=>$attach)
                                                    @if($key==0)
                                                        <h6 class="card-title mdi mdi-attachment m-t-10"> Anexos do cliente</h6>
                                                        <ul class="list-group bg-whitep-2">
                                                    @endif
                                                    <li class="list-group-item text-left" >
                                                        <a target="_blank" href="{{route('document.download.ticket', [$attach->id])}}">{{$attach->file_name}}</a>
                                                    </li>
                                                    @if(!isset($answered->attach[$key+1]))
                                                        </ul>
                                                    @endif
                                                @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            </div>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="m-b-20">Escreva um comentário</h4>
                    <form method="post" action="#" id="form-comment">
                        {{ csrf_field() }}
                        <textarea id="content" name="content" class="summernote" aria-hidden="true" style="display: none;"></textarea>
                            <a  href="{{route('ticket.index')}}" class="m-t-20 btn waves-effect waves-light btn-primary">
                                <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                            </a>
                            <button id="saveComment" type="button" class="m-t-20 btn waves-effect waves-light btn-success">Salvar comentário</button>
                        @if(($hasAdmin || in_array($user->id, $participants->pluck('id')->toArray()) || $user->id==$data->owner_id) &&  !$data->completed_at)
                                <button id="endTicket" class="m-t-20 btn waves-effect waves-light btn-danger">Encerrar ticket</button>
                            @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">

                <div class="card-body">
                    <h4 class="card-title">Informações.</h4>


                    <div class="row">
                        <div class="col-6">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <h6>Prioridade</h6>
                                    <h6 style="color:{{$data->priority_color}}">{{$data->priority_name}}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Categoria</h6>
                                    <h6 style="color:{{$data->category_color}}">{{$data->category_name}}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Status</h6>
                                    <h6 style="color:{{$data->status_color}}">{{$data->status_name}}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Criado em</h6>
                                    <h6 class="text-info">{{$data->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i')}}</h6>
                                </li>
                            </ul>


                        </div>
                        <div class="col-6">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <h6>Emissora</h6>
                                    <h6 class="text-info"> {!! $data->emissora ? $data->emissora : '-----' !!}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Prev. Início</h6>
                                    <h6 class="text-info">{{$data->start_forecast}}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Prev. Término</h6>
                                    <h6 class="text-info">{{$data->end_forecast}}</h6>
                                </li>
                                <li class="list-group-item">
                                    <h6>Exibe para o cliente</h6>
                                    <h6 class="text-info">{!! $data->show_client? '<b class="text-success">Sim</b>': '<b class="text-danger">Não</b>' !!}</h6>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12">
                            @if($hasAdmin || in_array($user->id, $participants->pluck('id')->toArray()) || $user->id==$data->owner_id)
                                <ul class="list-group">
                                    <li class="list-group-item">
                                    <a href="{{route('ticket.edit', [$data->id])}}" class="btn btn-sm btn-secondary">
                                        Editar
                                    </a>
                                    </li>
                                </ul>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body text-center">
                            <h4 class="card-title">Anexos</h4>
                            <ul  class="list-group">
                                @foreach($documents as $document)
                                    <li class="list-group-item text-left " id="document-{{$document->id}}">
                                        <a class="{{$document->file_preview=='client'? 'text-dark' : ''}}" title="{{$document->file_preview=='client'? 'Documento adicionado pelo cliente' : ''}}" target="_blank" href="{{route('document.download.ticket', [$document->id])}}" >
                                            {{$document->file_name_original}}
                                        </a>
                                        @if($user->id==$document->user_id || $hasAdmin)
                                            <span class="delete-todo todo-action cursor-pointer delete-document" id="delDoc-{{$document->id}}"><i class="ti-close"></i></span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <div id="dropzone">
                                <div class="dz-message">Anexe mais arquivos</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body text-center">
                            <h4 class="card-title">Rastreamento de processos</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name_tracker" name="name_tracker" placeholder="Nome">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="description_tracker" name="description_tracker" placeholder="Descrição">
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="url_tracker" name="url_tracker" placeholder="Url">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button" onclick="add_tracker();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group text-justify">
                                @foreach($trackerUrl as $url)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">{{$url->name}}</h5>
                                            <small class="text-purple" title="Última data de modificação">{{$url->last_modify ? $url->last_modify->diffForHumans() : '... '}} <i class="fas fa-window-close text-danger" style="cursor: pointer" title="Remover" onclick="delete_tracker({{$url->id}});"></i></small>
                                        </div>
                                        <p class="mb-1">
                                            {{$url->description}}
                                        </p>

                                        <small class="text-info" title="Ultima verificação">
                                            Última busca - {{$url->last_tracker ? $url->last_tracker->diffForHumans() : '...'}} <br/>
                                            <a href="{{$url->url}}" target="_blank">Link</a>

                                        </small>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="row">
                    <div class="col-6 border-right">
                        <div class="card-body text-center">
                            <h4 class="card-title">Responsáveis</h4>
                            @foreach($participants as $participant)
                                <div class="profile-pic m-b-20 m-t-20">
                                    @if($participant->picture)
                                        <img class="avatar-default d-xs-none" src="{{$participant->picture}}" style="width: 80px;height: 80px;padding: 0">
                                    @else
                                        <img class="avatar-default  d-xs-none" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg"  style="width: 80px;height: 80px;padding: 0">
                                    @endif

                                    <h4 class="m-t-20 m-b-0">{{$participant->name}}</h4>
                                    <h5 class="m-t-20 m-b-0">{{$participant->profile_name}}</h5>
                                    <a href="{{$participant->email}}">{{$participant->email}}</a>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card-body text-center">
                            <h4 class="card-title">Criado por</h4>
                            <div class="profile-pic m-b-20 m-t-20">
                                @if($owner->picture)
                                    <img class="avatar-default d-xs-none" src="{{$owner->picture}}" style="width: 80px;height: 80px;padding: 0">
                                @else
                                    <img class="avatar-default  d-xs-none" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg"  style="width: 80px;height: 80px;padding: 0">
                                @endif

                                <h4 class="m-t-20 m-b-0">{{$owner->name}}</h4>
                                <h5 class="m-t-20 m-b-0">{{$owner->profile_name}}</h5>
                                <a href="{{$owner->email}}">{{$owner->email}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Enviar comentário por email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="mdi mdi-account-circle mb-2"> Enviar para</h5>
                    <div id="modal-users" class="overflow-auto">
                        <table class="table table-bordered border-primary">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width: 70px" class="p-2">

                                    </th>
                                    <th class="p-2">
                                        Nome
                                    </th>
                                    <th class="p-2">
                                        Email
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach($usersEmissora as $userEmissora)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="users" value="{{$userEmissora->id}}">
                                        </td>
                                        <td>
                                            {{$userEmissora->name.' '.$userEmissora->lastname}}
                                        </td>
                                        <td>
                                            {{$userEmissora->email}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h5 class="mdi mdi-comment-text-outline m-b-5"> Cometário</h5>
                    <div class="overflow-auto">
                        <textarea id="modal-comment" name="modal-comment" class="summernote" aria-hidden="true" style="display: none;"></textarea>
                    </div>
                    <hr>
                    <h5 class="mdi mdi-attachment m-b-5"> Anexos</h5>
                    <div id="modal-attachment"  class="overflow-auto">
                        <table class="table table-bordered border-primary">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 70px" class="p-2">
                                    Máx. 10
                                </th>
                                <th class="p-2">
                                    Arquivo
                                </th>
                                <th class="p-2">
                                    Tamanho
                                </th>
                                <th class="p-2">
                                    Tipo
                                </th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="attachment" data-size="{{$document->file_size}}"value="{{$document->id}}">
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{route('document.download.ticket',[$document->id])}}" >
                                            {{$document->file_name_original}}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $document->file_size_format !!}
                                    </td> <td>
                                        {!! $document->file_type !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" id="sendEmail" data-comment-id="" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sentEmailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Comentário enviado por email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="mdi mdi-account-circle mb-2"> Enviar para</h5>
                    <div id="modal-users-sent" class="overflow-auto">
                        <table class="table table-bordered border-primary">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th class="p-2">
                                    Nome
                                </th>
                                <th class="p-2">
                                    Email
                                </th>
                                <th class="p-2">
                                    Status
                                </th>
                            </tr>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h5 class="mdi mdi-comment-text-outline m-b-5"> Cometário</h5>
                    <div id="modal-comment-sent" class="overflow-auto">

                    </div>
                    <hr>
                    <h5 class="mdi mdi-attachment m-b-5"> Anexos</h5>
                    <div id="modal-attachment-sent"  class="overflow-auto">
                        <table class="table table-bordered border-primary">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th class="p-2">
                                    Arquivo
                                </th>
                                <th class="p-2">
                                    Tipo
                                </th>
                                <th class="p-2">
                                    Removido
                                </th>
                            </tr>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/summernote.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/sweetalert2.css')}}">
    <style>
        .note-toolbar-wrapper{height: inherit!important;}
        .note-toolbar{z-index: 5}
        .truncate {
            width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cursor-pointer{cursor: pointer;    padding: 0px 5px;}
        .cursor-pointer:hover{color: #860426 }
        h5,h6{margin: 0}
        .progress{margin-bottom: 10px}


        .comment {
            overflow: auto;
            min-height: 8em;
        }
        .openComment a {
            font-weight: bold;
            color: #8c8787;
            min-width: 100px;
            display: inline-block;
        }

        .openComment a:hover {
            text-decoration: underline;
        }
        .openComment a.collapsed:after {
            content: '+ Mostrar mais';
        }

        .openComment a:not(.collapsed):after {
            content: '- Mostrar menos';
        }

        .comment .collapse:not(.show) {
            display: block;
            max-height: 8em;
            overflow: hidden;
        }

        .comment-content .collapsing {
            height: 8em;
        }

        .mouse-pointer {
            cursor: pointer;
        }
        .client-notified{
            font-weight: bold;
            border-left: solid 3px #ccc;
            padding-left: 5px;
        }
        .client-notified:before{
            transform: rotate(-40deg);
        }
        .client-notify{
            font-weight: bold;
            border-left: solid 3px #ccc;
            padding-left: 5px;
        }

        .client-notified-info{
            font-weight: bold;
            border-left: solid 3px #ccc;
            padding-left: 5px;
        }

        .mouse-pointer:hover{
            text-decoration: underline;
        }

    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/summernote.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/dropzone.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/sweetalert2.js')}}></script>
    <script>
        var urlDownload='{{route('document.download.ticket',[':id'])}}';
        var hideAllPopovers = function() {
            $('.client-notified-info').each(function() {
                if($(this).attr('aria-describedby')){
                    $(this).trigger('click')
                }
            });
        };
        $('.summernote').summernote({
            height: 250,
            codemirror: { // codemirror options
                theme: 'monokai'
            }
        });
        $('#saveComment').click(function (){
            var url= '{{route('ticket.comment', [$data->id])}}';
            $('#form-comment').attr('action', url).submit();
        });

        $('#endTicket').click(function (){
            var url= '{{route('ticket.end', [$data->id])}}';
            $('#form-comment').attr('action', url).submit();
        });


        $('.client-notify').click(function (){
            var id= this.id;
            var comment = $('#comment-'+id.split('-')[2]).html();
            $('#sendEmailModal #modal-comment').summernote('code', comment);
            $('#sendEmailModal').modal('toggle');
            $('#sendEmail').attr('data-comment-id', id.split('-')[2]);
        });

        $('.client-notified').click(function (){
            var url='{{route('ticket.notification.client', [':id'])}}';
            var id= this.id.split('-')[2];
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: "GET",
                data: {},
                dataType: "json",
                success: function (data) {
                    $('#modal-comment-sent').html(data.ticketNotificationClient.comment);
                    $('#modal-users-sent table tbody').html('');
                    $('#modal-attachment-sent table tbody').html('');
                    var tableTbody='';
                    for(var i=0; i<data.users.length; i++){
                        tableTbody+="<tr>"
                            tableTbody+="<td>"
                                tableTbody+=data.users[i].user_name;
                            tableTbody+="</td>"
                            tableTbody+="<td>"
                                tableTbody+=data.users[i].user_email;
                            tableTbody+="</td>"
                            tableTbody+="<td>"
                                tableTbody+=data.users[i].status;
                            tableTbody+="</td>"
                        tableTbody+="</tr>"
                    }
                    $('#modal-users-sent table tbody').html(tableTbody);

                    tableTbody='';
                    for(var i=0; i<data.attach.length; i++){
                        tableTbody+="<tr>"
                            tableTbody+="<td>"
                                tableTbody+='<a target="_blank" href="'+urlDownload.replace(':id',data.attach[i].id)+'">'+data.attach[i].file_name_original+'</a>';
                            tableTbody+="</td>"
                            tableTbody+="<td>"
                                tableTbody+=data.attach[i].file_type;
                            tableTbody+="</td>"
                            tableTbody+="<td>"
                                if(data.attach[i].removed){
                                    tableTbody+='<span class="label label-danger">Sim</span>';
                                }else{
                                    tableTbody+='<span class="label label-success">Não</span>';
                                }
                            tableTbody+="</td>"
                        tableTbody+="</tr>"
                    }
                    $('#modal-attachment-sent table tbody').html(tableTbody);
                    $('#sentEmailModal').modal('toggle');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Erro!", xhr.responseJSON.message, "error");
                }
            });

        });

        $('#sendEmail').click(function (){
            var commentId = $(this).attr('data-comment-id');
            var users = Array
                    .from(document.querySelectorAll('input[name="users"]'))
                    .filter((checkbox) => checkbox.checked)
                    .map((checkbox) => checkbox.value);
            var attachment = Array
                    .from(document.querySelectorAll('input[name="attachment"]'))
                    .filter((checkbox) => checkbox.checked)
                    .map((checkbox) => checkbox.value);

            var attachmentSize = Array
                    .from(document.querySelectorAll('input[name="attachment"]'))
                    .filter((checkbox) => checkbox.checked)
                    .map((checkbox) => $(checkbox).attr('data-size'));

            if(users.length<1){
                toastr.info('Selecione ao menos um usuário para envio', "Informações", {timeOut: 6000});
                return false;
            }
            if(attachmentSize.length){
                var size = 0;
                for (var i=0; i<attachmentSize.length; i++){
                    size+= Number(attachmentSize[i].replace('KB', ""));
                }
                if(size>120000){
                    toastr.info('Limite de anexos excedidos, é possível enviar somente 15Mb ', "Informações", {timeOut: 6000});
                    return false;
                }
            }
            var comment = $("#modal-comment").val();
            var text="Voce selecionou "+users.length+" usuário(s) e "+attachment.length+' anexo(s) para o envio desse comentário. Essa ação nao pode ser desfeita.';

            swal({
                title: "Você têm certeza?",
                text: text,
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Cancelar!",
            }).then((isConfirm) => {
                if (isConfirm.dismiss==='cancel') return;
                var url='{{route('ticket.comment.send.email')}}';
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {_token:$('input[name="_token"]').val(), 'comment_id':commentId, 'comment':comment, 'users':users, 'attachment':attachment},
                    dataType: "json",
                    success: function (data) {
                        swal("Sucesso!", "Email agendado com sucesso.", "success").then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Erro!", xhr.responseJSON.message, "error");
                    }
                });
            });

        });

        var cancelSubscripton = function (url, text ,id) {
            swal({
                title: "Você têm certeza?",
                text: text,
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Cancelar!",
            }).then((isConfirm) => {
                console.log(isConfirm);
                if (isConfirm.dismiss==='cancel') return;
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        swal("Sucesso!", "Arquivo removido com sucesso", "success").then(() => {
                            $('#'+id).remove();
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Erro!", xhr.responseJSON.message, "error");
                    }
                });
            });
        }

        $('.delete-document').click(function (){
            var url = '{{route('document.remove.ticket', [':id'])}}';
            var id = this.id.split('-')[1];
            cancelSubscripton(url.replace(':id', id), 'Você está prestes a remover esse documento', 'document-'+id)
        });

        $(document).ready(function () {
            Dropzone.prototype.defaultOptions.dictDefaultMessage = "Arraste os arquivos aqui para enviar";
            Dropzone.prototype.defaultOptions.dictFallbackMessage = "Seu navegador não suporta uploads de arquivos arrastar e soltar.";
            Dropzone.prototype.defaultOptions.dictFallbackText = "Use o formulário substituto abaixo para enviar seus arquivos como antigamente.";
            Dropzone.prototype.defaultOptions.dictFileTooBig = "O arquivo é muito grande (@{{filesize}} MB). Tamanho máximo do arquivo: @{{maxFilesize}} MB.";
            Dropzone.prototype.defaultOptions.dictInvalidFileType = "você não pode fazer upload de arquivos deste tipo.";
            Dropzone.prototype.defaultOptions.dictResponseError = "O servidor respondeu com o código @{{statusCode}}.";
            Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar upload";
            Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Tem certeza que deseja cancelar este upload?";
            Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover";
            Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "Você não pode enviar mais arquivos.";

            var drop = $("div#dropzone").dropzone({
                url: '{{route('ticket.upload', [$data->id])}}',
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: 30,
                parallelUploads: 1,
                maxFiles: 10,
                preventDuplicates: true,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                init: function () {
                    this.on("error", function (file, message) {
                        toastr.error(message, "Erro", {timeOut: 6000});
                        this.removeFile(file);
                    });
                    this.on("complete", function (file) {
                        toastr.success('Arquivos carregados com sucesso, a página sera recarregada em 2 segundos', "Sucesso", {timeOut: 6000});
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    })
                },
                success: function (file, result) {

                },
                error: function (file, result) {
                    toastr.error(result.message, "Erro", {timeOut: 6000});
                }
            })
                .addClass('dropzone');
        });
        function add_tracker(){
            var data = {
                _token:$('input[name="_token"]').val(),
                name:$('#name_tracker').val(),
                description:$('#description_tracker').val(),
                url:$('#url_tracker').val(),
                ticket_id:{{$data->id}},
            };
            var url = '{{route('ticket.save.tracker.url', [$data->id])}}'
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "json",
                success: function (data) {
                    swal("Sucesso!", "Url adcionada com sucesso", "success").then(() => {
                        document.location.reload(true);
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Erro!", xhr.responseJSON.message, "error");
                }
            });

        }
        function delete_tracker(id){
            swal({
                title: "Você têm certeza?",
                text: 'Essa url nao será mais rastreada',
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Cancelar!",
            }).then((isConfirm) => {
                console.log(isConfirm);
                if (isConfirm.dismiss==='cancel') return;
                var url = '{{route('ticket.delete.tracker.url', [':id'])}}';
                $.ajax({
                    url: url.replace(':id', id),
                    type: "GET",
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        swal("Sucesso!", "Url removida com sucesso", "success").then(() => {
                            document.location.reload(true);
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Erro!", xhr.responseJSON.message, "error");
                    }
                });
            });
        }
    </script>
@endsection



