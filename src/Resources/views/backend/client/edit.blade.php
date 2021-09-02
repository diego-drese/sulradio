@extends('SulRadio::backend.layout.main')
@section('title', 'Editar Cliente')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('client.update', $data->id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.client.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




