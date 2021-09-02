@extends('SulRadio::backend.layout.main')
@section('title', 'Editar Usuário')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('SulRadio::backend.client.header')
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('client.user.update', [$client->id, $data->_id])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.client.user.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




