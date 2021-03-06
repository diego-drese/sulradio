@component('mail::message')
@if(isset($data['emailToOwner']))
# Olá {{$data['owner']->name}},
Voce possui uma nova atualização.
@else
# Olá {{$data['agent']->name}},
Voce possui uma nova atualização.
@endif


O Usuário <b>{{$data['userLogged']->name}}</b> editou o ticket <b>{{$data->subject}}</b> com as seguintes informações:
<ul>
<li>Prioridade: <b style="color: {{$data->priority_color}}">{{$data->priority_name}}</b></li>
<li>Categoria: <b style="color: {{$data->category_color}}">{{$data->category_name}}</b></li>
<li>Status: <b style="color: {{$data->status_color}}">{{$data->status_name}}</b></li>
<li>Previsão de início: <b>{{$data->start_forecast}}</b></li>
<li>Previsão de término: <b>{{$data->end_forecast}}</b></li>
<li>Emissora: <b>{{$data->emissora ? $data->emissora : '-----'}}</b></li>
<li>Finalizado: {!! $data->completed_at ? '<b style="color:#238807">Sim</b>' : '<b style="color:#881407">Não</b>' !!} </li>
</ul>
<div class="">
{!! $data['html'] !!}
</div>
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