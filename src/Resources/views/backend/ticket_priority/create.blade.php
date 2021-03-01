@extends('Admin::layouts.backend.main')
@section('title', 'Criar prioridades de tickets')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('ticket.priority.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.ticket_priority.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



