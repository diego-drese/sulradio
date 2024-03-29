@extends('SulRadio::backend.layout.main')
@section('title', 'Editar ticket')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('ticket.update', $data->id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.ticket.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




