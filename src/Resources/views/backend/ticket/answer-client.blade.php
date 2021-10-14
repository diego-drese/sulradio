@extends('SulRadio::backend.layout.main')
@section('title', 'Resposta')
@section('content')
    <form method="post" action="#" id="form-comment" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-10">
                    {!! $ticketNotificationClient->comment !!}
                </div>
            </div>
            <div class="card">
                <div class="card-body p-10">
                    <h4 class="m-b-20">Escreva um comentário</h4>

                        {{ csrf_field() }}
                        <textarea id="content" name="content" class="summernote" aria-hidden="true" style="display: none;"></textarea>
                        <button id="saveComment" type="button" class="m-t-20 btn waves-effect waves-light btn-success">Salvar comentário</button>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body text-center">
                            <ul  class="list-group">
                                <li class="list-group-item text-center"><h4 class="card-title">Arquivos</h4></li>
                                @foreach($attach as $document)
                                    <li class="list-group-item text-left">
                                        <a target="_blank" href="{{route('document.download.ticket',[$document->id])}}" >
                                            {{$document->file_name_original}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <ul  class="list-group p-t-10">
                                <li class="list-group-item text-center"><h4 class="card-title">Anexar</h4></li>
                                @for($i=1;$i<11;$i++)
                                    <li class="list-group-item text-left">
                                        <input type="file" class="form-control" name="answer_file_{{$i}}" />
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </form>
@endsection
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/summernote.css')}}">
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
            var url= '{{route('ticket.client.answer', [$ticketNotificationClientUser->identify])}}';
            swal({
                title: "Você têm certeza?",
                text: 'Essa ação nao pode ser desfeita.',
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Cancelar!",
            }).then((isConfirm) => {
                if (isConfirm.dismiss==='cancel') return;
                $('#form-comment').attr('action', url).submit();
            });

        });


    </script>
@endsection



