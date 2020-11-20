@extends('Admin::layouts.backend.main')
@section('title', 'Editar Endereço')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.endereco.update', [$data->emissoraID, $data->enderecoID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-processo.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




