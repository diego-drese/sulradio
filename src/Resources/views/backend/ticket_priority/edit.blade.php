@extends('SulRadio::backend.layout.main')
@section('title', 'Editar prioridades de tickets')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('ticket.priority.update', $data->id)}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.ticket_priority.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




