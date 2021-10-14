@component('mail::message')

# Olá {{$data->user_name}},
Voce possui uma nova atualização.
<div class="">
{!! $data->comment !!}
</div>
<hr />
Para interagir com essa solicitação acesse:
@component('mail::button', ['url' => route('ticket.client.answer', [$data->identify])])
Clique aqui
@endcomponent
Obrigado,

{{ config('app.name') }}
@endcomponent