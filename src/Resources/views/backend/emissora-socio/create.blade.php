@extends('Admin::layouts.backend.main')
@section('title', 'Criar Sócio')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post"
                          action="{{route('emissora.socio.store', [$emissora->emissoraID])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora-socio.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



