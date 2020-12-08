<div class="row p-t-10">
    <div class="col-6 card card-body bg-secondary text-white">
        <h4>Dados da Entidade</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CNPJ</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->cnpj}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Nome Entidade</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['entidade_nome_entidade']}}" readonly>
            </div>
        </div>

        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Nome Fantasia</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['entidade_nome_fantasia']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">DDD</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Telefone</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Email para Contato</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Tipo Usuário</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Tipo Orgão</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
    </div>

    <div class="col-6 card card-body bg-light">
        <h4>Dados da Outorga</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Serviço</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->servico}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Carater</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->carater}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Fistel</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->fistel}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Pasta Cadastral</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['habilitacao_numscradjur']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">SCRAD Técnico</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['habilitacao_numscradtec']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Validade da Radiofrequência</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['habilitacao_datavalfreq']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Finalidade</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{$emissora->entidade['finalidade']}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Abrangência</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
    </div>

    <div class="col-6 card card-body bg-secondary text-white">
        <h4>Responsável Técnico</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CPF</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Nome Responsável</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">E-mail</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
    </div>

    <div class="col-6 card card-body bg-light">
        <h4>Documento da Outorga</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número Processo</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número Documento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Tipo Documento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Orgão</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Data do Documento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Data (DOU)</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Razão do Documento</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Natureza</label>
            <div class="col-9">
                <input class="form-control" type="text" value="---" readonly>
            </div>
        </div>
    </div>
    <div class="col-6 card card-body bg-secondary text-white">
        <h4>Endereço Correspondência</h4>
        @php
            $enderecos = $emissora->enderecos;
            $correspondencia = isset($enderecos['correspondencia']) ? $enderecos['correspondencia'] : [];
            $sede= isset($enderecos['sede']) ? $enderecos['sede'] : [];

        @endphp
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CEP</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_codcep']) ? $correspondencia['correspondencia_codcep'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Logradouro</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_endlogradouro']) ? $correspondencia['correspondencia_endlogradouro'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_endnumero']) ? $correspondencia['correspondencia_endnumero'] : '---') }}" readonly>
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
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_endbairro']) ? $correspondencia['correspondencia_endbairro'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">UF</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_siglauf']) ? $correspondencia['correspondencia_siglauf'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Município</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($correspondencia['correspondencia_nomemunicipio']) ? $correspondencia['correspondencia_nomemunicipio'] : '---') }}" readonly>
            </div>
        </div>

    </div>

    <div class="col-6 card card-body bg-light">
        <h4>Endereço da Sede</h4>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">CEP</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($sede['sede_codcep']) ? $sede['sede_codcep'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Logradouro</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($sede['sede_endlogradouro']) ? $sede['sede_endlogradouro'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Número</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($sede['sede_endnumero']) ? $sede['sede_endnumero'] : '---') }}" readonly>
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
                <input class="form-control" type="text" value="{{(isset($sede['sede_endbairro']) ? $sede['sede_endbairro'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">UF</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($sede['sede_siglauf']) ? $sede['sede_siglauf'] : '---') }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-3 col-form-label text-right">Município</label>
            <div class="col-9">
                <input class="form-control" type="text" value="{{(isset($sede['sede_nomemunicipio']) ? $sede['sede_nomemunicipio'] : '---') }}" readonly>
            </div>
        </div>
    </div>
    <div class="col-12 card card-body bg-light">
        <h4>Documento da Outorga</h4>
        <table id="table_emissoras" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Dia início</th>
                <th>Dia fim</th>
                <th>Hora inicio</th>
                <th>Hora fim</th>
            </tr>
            @php
                $horarioFuncionamento = $emissora->horario_funcionamento['item'];
            @endphp
            <tr>
                <th>{{$horarioFuncionamento['dia_inicio']}}</th>
                <th>{{$horarioFuncionamento['dia_fim']}}</th>
                <th>{{$horarioFuncionamento['hora_inicio']}}</th>
                <th>{{$horarioFuncionamento['hora_fim']}}</th>
            </tr>
            </thead>
        </table>
    </div>

</div>