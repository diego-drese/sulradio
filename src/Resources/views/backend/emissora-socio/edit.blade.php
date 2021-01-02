@extends('Admin::layouts.backend.main')
@section('title', 'Editar Sócio')
@section('content')
    <div class="row">
        <div class="col-6">
            @include('SulRadio::backend.emissora.header')
        </div>
        <div class="col-6">
            @include('SulRadio::backend.client.header')
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.socio.update', [$data->emissoraID, $data->socioID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-socio.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




