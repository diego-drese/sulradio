@component('mail::message')
# Olá {{$data['user_name']}},
Saiu um novo ato oficial da sua emissora.
## {{$data['razao_social']}}

<ul>
<li>Tipo: {{$data['tipo_nome']}}</li>
<li>Número do ato: {{$data['numero_ato']}}</li>
<li>Seção: {{$data['secao']}}</li>
<li>Categoria: {{$data['categoria_nome']}}</li>
<li>Data do ATO: {{$data['data_ato']}}</li>
<li>Data DOU: {{$data['data_dou']}}</li>

</ul>
@if(isset($data['url']))
<div class="">
    <a href="{{$data['url']}}"> Para conferir clique aqui</a>
</div>
@endif
Obrigado,

{{ config('app.name') }}
@endcomponent