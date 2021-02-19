<div class="single-note-item all-category note-business">
    <div class="card card-body ">
        <h5 class="note-title text-truncate w-75 mb-0" title="{{$emissora->razao_social}}">{{$emissora->razao_social}}
            <i class="point fas fa-circle ml-1 font-10 text-success"></i></h5>
        <div class="row">
            <div class="col-lg-4">

                <p class="note-date font-12 text-muted">{{$emissora->desc_servico}}</p>
                <div class="note-content">
                    <p class="note-inner-content text-muted" title="{{$emissora->nome_fantasia}}">
                        Nome Fantasia: <b>{{$emissora->nome_fantasia}}</b>
                    </p>
                    <p class="note-inner-content text-muted" title="{{$emissora->desc_municipio.'('.$emissora->desc_uf.')'}}">
                        Localidade: <b>{{$emissora->desc_municipio}} ({{$emissora->desc_uf}})</b>
                    </p>
                    <p class="note-inner-content text-muted" title="{{$emissora->classe}}">
                        Classe: <b>{{$emissora->classe}}</b>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <p class="note-inner-content text-muted">
                    <b>Observações</b>
                </p>
                <p class="note-inner-content text-muted" style="max-height: 78px;overflow: auto">
                    {{$emissora->observacao}}
                </p>
            </div>
            <div class="col-lg-4">
                <p class="note-inner-content text-muted">
                    <b>Informação sobre renovação:</b>
                </p>
                <p class="note-inner-content text-muted" style="max-height: 78px;overflow: auto">
                    {{$emissora->informacao_renovacao}}
                </p>
            </div>
        </div>

    </div>
</div>