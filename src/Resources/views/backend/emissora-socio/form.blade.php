<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex  align-items-center m-b-10">
                   <div class="ml-auto">
                        <div class="btn-group">
                            <a href="{{route('emissora.socio.index', [$emissoraID])}}" class="btn btn-primary m-r-5">
                                <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                            </a>
                            @if($hasStore || $hasUpdate)
                                <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5 form-group {{$errors->has('categoria_socioID') ? 'has-error' : ''}} ">
        <label for="categoria_socioID">Categoria *</label>
        <select class="form-control select2" id="categoria_socioID" name="categoria_socioID" required>
            <option value="">Selecione</option>
            @foreach($categoria as $key=>$value)
                <option {{isset($data->exists) && $value->categoriaID==$data->categoria_socioID ? 'selected="selected"' : '' }} value="{{$value->categoriaID}}">{{$value->desc_categoria}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5 form-group {{$errors->has('cargoID') ? 'has-error' : ''}} ">
        <label for="cargoID">Cargo</label>
        <select class="form-control select2" id="cargoID" name="cargoID">
            <option value="">Selecione</option>
            @foreach($cargo as $key=>$value)
                <option {{isset($data->exists) && $value->cargoID==$data->cargoID ? 'selected="selected"' : '' }} value="{{$value->cargoID}}">{{$value->desc_cargo}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 form-group {{$errors->has('nome') ? 'has-error' : ''}} ">
        <label for="numero_ato">Nome sócio / dirigente / pessoa jurídica *</label>
        <input type="nome" class="form-control" value="{{old('nome',$data->exists() ? $data->nome : '')}}"
               name="nome"
               id="nome"
               required>
        @if($errors->has('nome'))
            <span class="help-block">{{$errors->first('nome')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('tipo_mandato') ? 'has-error' : ''}} ">
        <label for="tipo_mandato">Tipo de instrumento</label>
        <select class="form-control select2" id="tipo_mandato" name="tipo_mandato">
            <option value="">Selecione</option>
            <option value="INSTRUMENTO PÚBLICO" {{$data->exists() && $data->tipo_mandato=='INSTRUMENTO PÚBLICO' ? 'selected': ''}}>INSTRUMENTO PÚBLICO</option>
            <option value="INSTRUMENTO PARTICULAR" {{$data->exists() && $data->tipo_mandato=='INSTRUMENTO PARTICULAR' ? 'selected': ''}}>INSTRUMENTO PARTICULAR</option>
            <option value="CONTRATO SOCIAL" {{$data->exists() && $data->tipo_mandato=='CONTRATO SOCIAL' ? 'selected': ''}}>CONTRATO SOCIAL</option>
        </select>
    </div>
    <div class="col-md-4 form-group {{$errors->has('nacionalidade') ? 'has-error' : ''}} ">
        <label for="nacionalidade">Nacionalidade *</label>
        <select class="form-control select2" id="nacionalidade" name="nacionalidade" required>
            <option value="">Selecione</option>
            <option value="BRASILEIRO NATO" {{$data->exists() && $data->nacionalidade=='BRASILEIRO NATO' ? 'selected' : ''}}>BRASILEIRO NATO</option>
            <option value="BRASILEIRO NATURALIZADO" {{$data->exists() && $data->nacionalidade=='BRASILEIRO NATURALIZADO' ? 'selected' : ''}}>BRASILEIRO NATURALIZADO</option>
        </select>
    </div>
    <div class="col-md-4 form-group {{$errors->has('cpf') ? 'has-error' : ''}} ">
        <label for="cpf">CPF / CNPJ *</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('cpf',$data->exists() ? $data->cpf : '')}}"
               name="cpf"
               id="cpf" required>
        @if($errors->has('cpf'))
            <span class="help-block">{{$errors->first('cpf')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('identidade') ? 'has-error' : ''}} ">
        <label for="identidade">RG *</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('identidade',$data->exists() ? $data->identidade : '')}}"
               name="identidade"
               id="identidade" required>
        @if($errors->has('identidade'))
            <span class="help-block">{{$errors->first('identidade')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('orgao_exp') ? 'has-error' : ''}} ">
        <label for="orgao_exp">Orgão expedidor</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('orgao_exp',$data->exists() ? $data->orgao_exp : '')}}"
               name="orgao_exp"
               id="orgao_exp">
        @if($errors->has('orgao_exp'))
            <span class="help-block">{{$errors->first('orgao_exp')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('data_expedicao') ? 'has-error' : ''}} ">
        <label for="data_expedicao">Data de expedição</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('data_expedicao',$data->exists() ? $data->data_expedicao : '')}}"
               name="data_expedicao"
               id="data_expedicao">
        @if($errors->has('data_expedicao'))
            <span class="help-block">{{$errors->first('data_expedicao')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('estado_civilID') ? 'has-error' : ''}} ">
        <label for="estado_civilID">Estado civil</label>
        <select class="form-control select2" id="estado_civilID" name="estado_civilID" required>
            <option value="">Selecione</option>
            @foreach($estadoCivil as $key=>$value)
                <option {{isset($data->exists) && $value->estado_civilID==$data->estado_civilID ? 'selected="selected"' : '' }} value="{{$value->estado_civilID}}">{{$value->desc_estado_civil}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('profissao') ? 'has-error' : ''}} ">
        <label for="profissao">Profissão</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('profissao',$data->exists() ? $data->profissao : '')}}"
               name="profissao"
               id="profissao">
        @if($errors->has('profissao'))
            <span class="help-block">{{$errors->first('profissao')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('valor_cotas') ? 'has-error' : ''}} ">
        <label for="valor_cotas">Cotas / Ações</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('valor_cotas',$data->exists() ? $data->valor_cotas : '')}}"
               name="valor_cotas"
               id="valor_cotas">
        @if($errors->has('valor_cotas'))
            <span class="help-block">{{$errors->first('valor_cotas')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('onominativa') ? 'has-error' : ''}} ">
        <label for="onominativa">O. Nominativa</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('onominativa',$data->exists() ? $data->onominativa : '')}}"
               name="onominativa"
               id="onominativa">
        @if($errors->has('onominativa'))
            <span class="help-block">{{$errors->first('onominativa')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('pnominativa') ? 'has-error' : ''}} ">
        <label for="pnominativa">P. Nominativa</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('pnominativa',$data->exists() ? $data->pnominativa : '')}}"
               name="pnominativa"
               id="pnominativa">
        @if($errors->has('pnominativa'))
            <span class="help-block">{{$errors->first('pnominativa')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('percentual') ? 'has-error' : ''}} ">
        <label for="percentual">Percentual</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('percentual',$data->exists() ? $data->percentual : '')}}"
               name="percentual"
               id="percentual">
        @if($errors->has('percentual'))
            <span class="help-block">{{$errors->first('percentual')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('moeda_cotas') ? 'has-error' : ''}} ">
        <label for="moeda_cotas">Moeda</label>
        <select class="form-control select2" id="moeda_cotas" name="moeda_cotas" required>
            <option value="">Selecione</option>
            <option value="Cr$" {{$data->exists() && $data->moeda_cotas=='Cr$' ?? 'selected'}}>Cr$</option>
            <option value="NCr$" {{$data->exists() && $data->moeda_cotas=='NCr$' ?? 'selected'}}>NCr$</option>
            <option value="Cz$" {{$data->exists() && $data->moeda_cotas=='Cz$' ?? 'selected'}}>Cz$</option>
            <option value="NCz$" {{$data->exists() && $data->moeda_cotas=='NCz$' ?? 'selected'}}>NCz$</option>
            <option value="CR$" {{$data->exists() && $data->moeda_cotas=='CR$' ?? 'selected'}}>CR$</option>
            <option value="R$" {{$data->exists() && $data->moeda_cotas=='R$' ?? 'selected'}}>R$</option>
        </select>
    </div>
    <div class="col-md-2 form-group {{$errors->has('valor_cotas') ? 'has-error' : ''}} ">
        <label for="valor_cotas">Valor</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('valor_cotas',$data->exists() ? $data->valor_cotas : '')}}"
               name="valor_cotas"
               id="valor_cotas">
        @if($errors->has('valor_cotas'))
            <span class="help-block">{{$errors->first('valor_cotas')}}</span>
        @endif
    </div>
    <div class="col-md-6 form-group {{$errors->has('tipo_ingressoID') ? 'has-error' : ''}} ">
        <label for="tipo_ingressoID">Tipo de ingresso</label>
        <select class="form-control select2" id="tipo_ingressoID" name="tipo_ingressoID" required>
            <option value="">Selecione</option>
            @foreach($tipoIngresso as $key=>$value)
                <option {{isset($data->exists) && $value->tipo_ingressoID==$data->tipo_ingressoID ? 'selected="selected"' : '' }} value="{{$value->tipo_ingressoID}}">{{$value->desc_tipo_ingresso}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('data_ingresso') ? 'has-error' : ''}} ">
        <label for="data_ingresso">Data do ingresso</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('data_ingresso',$data->exists() ? $data->data_ingresso : '')}}"
               name="data_ingresso"
               id="data_ingresso">
        @if($errors->has('data_ingresso'))
            <span class="help-block">{{$errors->first('data_ingresso')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('numero_alteracao') ? 'has-error' : ''}} ">
        <label for="numero_alteracao">Nº alteração</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('numero_alteracao',$data->exists() ? $data->numero_alteracao : '')}}"
               name="numero_alteracao"
               id="numero_alteracao">
        @if($errors->has('numero_alteracao'))
            <span class="help-block">{{$errors->first('numero_alteracao')}}</span>
        @endif
    </div>
    <div class="col-md-7 form-group {{$errors->has('logradouro') ? 'has-error' : ''}} ">
        <label for="logradouro">Logradouro</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('logradouro',$data->exists() ? $data->logradouro : '')}}"
               name="logradouro"
               id="logradouro">
        @if($errors->has('logradouro'))
            <span class="help-block">{{$errors->first('logradouro')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('numero') ? 'has-error' : ''}} ">
        <label for="numero">Número</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('numero',$data->exists() ? $data->numero : '')}}"
               name="numero"
               id="numero">
        @if($errors->has('numero'))
            <span class="help-block">{{$errors->first('numero')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('complemento') ? 'has-error' : ''}} ">
        <label for="complemento">Complemento</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('complemento',$data->exists() ? $data->complemento : '')}}"
               name="complemento"
               id="complemento">
        @if($errors->has('complemento'))
            <span class="help-block">{{$errors->first('complemento')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('bairro') ? 'has-error' : ''}} ">
        <label for="bairro">Bairro</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('bairro',$data->exists() ? $data->bairro : '')}}"
               name="bairro"
               id="bairro">
        @if($errors->has('bairro'))
            <span class="help-block">{{$errors->first('bairro')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('cep') ? 'has-error' : ''}} ">
        <label for="cep">Cep</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('cep',$data->exists() ? $data->cep : '')}}"
               name="cep"
               id="cep">
        @if($errors->has('cep'))
            <span class="help-block">{{$errors->first('cep')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('ufID') ? 'has-error' : ''}} ">
        <label for="ufID" class="d-block">Uf *</label>
        <select class="form-control select2" id="ufID" name="ufID" required                data-municipioID="{{$data->municipioID}}">
            <option value="">Selecione</option>
            @foreach($uf as $key=>$value)
                <option data-municipioID="{{json_encode($value->municipios)}}"
                        {{isset($data->exists) && (string)$value->ufID===(string)$data->ufID ? 'selected="selected"' : '' }} value="{{$value->ufID}}">{{$value->desc_uf}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group ">
        <label for="municipioID" class="d-block">Município *</label>
        <select class="form-control select2" id="municipioID" name="municipioID" required>

        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('email') ? 'has-error' : ''}} ">
        <label for="email">E-mail</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('email',$data->exists() ? $data->email : '')}}"
               name="email"
               id="email">
        @if($errors->has('email'))
            <span class="help-block">{{$errors->first('email')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('fone') ? 'has-error' : ''}} ">
        <label for="fone">Telefone</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('fone',$data->exists() ? $data->fone : '')}}"
               name="fone"
               id="fone">
        @if($errors->has('fone'))
            <span class="help-block">{{$errors->first('fone')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('fax') ? 'has-error' : ''}} ">
        <label for="fax">Fax</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('fax',$data->exists() ? $data->fax : '')}}"
               name="fax"
               id="fax">
        @if($errors->has('fax'))
            <span class="help-block">{{$errors->first('fax')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('celular') ? 'has-error' : ''}} ">
        <label for="celular">Celular</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('celular',$data->exists() ? $data->celular : '')}}"
               name="celular"
               id="celular">
        @if($errors->has('celular'))
            <span class="help-block">{{$errors->first('celular')}}</span>
        @endif
    </div>
</div>


<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.socio.index', [$emissoraID])}}" class="btn btn-primary m-r-5">
        <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
    </a>
</div>
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/daterangepicker.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #e9ecef;
        }

        .select2-dropdown {
            border: 1px solid #e9ecef;
        }

        .select2-container .select2-selection--single {
            height: 32px;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>
    <script>
        $('#data_ingresso,#data_expedicao').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            locale: {
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                nextText: 'Proximo',
                prevText: 'Anterior',
                //separator: ' - ',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                weekLabel: 'Sem',
                customRangeLabel: 'Período de'
            },
        }).on('hide.daterangepicker', function (ev, picker) {

        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $(".select2").select2({
            width: '100%',
            placeholder: 'Selecione',
            allowClear: true
        });
        $('#ufID').change(function () {
            if (!this.value) return false;
            var municipioID = $(this).attr('data-municipioID');
            var cities = JSON.parse($(this).find(':selected').attr('data-municipioID'));
            $('#municipioID').html('<option value="">Selecione</option>');
            for (var i = 0; i < cities.length; i++) {
                $('#municipioID').append('<option ' + (cities[i]['municipioID'] == municipioID ? 'selected' : '') + ' value="' + cities[i]['municipioID'] + '">' + cities[i]['desc_municipio'] + '</option>')
            }

        });
        $('#ufID').trigger('change');


    </script>
@endsection