@extends('SulRadio::backend.layout.main')
@section('title', 'Editar pasta de documento')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('document.folder.update', $data->id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.document_folder.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




