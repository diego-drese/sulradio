@extends('Admin::layouts.backend.main')
@section('title', 'Criar Ato oficial')
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
                          action="{{route('emissora.atos.oficiais.store', [$emissora->emissoraID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-ato.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



