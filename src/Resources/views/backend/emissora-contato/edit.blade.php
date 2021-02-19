@extends('Admin::layouts.backend.main')
@section('title', 'Editar contato')
@section('content')
    <div class="row">
       @include('SulRadio::backend.emissora_header.header')
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.contato.update', [$data->emissoraID, $data->contatoID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-contato.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




