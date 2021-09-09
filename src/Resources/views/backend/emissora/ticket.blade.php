@extends('SulRadio::backend.layout.main')
@section('title', $data->subject)
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {!! $data->html !!}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
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
                                <li class="list-group-item text-center">
                                    <a  href="{{route('emissora.tickets',[$emissora->emissoraID])}}" class="btn btn-primary m-r-5">
                                        <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <div class="card-body text-center">
                            <h4 class="card-title">Documentos</h4>
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
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
@section('style_head')

@endsection
@section('script_footer_end')

@endsection



