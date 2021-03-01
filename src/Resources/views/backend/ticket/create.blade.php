@extends('Admin::layouts.backend.main')
@section('title', 'Criar ticket')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body ">
                    <form id="form-profile" method="post" action="{{route('ticket.store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('SulRadio::backend.ticket.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



