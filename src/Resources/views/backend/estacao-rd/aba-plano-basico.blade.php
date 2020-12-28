<div class="row p-t-10">
    @php
        $administrativo = $emissora->administrativo;
    @endphp
    <div class="col-md-6 card card-body bg-secondary text-white">
        <h4>Plano Básico</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">UF</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->uf}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Município</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->municipio}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Local Específico</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['localespecifico']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Canal</label>
            <div class="col-md-9">
                <input class="form-control" type="text"
                       value="{{$emissora->canal}}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Reuso</label>
            <div class="col-md-9">
                <input class="form-control" type="text"
                       value="{{$emissora->reuso ?? 'NÃO'}}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">UF Reuso</label>
            <div class="col-md-9">
                <input class="form-control" type="text"
                       value="---"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Município Reuso</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Classe</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->classe}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">ERP</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->erp}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Altura Antena</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="{{$emissora->altura}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-right">Pareamento</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-md-3 col-form-label text-left">Limitações</label>
            <div class="col-md-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>

        </div>
    </div>
    <div class="col-md-6 card card-body bg-light">
        <h4>Pontos do contorno Protegido</h4>
        <label for="example-search-input" class="col-md-12 col-form-label text-right"></label>
        <div class="form-group row">
            @for($i=0; $i<=355; $i+=5)
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-right" style="min-width: 70px;"> {{$i*1}}º </span>
                        </div>
                        <input class="form-control" type="text" value="---" readonly>
                    </div>
                </div>
            @endFor
        </div>
    </div>
    <div class="col-md-12 card card-body bg-light">
        <h4>Localização</h4>
        @php
            $documentContrato = isset($emissora->documento_historico) ? $emissora->documento_historico : [];
        @endphp
        <table class="table">
            <tr>
                <th>Número Processo</th>
                <th>Número Documento</th>
                <th>Tipo Documento</th>
                <th>Orgão</th>
                <th>Data do Documento</th>
                <th>Data de Publicação (DOU)</th>
                <th>Razão do Documento</th>
                <th>Natureza</th>
            </tr>
            <tbody>
            @foreach($documentContrato as $value)
                @if($value['razao'] && $value['razao'] == 'Contrato')
                    <tr>
                        <th>{{$value['numeroprocesso'] && !empty($value['numeroprocesso']) ? $value['numeroprocesso'] : '9999'}}</th>
                        <th>{{$value['numerodocumento']?? '0000'}}</th>
                        <th>{{$value['tipodocumento']?? '---'}}</th>
                        <th>{{$value['orgao']?? '---'}}</th>
                        <th>{{$value['datadocumento'] ? date('d/m/Y', strtotime($value['datadocumento'])) :  '---'}}</th>
                        <th>{{$value['datadou']? date('d/m/Y', strtotime($value['datadou'])) :  date('d/m/Y', strtotime($value['datadocumento']))}}</th>
                        <th>{{$value['razao']?? '---'}}</th>
                        <th>{{$value['natureza'] && !empty($value['natureza']) ? $value['natureza'] : '---'}}</th>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-md-12 card card-body bg-secondary text-white">
        <h4>Informações do documento de Aprovação de Locais</h4>
        @php
            $documentContrato = isset($emissora->administrativo) && isset($emissora->administrativo['aprovacao_locais']) ? $emissora->administrativo['aprovacao_locais'] : [];
        @endphp
        <table class="table text-white">
            <tr>
                <th>Número Processo</th>
                <th>Número Documento</th>
                <th>Tipo Documento</th>
                <th>Orgão</th>
                <th>Data do Documento</th>
                <th>Data de Publicação (DOU)</th>
                <th>Razão do Documento</th>
                <th>Natureza</th>
            </tr>
            <tbody>
            <tr>
                <th>{{$documentContrato['numprocesso'] && !empty($documentContrato['numprocesso']) ? $documentContrato['numprocesso'] : '9999'}}</th>
                <th>{{$documentContrato['numdocumento']?? '0000'}}</th>
                <th>{{$documentContrato['tipodocumento']?? '---'}}</th>
                <th>{{$documentContrato['codorgao']?? '---'}}</th>
                <th>{{$documentContrato['datadocumento'] ? date('d/m/Y', strtotime($value['datadocumento'])) :  '---'}}</th>
                <th>{{$documentContrato['datadou']? date('d/m/Y', strtotime($value['datadou'])) :  date('d/m/Y', strtotime($value['datadocumento']))}}</th>
                <th>{{$documentContrato['razao']?? '---'}}</th>
                <th>{{$documentContrato['natureza'] && !empty($value['natureza']) ? $value['natureza'] : '---'}}</th>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-12 card card-body bg-light">
        <h4>Histórico de Documentos EmitidosHistórico de Documentos Emitidos</h4>
        @php
            $documents= isset($emissora->documento_historico) ? $emissora->documento_historico : [];
        @endphp
        <table class="table">
            <tr>
                <th>Número Processo</th>
                <th>Número Documento</th>
                <th>Tipo Documento</th>
                <th>Orgão</th>
                <th>Data do Documento</th>
                <th>Data de Publicação (DOU)</th>
                <th>Razão do Documento</th>
                <th>Natureza</th>
            </tr>
            <tbody>
            <tr>
            @foreach($documents as $value)
                @if($value['razao'] && $value['razao'] != 'Outorga' &&  $value['razao'] != 'Aprovação de Local')
                    <tr>
                        <th>{{$value['numeroprocesso'] && !empty($value['numeroprocesso']) ? $value['numeroprocesso'] : '9999'}}</th>
                        <th>{{$value['numerodocumento']?? '0000'}}</th>
                        <th>{{$value['tipodocumento']?? '---'}}</th>
                        <th>{{$value['orgao']?? '---'}}</th>
                        <th>{{$value['datadocumento'] ? date('d/m/Y', strtotime($value['datadocumento'])) :  '---'}}</th>
                        <th>{{$value['datadou']? date('d/m/Y', strtotime($value['datadou'])) :  date('d/m/Y', strtotime($value['datadocumento']))}}</th>
                        <th>{{$value['razao']?? '---'}}</th>
                        <th>{{$value['natureza'] && !empty($value['natureza']) ? $value['natureza'] : '---'}}</th>
                    </tr>
                @endif
            @endforeach
                    </tr>
            </tbody>
        </table>
    </div>
</div>