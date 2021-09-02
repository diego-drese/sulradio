@extends('SulRadio::backend.layout.main')
@section('title', 'Editar Endereço')
@section('content')
    <div class="row">
       @include('SulRadio::backend.emissora_header.header')
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.endereco.update', [$data->emissoraID, $data->enderecoID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-endereco.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




