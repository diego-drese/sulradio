@extends('SulRadio::backend.layout.main')
@section('title', 'Criar processo')
@section('content')
    <div class="row">
        @include('SulRadio::backend.emissora_header.header')
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.processo.store', [$emissora->emissoraID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-processo.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



