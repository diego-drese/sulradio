@component('mail::message')

# Olá {{$data['agent']->name}},
Voce possui uma nova atualização.

o usuário <b>{{$data['userLogged']->name}}</b> adicionou um comentário ao ticket <b>{{$data->subject}}</b>:
<hr />
<div class="">
{!! $data['html'] !!}
</div>
<hr />
Para conferir acesse:
@component('mail::button', ['url' => route('ticket.ticket', [$data->ticket_id])])
Clique aqui para esse ticket
@endcomponent
@component('mail::button', ['url' => route('ticket.index')])
Meus Tickets
@endcomponent
Obrigado,

{{ config('app.name') }}
@endcomponent