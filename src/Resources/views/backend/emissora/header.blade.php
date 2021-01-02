<div class="single-note-item all-category note-business">
    <div class="card card-body ">
        <span class="side-stick text-success"></span>
        <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="{{$emissora->razao_social}}">{{$emissora->razao_social}}
            <i class="point fas fa-circle ml-1 font-10 text-success"></i></h5>
        <p class="note-date font-12 text-muted">{{$emissora->desc_servico}}</p>
        <div class="note-content">
            <p class="note-inner-content text-muted" data-notecontent="{{$emissora->name}}">
                Nome Fantasia: <b>{{$emissora->nome_fantasia}}</b>
            </p>
            <p class="note-inner-content text-muted" data-notecontent="{{$emissora->name}}">
                Localidade: <b>{{$emissora->desc_municipio}} ({{$emissora->desc_uf}})</b>
            </p>
            <p class="note-inner-content text-muted" data-notecontent="{{$emissora->name}}">
                Classe: <b>{{$emissora->classe}}</b>
            </p>
        </div>
    </div>
</div>