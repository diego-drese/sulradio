@component('mail::message')
# Olá {{$data['agent']->name}},
Lembrete de prazo de execução.<br/>
O Prazo Execução é [{{$days}}] dias.

<ul>
<li>Prioridade: <b style="color: {{$data->priority_color}}">{{$data->priority_name}}</b></li>
<li>Categoria: <b style="color: {{$data->category_color}}">{{$data->category_name}}</b></li>
<li>Status: <b style="color: {{$data->status_color}}">{{$data->status_name}}</b></li>
<li>Emissora: <b>{{$data->emissora ?? "---"}}</b></li>
<li>Prazo Execução :<b style="color: #EB1110">{{$data->start_forecast}}</b> </li>
<li>Prazo Protocolo :<b>{{$data->end_forecast}}</b> </li>
</ul>
<div class="subcopy"></div>
<div>
    {!! $data['html'] !!}
</div>
<div class="subcopy"></div>

@component('mail::button', ['url' => route('ticket.ticket', [$data->id])])
    Clique aqui para esse ticket
@endcomponent
@component('mail::button', ['url' => route('ticket.index')])
    Meus Tickets
@endcomponent
Obrigado,

{{ config('app.name') }}
@endcomponent