@component('mail::message')

# Olá {{$data['user']->name}},
Abaixo segue o acompanhamento diário de seus tickets.
@if($data['each'] && count($data['each']))
<h2>Tickets atribuidos a você. Vencidos ou a vencer(7 dias)</h2>
<ul>
    @foreach($data['each'] as $ticket)
        <li>{{$ticket->total}}: <b style="color: {{$ticket->status_color}}">{{$ticket->status_name}}</b></li>
    @endforeach
</ul>
@endif

@if($data['agent'] && count($data['agent']))
<hr />
<h2>Tickets atribuidos a você. Não finalizados</h2>
<ul>
    @foreach($data['agent'] as $ticket)
        <li>{{$ticket->total}}: <b style="color: {{$ticket->status_color}}">{{$ticket->status_name}}</b></li>
    @endforeach
</ul>
@endif

@if($data['owner'] && count($data['owner']))
<hr />
<h2>Tickets que você abriu. Não finalizados</h2>
<ul>
    @foreach($data['owner'] as $ticket)
        <li>{{$ticket->total}}: <b style="color: {{$ticket->status_color}}">{{$ticket->status_name}}</b></li>
    @endforeach
</ul>
@endif
<hr />
Para conferir acesse:
@component('mail::button', ['url' => route('ticket.index')])
    Meus Tickets
@endcomponent
Obrigado,

{{ config('app.name') }}
@endcomponent