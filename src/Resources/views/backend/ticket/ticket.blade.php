@extends('Admin::layouts.backend.main')
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
                                <div class="media-body">
                                    <h5 class="mt-0 mb-1">{{$comment->user_name}}</h5>
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


                            @if(($hasAdmin || $user->id==$data->agent_id) && !$data->completed_at)
                                <button id="endTicket" class="m-t-20 btn waves-effect waves-light btn-danger">Encerrar ticket Rikect</button>
                            @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informações.</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3 m-t-10 m-b-10 text-left">
                            Prioridade
                        </div>
                        <div class="col-3 m-t-10 m-b-10">
                            <h4><span class="badge" style="background: {{$data->priority_color}}; color: #fff">{{$data->priority_name}}</span></h4>
                        </div>
                        <div class="col-3 m-t-10 m-b-10  text-left">
                            Categoria
                        </div>
                        <div class="col-3 m-t-10 m-b-10">
                            <h4><span class="badge" style="background: {{$data->category_color}}; color: #fff">{{$data->category_name}}</span></h4>
                        </div>
                        <div class="col-3 m-t-10 m-b-10  text-left">
                            Status
                        </div>
                        <div class="col-3 m-t-10 m-b-10">
                            <h4><span class="badge" style="background: {{$data->status_color}}; color: #fff">{{$data->status_name}}</span></h4>
                        </div>
                        <div class="col-3 m-t-10 m-b-10  text-left">
                            Criado em
                        </div>
                        <div class="col-3 m-t-10 m-b-10">
                            {{$data->created_at->format('d/m/Y H:i')}}
                        </div>

                        <div class="col-3 m-t-10 m-b-10  text-left">
                            Emissora
                        </div>

                        <div class="col-6 m-t-10 m-b-10  text-left">
                            {{$data->emissora}}
                        </div>
                        <div class="col-3 m-t-10 m-b-10  text-left">
                            @if($hasAdmin || $user->id==$data->agent_id)
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
                    <div class="col-6 border-right">
                        <div class="card-body text-center">
                            <h4 class="card-title">Responsável</h4>
                            <div class="profile-pic m-b-20 m-t-20">
                                @if($agent->picture)
                                    <img class="avatar-default d-xs-none" src="{{$agent->picture}}" style="width: 80px;height: 80px;padding: 0">
                                @else
                                    <img class="avatar-default  d-xs-none" src="/vendor/oka6/admin/assets/images/users/user_avatar.svg"  style="width: 80px;height: 80px;padding: 0">
                                @endif

                                <h4 class="m-t-20 m-b-0">{{$agent->name}}</h4>
                                <h5 class="m-t-20 m-b-0">{{$agent->profile_name}}</h5>
                                <a href="{{$agent->email}}">{{$agent->email}}</a>
                            </div>

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
    <style>
        .note-toolbar-wrapper{height: inherit!important;}
        .note-toolbar{z-index: 5}
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/summernote.js')}}></script>
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
    </script>
@endsection



