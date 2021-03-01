@component('mail::message')

@if($data['sentTo']=='CurrentAgent')
# Olá {{$data['agent']->name}},
@elseif($data['sentTo']=='OldAgent')
# Olá {{$data['oldAgent']->name}},
@elseif($data['sentTo']=='Owner')
# Olá {{$data['owner']->name}},
@endif

Voce possui uma nova atualização.

o Usuário <b>{{$data['userLogged']->name}}</b> tranferiu o ticket <b>{{$data->subject}}</b> de {{$data['oldAgent']->name}} para {{$data['agent']->name}}

Para conferir acesse:
@component('mail::button', ['url' => route('ticket.ticket', [$data->id])])
    Clique aqui para esse ticket
@endcomponent
@component('mail::button', ['url' => route('ticket.index')])
    Meus Tickets
@endcomponent
Obrigado,

{{ config('app.name') }}
@endcomponent