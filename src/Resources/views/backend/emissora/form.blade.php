<div class="row">
    <div class="col-md-4 form-group {{$errors->has('type_user') ? 'has-error' : ''}} ">
        <label for="IdStatusSead">Status SEAD * </label>
        <select class="form-control select2" id="status_seadID" name="status_seadID" required>
            <option value="">Selecione</option>
            @foreach($statusSead as $key=>$value)
                <option {{isset($data->exists) && (string)$value->status_seadID===(string)$data->status_seadID ? 'selected="selected"' : '' }} value="{{$value->status_seadID}}">{{$value->desc_status_sead}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8 form-group text-right">
        <label for="">&nbsp;</label><br/>
        <a  href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
            <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
        </a>
        @if($hasStore || $hasUpdate)
            <button type="submit" class="btn btn-success">Salvar</button>
        @endif

    </div>
    <div class="col-md-8 form-group {{$errors->has('razao_social') ? 'has-error' : ''}} ">
        <label for="razao_social">Razão social *</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('razao_social',$data->exists() ? $data->razao_social : '')}}"
               name="razao_social"
               id="razao_social"
               required>
        @if($errors->has('razao_social'))
            <span class="help-block">{{$errors->first('razao_social')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('servicoID') ? 'has-error' : ''}} ">
        <label for="servicoID">Serviço *</label>
        <select class="form-control select2" id="servicoID" name="servicoID" required>
            <option value="">Selecione</option>
            @foreach($servico as $key=>$value)
                <option {{isset($data->exists) && (string)$value->servicoID===(string)$data->servicoID ? 'selected="selected"' : '' }} value="{{$value->servicoID}}">{{$value->desc_servico}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group {{$errors->has('tipo_emissoraID') ? 'has-error' : ''}} ">
        <label for="tipo_emissoraID">Tipo *</label>
        <select class="form-control select2" id="tipo_emissoraID" name="tipo_emissoraID" required>
            <option value="">Selecione</option>
            @foreach($tipoEmissora as $key=>$value)
                <option {{isset($data->exists) && (string)$value->tipo_emissoraID===(string)$data->tipo_emissoraID ? 'selected="selected"' : '' }} value="{{$value->tipo_emissoraID}}">{{$value->desc_tipo_emissora}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 form-group {{$errors->has('nome_fantasia') ? 'has-error' : ''}} ">
        <label for="nome_fantasia">Nome Fantasia</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('nome_fantasia',$data->exists() ? $data->nome_fantasia : '')}}"
               name="nome_fantasia"
               id="nome_fantasia">
        @if($errors->has('nome_fantasia'))
            <span class="help-block">{{$errors->first('nome_fantasia')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('indicativo_chamada') ? 'has-error' : ''}} ">
        <label for="nome_fantasia">Indicativo Chamada</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('indicativo_chamada',$data->exists() ? $data->indicativo_chamada : '')}}"
               name="indicativo_chamada"
               id="indicativo_chamada">
        @if($errors->has('indicativo_chamada'))
            <span class="help-block">{{$errors->first('indicativo_chamada')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('faixa_fronteira') ? 'has-error' : ''}} ">
        <label for="faixa_fronteira">Faixa Fronteira</label>
        <select class="form-control select2" id="faixa_fronteira" name="faixa_fronteira">
            <option value="">Selecione</option>
            <option value="NÃO" {{isset($data->exists) && 'NÃO'===(string)$data->faixa_fronteira ? 'selected="selected"' : '' }}>
                NÃO
            </option>
            <option value="SIM" {{isset($data->exists) && 'SIM'===(string)$data->faixa_fronteira ? 'selected="selected"' : '' }}>
                SIM
            </option>
        </select>
    </div>

    <div class="col-md-4 form-group {{$errors->has('ufID') ? 'has-error' : ''}} ">
        <label for="ufID" class="d-block">Localidade da outorga *</label>
        <div class="row">
            <div class="col-5 d-inline-block">
                <select class="form-control select2" id="ufID" name="ufID" required
                        data-municipioID="{{$data->municipioID}}">
                    <option value="">Selecione</option>
                    @foreach($uf as $key=>$value)
                        <option data-municipioID="{{json_encode($value->municipios)}}"
                                {{isset($data->exists) && (string)$value->ufID===(string)$data->ufID ? 'selected="selected"' : '' }} value="{{$value->ufID}}">{{$value->desc_uf}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-7 d-inline-block">
                <select class="form-control select2" id="municipioID" name="municipioID" required>

                </select>
            </div>
        </div>
    </div>
    <div class="col-md-1 form-group {{$errors->has('canal') ? 'has-error' : ''}} ">
        <label for="canal">Canal</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('canal',$data->exists() ? $data->canal : '')}}"
               name="canal"
               id="canal">
        @if($errors->has('canal'))
            <span class="help-block">{{$errors->first('canal')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('frequencia') ? 'has-error' : ''}} ">
        <label for="frequencia">Freq.</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('frequencia',$data->exists() ? $data->frequencia : '')}}"
               name="frequencia"
               id="frequencia">
        @if($errors->has('frequencia'))
            <span class="help-block">{{$errors->first('frequencia')}}</span>
        @endif
    </div>

    <div class="col-md-1 form-group {{$errors->has('classe') ? 'has-error' : ''}} ">
        <label for="classe">Classe</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('classe',$data->exists() ? $data->classe : '')}}"
               name="classe"
               id="classe">
        @if($errors->has('classe'))
            <span class="help-block">{{$errors->first('classe')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('potencia') ? 'has-error' : ''}} ">
        <label for="potencia">Potência Dia/Noite(KW)</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('potencia',$data->exists() ? $data->classe : '')}}"
               name="potencia"
               id="potencia">
        @if($errors->has('potencia'))
            <span class="help-block">{{$errors->first('potencia')}}</span>
        @endif
    </div>



    <div class="col-md-4 form-group {{$errors->has('cnpj') ? 'has-error' : ''}} ">
        <label for="classe">CNPJ</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('cnpj',$data->exists() ? $data->cnpj : '')}}"
               name="cnpj"
               id="cnpj">
        @if($errors->has('cnpj'))
            <span class="help-block">{{$errors->first('cnpj')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('inscricao_estadual') ? 'has-error' : ''}} ">
        <label for="inscricao_estadual">Inscrição estadual</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('inscricao_estadual',$data->exists() ? $data->inscricao_estadual : '')}}"
               name="inscricao_estadual"
               id="inscricao_estadual">
        @if($errors->has('inscricao_estadual'))
            <span class="help-block">{{$errors->first('inscricao_estadual')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('nire') ? 'has-error' : ''}} ">
        <label for="nire">Nire</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('nire',$data->exists() ? $data->nire : '')}}"
               name="nire"
               id="nire">
        @if($errors->has('nire'))
            <span class="help-block">{{$errors->first('nire')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('tipo_repres_socialID') ? 'has-error' : ''}} ">
        <label for="tipo_repres_socialID">Representação social</label>
        <select class="form-control select2" id="tipo_repres_socialID" name="tipo_repres_socialID">
            <option value="">Selecione</option>
            @foreach($tipoRepresetanteSocial as $key=>$value)
                <option {{isset($data->exists) && (string)$value->tipo_repres_socialID===(string)$data->tipo_repres_socialID ? 'selected="selected"' : '' }} value="{{$value->tipo_repres_socialID}}">{{$value->desc_tipo_repres_social}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 form-group {{$errors->has('endereco_sede') ? 'has-error' : ''}} ">
        <label for="endereco_sede">Endereço - Sede </label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('endereco_sede',$data->exists() ? $data->endereco_sede : '')}}"
               name="endereco_sede"
               id="endereco_sede">
        @if($errors->has('endereco_sede'))
            <span class="help-block">{{$errors->first('endereco_sede')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('bairro_sede') ? 'has-error' : ''}} ">
        <label for="bairro_sede">Bairro</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('bairro_sede',$data->exists() ? $data->bairro_sede : '')}}"
               name="bairro_sede"
               id="bairro_sede">
        @if($errors->has('bairro_sede'))
            <span class="help-block">{{$errors->first('bairro_sede')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('cep_sede') ? 'has-error' : ''}} ">
        <label for="cep_sede">Cep</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('cep_sede',$data->exists() ? $data->cep_sede : '')}}"
               name="cep_sede"
               id="cep_sede">
        @if($errors->has('cep_sede'))
            <span class="help-block">{{$errors->first('cep_sede')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('localidade_sedeID') ? 'has-error' : ''}} ">
        <label for="sedufID" class="d-block">Localidade</label>
        <div class="row">
            <div class="col-5 d-inline-block">
                <select class="form-control select2" id="sedufID" name="sedufID"
                        data-municipioID="{{$data->localidade_sedeID}}">
                    <option value="">Selecione</option>
                    @foreach($uf as $key=>$value)
                        <option data-municipioID="{{json_encode($value->municipios)}}"
                                {{isset($data->exists) && (string)$value->ufID===(string)$data->sedufID ? 'selected="selected"' : '' }} value="{{$value->ufID}}">{{$value->desc_uf}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-7 d-inline-block">
                <select class="form-control select2" id="localidade_sedeID" name="localidade_sedeID">

                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 form-group {{$errors->has('observacao') ? 'has-error' : ''}} ">
        <label for="observacao">Observações</label>
        <textarea rows="7" name="observacao" class="form-control"
                  id="observacao">{{$data->exists() && $data->observacao  ? $data->observacao : ''}}</textarea>
    </div>
    <div class="col-md-12 form-group {{$errors->has('informacao_renovacao') ? 'has-error' : ''}} ">
        <label for="informacao_renovacao">Informações sobre renovação</label>
        <textarea rows="7" name="informacao_renovacao" class="form-control"
                  id="informacao_renovacao">{{$data->exists() && $data->informacao_renovacao  ? $data->informacao_renovacao : ''}}</textarea>
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
        <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
    </a>
</div>
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
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
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/forms.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        $('#cnpj').mask('00.000.000/0000-00', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
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

        $('#sedufID').change(function () {
            if (!this.value) return false;
            var municipioID = $(this).attr('data-municipioID');
            var cities = JSON.parse($(this).find(':selected').attr('data-municipioID'));
            $('#localidade_sedeID').html('<option value="">Selecione</option>');
            for (var i = 0; i < cities.length; i++) {
                $('#localidade_sedeID').append('<option ' + (cities[i]['municipioID'] == municipioID ? 'selected' : '') + ' value="' + cities[i]['municipioID'] + '">' + cities[i]['desc_municipio'] + '</option>')
            }

        });
        $('#sedufID').trigger('change')
    </script>
@endsection