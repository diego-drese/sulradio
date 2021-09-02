@extends('SulRadio::backend.layout.main')
@section('title', 'Criar cliente')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('client.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.client.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



