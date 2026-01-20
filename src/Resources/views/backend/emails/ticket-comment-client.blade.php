@component('mail::message')
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
<hr />
<h1>SULRADIO – Atualização de Processo</h1>
<div>
Uma nova atualização está disponível no sistema SEAD.
</div>
<hr />
Acessar o processo:
@component('mail::button', ['url' => route('ticket.client.answer', [$data->identify])])
ACESSE AQUI
@endcomponent
<i><b style="color: #d21325">*</b> Esta é uma mensagem automática. Por favor, não responda!</i>
Obrigado,
{{ config('app.name') }}
@endcomponent