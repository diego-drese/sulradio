@extends('SulRadio::backend.layout.main')
@section('title', 'Criar Emissora')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('emissora.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.emissora.tabs')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



