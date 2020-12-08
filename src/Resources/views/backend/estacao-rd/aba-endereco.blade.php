<div class="row p-t-10">
    <div class="col-4 card card-body bg-secondary text-white">
        <h4>Endereço do Transmissor</h4>
        @php
            $enderecos = $emissora->enderecos;
            $estacao = isset($enderecos['estacao']) ? $enderecos['estacao'] : [];
            $estacaoprincipal= isset($enderecos['estacaoprincipal']) ? $enderecos['estacaoprincipal'] : [];
            $estacaoauxiliar= isset($enderecos['estacaoauxiliar']) ? $enderecos['estacaoauxiliar'] : [];

        @endphp
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CEP</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_codcep']) ? $estacao['estacao_codcep'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Logradouro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_endlogradouro']) ? $estacao['estacao_endlogradouro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_endnumero']) ? $estacao['estacao_endnumero'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Complemento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Bairro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_endbairro']) ? $estacao['estacao_endbairro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">UF</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_siglauf']) ? $estacao['estacao_siglauf'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Município</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacao['estacao_nomemunicipio']) ? $estacao['estacao_nomemunicipio'] : '---') }}"
                       readonly>
            </div>
        </div>

    </div>

    <div class="col-4 card card-body bg-light">
        <h4>Endereço do Estúdio Principal</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CEP</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_codcep']) ? $estacaoprincipal['estacaoprincipal_codcep'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Logradouro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_endlogradouro']) ? $estacaoprincipal['estacaoprincipal_endlogradouro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_endnumero']) ? $estacaoprincipal['estacaoprincipal_endnumero'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Complemento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Bairro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_endbairro']) ? $estacaoprincipal['estacaoprincipal_endbairro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">UF</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_siglauf']) ? $estacaoprincipal['estacaoprincipal_siglauf'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Município</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoprincipal['estacaoprincipal_nomemunicipio']) ? $estacaoprincipal['estacaoprincipal_nomemunicipio'] : '---') }}"
                       readonly>
            </div>
        </div>
    </div>
    <div class="col-4 card card-body bg-secondary text-white">
        <h4>Endereço do Estúdio Auxiliar</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CEP</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_codcep']) ? $estacaoauxiliar['estacaoauxiliar_codcep'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Logradouro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_endlogradouro']) ? $estacaoauxiliar['estacaoauxiliar_endlogradouro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_endnumero']) ? $estacaoauxiliar['estacaoauxiliar_endnumero'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Complemento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Bairro</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_endbairro']) ? $estacaoauxiliar['estacaoauxiliar_endbairro'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">UF</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_siglauf']) ? $estacaoauxiliar['estacaoauxiliar_siglauf'] : '---') }}"
                       readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Município</label>
            <div class="col-9">
                <input class="form-control" type="text"
                       value="{{(isset($estacaoauxiliar['estacaoauxiliar_nomemunicipio']) ? $estacaoauxiliar['estacaoauxiliar_nomemunicipio'] : '---') }}"
                       readonly>
            </div>
        </div>
    </div>
</div>