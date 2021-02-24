@extends('Admin::layouts.backend.main')
@section('title', 'Documento timeline')
@section('content')
    <div class="row">
       @include('SulRadio::backend.emissora_header.header')
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex  align-items-center m-b-10">
                        <div class="ml-auto">
                            <div class="btn-group">
                                <a href="{{$back_url}}" class="btn btn-primary m-r-5">
                                    <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                                </a>

                            </div>
                        </div>
                    </div>
                    <ul class="timeline">
                    @foreach($timeline as $key=>$item)
                        <li class="{{$key%2==0 ? 'timeline-item' : 'timeline-item timeline-inverted'}}">
                            <div class="timeline-badge white-box">
                                <span class="font-12">
                                    <img alt="user" class="avatar-default  d-xs-none" src="{{$item->user_picture ? $item->user_picture : '/vendor/oka6/admin/assets/images/users/user_avatar.svg'}}" class="img-fluid">

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
                                        Finalidade: {{$item->goal ? $item->goal : '---'}}
                                    </p><p>
                                        Pasta: {{$item->document_folder_name ? $item->document_folder_name : '---'}}
                                    </p><p>
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


@endsection