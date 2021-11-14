@component('mail::message')
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
<hr />
<h1>Prezado cliente SEAD - Sulradio.</h1>
<div>
Acesse o sistema SEAD - Sulradio para ler e intergir com um processo de seu interesse através do botão abaixo.
</div>
<hr />
Para interagir com essa solicitação acesse:
@component('mail::button', ['url' => route('ticket.client.answer', [$data->identify])])
ACESSE AQUI
@endcomponent
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
Obrigado,

{{ config('app.name') }}
@endcomponent