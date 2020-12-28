@extends('Admin::layouts.backend.main')
@section('title', 'Editar Usuário')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('client.user.update', [$client->_id, $data->_id])}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.client.user.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




