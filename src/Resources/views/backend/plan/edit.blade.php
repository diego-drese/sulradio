@extends('Admin::layouts.backend.main')
@section('title', 'Editar Plano')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('plan.update', $data->_id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.plan.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




