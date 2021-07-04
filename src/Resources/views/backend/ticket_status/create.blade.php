@extends('Admin::layouts.backend.main')
@section('title', 'Criar status de tickets')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('ticket.status.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.ticket_status.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



