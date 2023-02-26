@extends('SulRadio::backend.layout.main')
@section('title', 'Adiciona Município')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('municipio.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.municipio.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



