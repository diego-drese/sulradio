@extends('SulRadio::backend.layout.main')
@section('title', $data->subject)
@section('content')
    <div class="row">
{{--        <div class="col-lg-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    {!! $data->html !!}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-12 form-group text-right">
            <a  href="{{route('emissora.tickets', [$emissoraID])}}" class="btn btn-primary m-r-5">
                <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
            </a>
        </div>

        <div class="col-lg-12">
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
                                            <span class="delete-todo todo-action cursor-pointer delete-document" id="delDoc-{{$document->id}}"></span>
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



