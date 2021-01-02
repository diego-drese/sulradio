<div class="single-note-item all-category note-business">
    <div class="card card-body ">
        @if(isset($client))
            <span class="side-stick text-success"></span>
            <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="{{$client->company_name}}">{{$client->company_name}}
                <i class="point fas fa-circle ml-1 font-10 text-success"></i></h5>
            <p class="note-date font-12 text-muted">{{$client->created_at->diffForHumans()}}</p>
            <div class="note-content">
                <p class="note-inner-content text-muted" data-notecontent="{{$client->name}}">
                    Nome: <b>{{$client->name}}</b>
                </p>
                <p class="note-inner-content text-muted" data-notecontent="{{$client->name}}">
                    Email: <b>{{$client->email}}</b>
                </p>
                <p class="note-inner-content text-muted" data-notecontent="{{$client->name}}">
                    {{$client->document_type}}: <b>{{$client->document}}</b>
                </p>
            </div>
        @else
            <span class="side-stick text-danger"></span>
            <h5 class="note-title text-truncate w-75 mb-0" >Não possui cliente
                <i class="point fas fa-circle ml-1 font-10 text-danger"></i></h5>
            <p class="note-date font-12 text-muted">---</p>
            <div class="note-content">
                <p class="note-inner-content text-muted">
                    Nome: <b>---</b>
                </p>
                <p class="note-inner-content text-muted">
                    Email: <b>---</b>
                </p>
                <p class="note-inner-content text-muted">
                     --- : <b>---</b>
                </p>
            </div>
        @endif
    </div>
</div>