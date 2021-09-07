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
                    <h4 class="card-title">Comentários</h4>
                    @foreach($comments as $comment)
                        <ul class="list-unstyled m-t-40 border-bottom">
                            <li class="media">
                                @if($comment->user_picture)
                                    <img class="avatar-default m-r-15" src="{{$comment->user_picture}}" style="width: 60px;height: 60px;padding: 0;margin-top: -10px;">
                                @else
                                    <img class="avatar-default m-r-15" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg" style="width: 60px;height: 60px;padding: 0;margin-top: -10px;">
                                @endif
                                <br>

                                <div class="media-body">
                                    <h5 class="mt-0 mb-1"><b>{{$comment->user_name}}</b> -  {{$comment->created_at}}</h5>
                                    <div>
                                        {!! $comment->html !!}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    @endforeach

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
                            <h6>Prioridade</h6>
                            <h6 style="color:{{$data->priority_color}}">{{$data->priority_name}}</h6>
                            <div class="progress">
                                <div class="progress-bar " role="progressbar" style="width: 100%; height: 6px; background: {{$data->priority_color}}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-6">

                            <h6>Categoria</h6>
                            <h6 style="color:{{$data->category_color}}">{{$data->category_name}}</h6>
                            <div class="progress">
                                <div class="progress-bar " role="progressbar" style="width: 100%; height: 6px; background: {{$data->category_color}}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <h6>Status</h6>
                            <h6 style="color:{{$data->status_color}}">{{$data->status_name}}</h6>
                            <div class="progress">
                                <div class="progress-bar " role="progressbar" style="width: 100%; height: 6px; background: {{$data->status_color}}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6>Criado em</h6>
                            <h6 class="text-info">{{$data->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i')}}</h6>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="col-6">
                            <h6>Emissora</h6>
                            <h6 class="text-info"> {!! $data->emissora ? $data->emissora : '-----' !!}</h6>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6>Prev. Início</h6>
                            <h6 class="text-info">{{$data->start_forecast}}</h6>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6>Prev. Término</h6>
                            <h6 class="text-info">{{$data->end_forecast}}</h6>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 100%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-6 m-t-10 m-b-10 text-left">
                            @if($hasAdmin || in_array($user->id, $participants->pluck('id')->toArray()) || $user->id==$data->owner_id)
                                <a href="{{route('ticket.edit', [$data->id])}}" class="btn btn-sm btn-secondary">
                                    Editar
                                </a>
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
                                    <li class="list-group-item text-left" id="document-{{$document->id}}">
                                        <a target="_blank" href="{{route('document.download.ticket',[$document->id])}}" >
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


    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/summernote.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/dropzone.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/sweetalert2.js')}}></script>
    <script>

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



