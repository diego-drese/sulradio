@component('mail::message')
# Olá {{$data['user_name']}},
Publicado no D.O.U ato relacionado à sua emissora:
## {{$data['razao_social']}}

<ul>
<li>Tipo: {{$data['tipo_nome']}}</li>
<li>Número do ato: {{$data['numero_ato']}}</li>
<li>Seção: {{$data['secao']}}</li>
<li>Categoria: {{$data['categoria_nome']}}</li>
<li>Data do ATO: {{$data['data_ato']}}</li>
<li>Data D.O.U: {{$data['data_dou']}}</li>

</ul>
@if(isset($data['url']))
<div class="">
    <a href="{{$data['url']}}"> Para conferir clique aqui</a>
</div>
@endif
Obrigado,

{{ config('app.name') }}
@endcomponent