@component('mail::message')
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
<hr />
<h1>Olá {{$data->user_name}}</h1>
<div>
{!! $data->comment !!}
</div>
<hr />
Para interagir com essa solicitação acesse:
@component('mail::button', ['url' => route('ticket.client.answer', [$data->identify])])
Clique aqui
@endcomponent
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
Obrigado,

{{ config('app.name') }}
@endcomponent