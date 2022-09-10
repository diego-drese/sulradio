

<div class="row">
    <div class="col-md-12 form-group text-right">
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
    <div class="col-md-4 form-group {{$errors->has('cnpj') ? 'has-error' : ''}} ">
        <label for="classe">CNPJ</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('cnpj',$data->exists() ? $data->cnpj : '')}}"
               name="cnpj"
               id="cnpj">
        @if($errors->has('cnpj'))
            <span class="help-block">{{$errors->first('cnpj')}}</span>
        @endif
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
    <div class="col-md-2 form-group {{$errors->has('servicoID') ? 'has-error' : ''}} ">
        <label for="servicoID">Serviço *</label>
        <select class="form-control select2" id="servicoID" name="servicoID" required>
            <option value="">Selecione</option>
            @foreach($servico as $key=>$value)
                <option {{isset($data->exists) && (string)$value->servicoID===(string)$data->servicoID ? 'selected="selected"' : '' }} value="{{$value->servicoID}}">{{$value->desc_servico}}</option>
            @endforeach
        </select>
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
    <div class="col-md-4 form-group {{$errors->has('fistel') ? 'has-error' : ''}} ">
        <label for="fistel">Fistel</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('fistel',$data->exists() ? $data->fistel : '')}}"
               name="fistel"
               id="fistel"
               required>
        @if($errors->has('fistel'))
            <span class="help-block">{{$errors->first('fistel')}}</span>
        @endif
    </div>
    <div class="col-md-12 form-group {{$errors->has('url_mosaico') ? 'has-error' : ''}} ">
        <label for="url_mosaico">Url Consulta MOSAICO</label>
        <div class="input-group mb-3">
            <button class="btn btn-success" onclick="copyUrl('url_mosaico')" type="button" id="execCopy">Copiar</button>
            <input type="text" autocomplete="off" class="form-control"
                   value="{{old('url_mosaico',$data->exists() ? $data->url_mosaico : '')}}"
                   name="url_mosaico"
                   id="url_mosaico"
                   placeholder="Preencha o Fistel"
                   readonly>
            <a href="{{old('url_mosaico',$data->exists() ? $data->url_mosaico : '')}}" id="url_mosaico_link" target="_blank" class="btn btn-info" type="button">Navegar</a>
        </div>
    </div>
    <div class="col-md-12 form-group {{$errors->has('url_seacco') ? 'has-error' : ''}} ">
        <label for="url_seacco">Url Consulta SIACCO</label>
        <div class="input-group mb-3">
            <button class="btn btn-success" onclick="copyUrl('url_seacco')" type="button" id="execCopy">Copiar</button>
            <input type="text" autocomplete="off" class="form-control"
                   value="{{old('url_seacco',$data->exists() ? $data->url_seacco : '')}}"
                   name="url_seacco"
                   id="url_seacco"
                   placeholder="Preencha o CNPJ"
                   readonly>
            <a href="{{old('url_seacco',$data->exists() ? $data->url_seacco : '')}}" id="url_seacco_link" target="_blank" class="btn btn-info" type="button">Navegar</a>
        </div>
    </div>

    <div class="col-md-12 form-group {{$errors->has('url_cnpj') ? 'has-error' : ''}} ">
        <label for="url_cnpj">Url Consulta CNPJ</label>
        <div class="input-group mb-3">
            <button class="btn btn-success" onclick="copyUrl('url_cnpj')" type="button" id="execCopy">Copiar</button>
            <input type="text" autocomplete="off" class="form-control"
                   value="{{old('url_cnpj',$data->exists() ? $data->url_cnpj : '')}}"
                   name="url_cnpj"
                   id="url_cnpj"
                   placeholder="Preencha o CNPJ"
                   readonly>
            <a href="{{old('url_cnpj',$data->exists() ? $data->url_cnpj : '')}}" id="url_cnpj_link" target="_blank" class="btn btn-info" type="button">Navegar</a>
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
         .dataTables_filter{display: none}
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/forms.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>

        var urlMosaico = '{{\Illuminate\Support\Facades\Config::get('sulradio.url_mosaico')}}';
        var urlSeacco = '{{\Illuminate\Support\Facades\Config::get('sulradio.url_seacco')}}';
        var urlCNPJ = '{{\Illuminate\Support\Facades\Config::get('sulradio.url_cnpj')}}';
        var urlGetByFistel = '{{ route('estacao.rd.fistel', [':fistel']) }}';

        $('#cnpj').mask('00.000.000/0000-00', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
        });
        function cnpjValidation(value) {
            if (!value) return false

            // Aceita receber o valor como string, número ou array com todos os dígitos
            const isString = typeof value === 'string'
            const validTypes = isString || Number.isInteger(value) || Array.isArray(value)

            // Elimina valor em formato inválido
            if (!validTypes) return false

            // Filtro inicial para entradas do tipo string
            if (isString) {
                // Limita ao máximo de 18 caracteres, para CNPJ formatado
                if (value.length > 18) return false

                // Teste Regex para veificar se é uma string apenas dígitos válida
                const digitsOnly = /^\d{14}$/.test(value)
                // Teste Regex para verificar se é uma string formatada válida
                const validFormat = /^\d{2}.\d{3}.\d{3}\/\d{4}-\d{2}$/.test(value)

                // Se o formato é válido, usa um truque para seguir o fluxo da validação
                if (digitsOnly || validFormat) true
                // Se não, retorna inválido
                else return false
            }

            // Guarda um array com todos os dígitos do valor
            const match = value.toString().match(/\d/g)
            const numbers = Array.isArray(match) ? match.map(Number) : []

            // Valida a quantidade de dígitos
            if (numbers.length !== 14) return false

            // Elimina inválidos com todos os dígitos iguais
            const items = [...new Set(numbers)]
            if (items.length === 1) return false

            // Cálculo validador
            const calc = (x) => {
                const slice = numbers.slice(0, x)
                let factor = x - 7
                let sum = 0

                for (let i = x; i >= 1; i--) {
                    const n = slice[x - i]
                    sum += n * factor--
                    if (factor < 2) factor = 9
                }

                const result = 11 - (sum % 11)

                return result > 9 ? 0 : result
            }

            // Separa os 2 últimos dígitos de verificadores
            const digits = numbers.slice(12)

            // Valida 1o. dígito verificador
            const digit0 = calc(12)
            if (digit0 !== digits[0]) return false

            // Valida 2o. dígito verificador
            const digit1 = calc(13)
            return digit1 === digits[1]
        }

        $('#cnpj').blur(function (){
            if(!cnpjValidation(this.value)){
                toastr.error('Verrifique o cnpj digitado', 'CNPJ Inválido');
                $('#cnpj').val('');
                $('#url_seacco').val('');
                $('#url_cnpj').val('');
                return;
            }
            var cnpj = $('#cnpj').val();
            var cnpjNumbers = cnpj.replace(/\D/g, '');
            var urlSeaccoParsed = urlSeacco.replace('{NOME}', $('#razao_social').val()).replace('{CNPJ}', cnpjNumbers)
            $('#url_seacco').val(urlSeaccoParsed);
            $('#url_seacco_link').attr('href', urlSeaccoParsed);
            var urlCnpjParsed = urlCNPJ.replace('{CNPJ}', $('#cnpj').val());
            $('#url_cnpj').val(urlCnpjParsed);
            $('#url_cnpj_link').attr('href', urlCnpjParsed);

        })
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

        $('#fistel').blur(function(){
            var fistel = this.value;
            $.ajax({
                url: urlGetByFistel.replace(':fistel', fistel),
                type: "get",
                dataType: 'json',
                data: {},
                beforeSend: function () {

                },
                success: function (data) {
                    if(data.id){
                        var urlMosaicoParsed = urlMosaico.replace('{ID}',data.id).replace('{STATE}', data.state);
                        $('#url_mosaico').val(urlMosaicoParsed);
                        $('#url_mosaico_link').attr('href', urlMosaicoParsed);
                        toastr.success('Dados carregados com sucesso', 'Identificador');
                        return false;
                    }
                    toastr.warning('Não foi encontados dados para esse fistel', 'Identificador');
                    $('#url_mosaico').val('');
                    $('#url_mosaico_link').attr('href', '');
                },
                error: function (erro) {
                    toastr.error(erro.responseJSON.message, 'Erro');
                }
            });
        });

        $('#sedufID').change(function () {
            if (!this.value) return false;
            var municipioID = $(this).attr('data-municipioID');
            var cities = JSON.parse($(this).find(':selected').attr('data-municipioID'));
            $('#localidade_sedeID').html('<option value="">Selecione</option>');
            for (var i = 0; i < cities.length; i++) {
                $('#localidade_sedeID').append('<option ' + (cities[i]['municipioID'] == municipioID ? 'selected' : '') + ' value="' + cities[i]['municipioID'] + '">' + cities[i]['desc_municipio'] + '</option>')
            }

        });
        $('#sedufID').trigger('change');
        @if($data->emissoraID)
            var languageDatatable = {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            };
            var hasEditContato = '{{$hasEditContato}}';
            var hasUpdateContato = '{{$hasUpdateContato}}';

            var tableContato = $('#tableContato').DataTable({
                language: languageDatatable,
                serverSide: true,
                processing: true,
                autoWidth: false,
                orderCellsTop: true,
                stateSave: true,
                stateLoaded: function (settings, data) {
                    setTimeout(function () {
                        var dataExtra = settings.ajax.data({});
                        var searchCols = settings.aoPreSearchCols;
                        if (searchCols && searchCols.length) {
                            for (var i = 0; i < searchCols.length; i++) {
                                $('#table_user thead tr:eq(1) th:eq(' + i + ') .fieldSearch').val(searchCols[i]['sSearch']);
                            }
                        }
                        console.log(settings.aoPreSearchCols, data);
                    }, 50);
                },
                ajax: {
                    url: '{{ route('emissora.contato.index', [$data->client_id??-1, 'emissora_id'=>$data->emissoraID]) }}',
                    type: 'GET',
                    data: function (d) {
                        d._token = $("input[name='_token']").val();
                        return d;
                    }
                },
                columns: [
                    {
                        data: null, searchable: false, orderable: false, render: function (data) {
                            var edit_button = "";
                            if(hasEditContato){
                                edit_button += '<a href="' + data.edit_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Editar</b></a>';
                            }


                            return edit_button
                        }
                    },

                    {data: "name", 'name': 'name'},
                    {data: "lastname", 'name': 'lastname'},
                    {data: "active", 'name': 'active', orderable: false,
                        render: function (data, display, row) {
                            if (data == "1") {
                                return '<span class="badge badge-success mr-1 ">SIM</span>';
                            } else if (data == '0') {
                                return '<span class="badge badge-danger mr-1 ">NÃO</span>';
                            }
                            return '---';
                        }
                    },
                    {data: "receive_notification", 'name': 'receive_notification', orderable: false,
                        render: function (data, display, row) {
                            if (data == "1") {
                                return '<span class="badge badge-success mr-1">SIM</span>';
                            } else if (data == '0') {
                                return '<span class="badge badge-danger mr-1">NÃO</span>';
                            }
                            return '---';
                        }
                    },
                    {data: "email", 'name': 'email'},
                    {data: "cell_phone", 'name': 'cell_phone'},
                    {data: "function_name", 'name': 'function_name',  searchable: false, orderable: false,},
                ]
            });


        $('#tableContato thead tr:eq(1) th').each(function (i) {
            $('.fieldSearch', this).on('keyup change', function () {
                if (tableContato.column(i).search() !== this.value) {
                    tableContato.column(i).search(this.value, true).draw();
                }
            });
        });

        function copyUrl(id){
            var copyText = document.getElementById(id);
            /* Select the text field */
            copyText.value;
            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            toastr.success(copyText.value, 'Copiado')
        }
        $('#clearFilterContato').click(function () {
            tableContato.state.clear();
        })
        @endif
    </script>
@endsection