@extends('Admin::layouts.backend.main')
@section('title', 'Editar tipo de documento')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('document.type.update', $data->_id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.document_type.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




