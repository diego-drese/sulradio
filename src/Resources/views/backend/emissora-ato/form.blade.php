<div class="row">
    <input type="hidden" name="douId" value="{{isset($douId) ? $douId : ''}}">
    <div class="col-md-6 form-group {{$errors->has('tipo_atoID') ? 'has-error' : ''}} ">
        <label for="tipo_atoID">Tipo *</label>
        <select class="form-control select2" id="tipo_atoID" name="tipo_atoID" required>
            <option value="">Selecione</option>
            @foreach($tipoAto as $key=>$value)
                <option {{old('tipo_atoID', $data->exists() ? $data->tipo_atoID : '') == $value->tipo_atoID ? 'selected="selected"' : ''}} value="{{$value->tipo_atoID}}">{{$value->desc_tipo_ato}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group {{$errors->has('numero_ato') ? 'has-error' : ''}} ">
        <label for="numero_ato">Número do ato</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('numero_ato',$data->exists() ? $data->numero_ato : '')}}"
               name="numero_ato"
               id="numero_ato">
        @if($errors->has('numero_ato'))
            <span class="help-block">{{$errors->first('numero_ato')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('data_ato') ? 'has-error' : ''}} ">
        <label for="data_ato">Data do ato</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('data_ato',$data->exists() ? $data->data_ato : '')}}"
               name="data_ato"
               id="data_ato">
        @if($errors->has('data_ato'))
            <span class="help-block">{{$errors->first('data_ato')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group date {{$errors->has('data_dou') ? 'has-error' : ''}} ">
        <label for="data_dou">Data DOU *</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('data_dou',$data->exists() ? $data->data_dou : '')}}"
               name="data_dou"
               id="data_dou"
               required>
        @if($errors->has('data_dou'))
            <span class="help-block">{{$errors->first('data_dou')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('secao') ? 'has-error' : ''}} ">
        <label for="secao">Seção</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('secao',$data->exists() ? $data->secao : '')}}"
               name="secao"
               id="secao">
        @if($errors->has('secao'))
            <span class="help-block">{{$errors->first('secao')}}</span>
        @endif
    </div>
    <div class="col-md-1 form-group {{$errors->has('pagina') ? 'has-error' : ''}} ">
        <label for="pagina">Página</label>
        <input type="number" class="form-control" value="{{old('pagina',$data->exists() ? $data->pagina : '')}}"
               name="pagina"
               id="pagina">
        @if($errors->has('pagina'))
            <span class="help-block">{{$errors->first('pagina')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('processo') ? 'has-error' : ''}} ">
        <label for="processo">Processo</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('processo',$data->exists() ? $data->processo : '')}}"
               name="processo"
               id="processo">
        @if($errors->has('processo'))
            <span class="help-block">{{$errors->first('processo')}}</span>
        @endif
    </div>
    <div class="col-md-5 form-group {{$errors->has('localidade_sedeID') ? 'has-error' : ''}} ">
        <label for="sedufID" class="d-block">Localidade</label>
        <div class="row">
            <div class="col-5 d-inline-block">
                <select class="form-control select2" id="ufID" name="ufID" data-municipioID="{{$data->municipioID}}">
                    <option value="">Selecione</option>
                    @foreach($uf as $key=>$value)
                        <option data-municipioID="{{json_encode($value->municipios)}}"
                                {{isset($data->exists) && (string)$value->ufID===(string)$data->ufID ? 'selected="selected"' : '' }} value="{{$value->ufID}}">{{$value->desc_uf}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-7 d-inline-block">
                <select class="form-control select2" id="municipioID" name="municipioID">

                </select>
            </div>
        </div>
    </div>
    <div class="col-md-2 form-group {{$errors->has('conferido') ? 'has-error' : ''}} ">
        <label for="conferido">Conferido *</label>
        <select class="form-control select2" id="conferido" name="conferido" required>
            <option value="0" {{isset($data->exists) && $data->conferido==0 ? 'selected': ''}}>Digitado</option>
            <option value="1" {{isset($data->exists) && $data->conferido==1 ? 'selected': ''}}>Conferido</option>
        </select>
    </div>

    <div class="col-md-8 form-group {{$errors->has('categoriaID') ? 'has-error' : ''}} ">
        <label for="categoriaID">Categoria *</label>
        <select class="form-control select2" id="categoriaID" name="categoriaID" required>
            <option value="">Selecione</option>
            @foreach($categoria as $key=>$value)
                <option  {{old('categoriaID', $data->exists() ? $data->categoriaID : '') == $value->categoriaID ? 'selected="selected"' : ''}}  value="{{$value->categoriaID}}">{{$value->desc_categoria}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 form-group {{$errors->has('servicoID') ? 'has-error' : ''}} ">
        <label for="servicoID">Serviço *</label>
        <select class="form-control select2" id="servicoID" name="servicoID" required>
            <option value="">Selecione</option>
            @foreach($servico as $key=>$value)
                <option {{old('servicoID', $data->exists() ? $data->servicoID : '') == $value->servicoID ? 'selected="selected"' : ''}}  value="{{$value->servicoID}}">{{$value->desc_servico}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 form-group {{$errors->has('finalidadeID') ? 'has-error' : ''}} ">
        <label for="finalidadeID">Finalidade *</label>
        <select class="form-control select2" id="finalidadeID" name="finalidadeID" required>
            <option value="">Selecione</option>
            @foreach($finalidade as $key=>$value)
                <option {{old('finalidadeID', $data->exists() ? $data->finalidadeID : '') == $value->finalidadeID ? 'selected="selected"' : ''}}  value="{{$value->finalidadeID}}">{{$value->desc_finalidadeID}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group {{$errors->has('canal_freq') ? 'has-error' : ''}} ">
        <label for="data_dou">Canal/Freq.</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('canal_freq',$data->exists() ? $data->canal_freq : '')}}"
               name="canal_freq"
               id="canal_freq">
        @if($errors->has('canal_freq'))
            <span class="help-block">{{$errors->first('canal_freq')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('classe') ? 'has-error' : ''}} ">
        <label for="classe">Classe</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('classe',$data->exists() ? $data->classe : '')}}"
               name="classe"
               id="classe">
        @if($errors->has('classe'))
            <span class="help-block">{{$errors->first('classe')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('concorrenciaID') ? 'has-error' : ''}} ">
        <label for="ato_ref">Nº. conc.</label>
        <input type="number" class="form-control"
               value="{{old('concorrenciaID',$data->exists() ? $data->concorrenciaID : '')}}"
               name="concorrenciaID"
               id="concorrenciaID">
        @if($errors->has('concorrenciaID'))
            <span class="help-block">{{$errors->first('concorrenciaID')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('ato_outorgaID') ? 'has-error' : ''}} ">
        <label for="ato_ref">Nº. ato out.</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('ato_outorgaID',$data->exists() ? $data->ato_outorgaID : '')}}"
               name="ato_outorgaID"
               id="ato_outorgaID">
        @if($errors->has('ato_outorgaID'))
            <span class="help-block">{{$errors->first('ato_outorgaID')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('tipo_penalidadeID') ? 'has-error' : ''}} ">
        <label for="tipo_penalidadeID">Tipo de penalidade.</label>
        <select class="form-control select2" id="tipo_penalidadeID" name="tipo_penalidadeID">
            <option value="">Selecione</option>
            @foreach($tipoPenalidade as $key=>$value)
                <option {{old('tipo_penalidadeID', $data->exists() ? $data->tipo_penalidadeID : '') == $value->tipo_penalidadeID ? 'selected="selected"' : ''}} value="{{$value->tipo_penalidadeID}}">{{$value->desc_tipo_penalidade}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('referencia_penalidadeID') ? 'has-error' : ''}} ">
        <label for="referencia_penalidadeID">Referencial</label>
        <select class="form-control select2" id="referencia_penalidadeID" name="referencia_penalidadeID">
            <option value="">Selecione</option>
            @foreach($referenciaPenalidade as $key=>$value)
                <option {{old('referencia_penalidadeID', $data->exists() ? $data->referencia_penalidadeID : '') == $value->referencia_penalidadeID ? 'selected="selected"' : ''}} value="{{$value->referencia_penalidadeID}}">{{$value->desc_ref_penalidadeID}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('valor_penalidade') ? 'has-error' : ''}} ">
        <label for="ato_ref">Valor</label>
        <input type="text" class="form-control money"
               value="{{old('valor_penalidade',$data->exists() ? $data->valor_penalidade : '')}}"
               name="valor_penalidade"
               id="valor_penalidade">
        @if($errors->has('processo_renovacao'))
            <span class="help-block">{{$errors->first('processo_renovacao')}}</span>
        @endif
    </div>

    <div class="col-md-12 form-group {{$errors->has('observacao') ? 'has-error' : ''}} ">
        <label for="observacao">Observações</label>
        <textarea rows="7" name="observacao" class="form-control"
                  id="observacao">{{$data->exists() && $data->observacao  ? $data->observacao : ''}}</textarea>
    </div>
    <div class="col-md-12 form-group {{$errors->has('valor_penalidade') ? 'has-error' : ''}} ">
        <label for="ato_url">Url</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                @if(old('ato_url', $data->exists() && filter_var($data->ato_url, FILTER_VALIDATE_URL) ? $data->ato_url : '#') != '#')
                    <button class="btn btn-success" onclick="copyUrl()" type="button" id="execCopy">Copiar</button>
                    <input type="text" style="display: none" id="copy" value="{{old('ato_url', $data->exists() && filter_var($data->ato_url, FILTER_VALIDATE_URL) ? $data->ato_url : '#')}}">
                @endif
            </div>
            <input type="text" class="form-control"
                   value="{{old('ato_url',$data->exists() ? $data->ato_url : '')}}"
                   name="ato_url"
                   id="ato_url">
            <div class="input-group-append">
                @if(old('ato_url', $data->exists() && filter_var($data->ato_url, FILTER_VALIDATE_URL) ? $data->ato_url : '#') != '#')
                    <a href="{{old('ato_url', $data->exists() && filter_var($data->ato_url, FILTER_VALIDATE_URL) ? $data->ato_url : '#')}}" target="_blank" class="btn btn-info" type="button">Navegar</a>
                @endif

            </div>
        </div>
        @if($errors->has('ato_url'))
            <span class="help-block">{{$errors->first('ato_url')}}</span>
        @endif
    </div>
    @if(count($usersClients))
        <div class="col-md-12 form-group">
            <label for="observacao">Notificaçoes</label>
            <table class="table table-striped table-bordered dataTable no-footer">
                <tr>
                    <th>Email</th>
                    <th>Nome</th>
                    <th width="120px">Recebe Notificações</th>
                    <th>Status Envio</th>
                    <th>Data Envio</th>
                </tr>
                @foreach($usersClients as $user)
                   <tr>
                       <td>{{$user->email}}</td>
                       <td>{{$user->name." ".$user->lastname}}</td>
                       <td>{!! $user->receive_notification? '<span class="badge badge-success mr-1 ">SIM</span>': '<span class="badge badge-danger mr-1 ">NÃO</span>' !!}</td>
                       <td>{{$user->status_send??'----' }}</td>
                       <td>{{$user->date_sent??'----' }}</td>
                   </tr>
                @endforeach
            </table>
    </div>
    @endif
</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.atos.oficiais.index', [$emissoraID])}}"
       class="btn btn-primary m-r-5">
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
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/forms.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>
    <script>
        $('.money').mask('#.00', {reverse: true});
        $('#data_ato, #data_dou,#data_renovacao, #data_ato_outorga,#data_dou_outorga,#data_ato_renovacao,#data_dou_renovacao,#data_inicio_renovacao').daterangepicker({
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

        function copyUrl(){
            var copyText = document.getElementById("ato_url");
            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            toastr.success(copyText.value, 'Copiado')
        }
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