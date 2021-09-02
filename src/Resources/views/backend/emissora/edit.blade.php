@extends('SulRadio::backend.layout.main')
@section('title', 'Editar Emissora')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('emissora.update',$data->emissoraID)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




