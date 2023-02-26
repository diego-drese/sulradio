<div class="row">
    <div class="col-md-6 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="name">Nome completo*</label>
        <div class="input-group mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome" required
            value="{{old('name', $data->exists() ? $data->name : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
            </div>
        </div>
        @if($errors->has('name'))
            <span class="help-block">{{$errors->first('name')}}</span>
        @endif
    </div>
    <div class="col-md-6 form-group {{$errors->has('company_name') ? 'has-error' : ''}} ">
        <label for="company_name">Nome da empresa </label>
        <div class="input-group mb-3">
            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Empresa"
                   value="{{old('company_name', $data->exists() ? $data->company_name : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
            </div>
        </div>
        @if($errors->has('company_name'))
            <span class="help-block">{{$errors->first('company_name')}}</span>
        @endif
    </div>

    <div class="col-md-8 form-group {{$errors->has('email') ? 'has-error' : ''}} ">
        <label for="email">Email </label>
        <div class="input-group mb-3">
            <input type="text" name="email" id="email" class="form-control" placeholder="Email"
                   value="{{old('email', $data->exists() ? $data->email : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-gmail"></i> </span>
            </div>
        </div>
        @if($errors->has('email'))
            <span class="help-block">{{$errors->first('email')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('document_type') ? 'has-error' : ''}} ">
        <label for="document_type">Tipo de documento</label>
        <div class="input-group mb-3">
            <select name="document_type" id="document_type" class="form-control" >
                <option value=""> Selecione </option>
                <option value="Rg" {{old('document_type', $data->exists() ? $data->document_type : '') == "Rg" ? 'selected' : ''}}> Rg </option>
                <option value="CPF" {{old('document_type', $data->exists() ? $data->document_type : '') == "CPF" ? 'selected' : ''}}> CPF </option>
                <option value="CNPJ" {{old('document_type', $data->exists() ? $data->document_type : '') == "CNPJ" ? 'selected' : ''}}> CNPJ </option>
                <option value="Passaporte" {{old('document_type', $data->exists() ? $data->document_type : '') == "Passaporte" ? 'selected' : ''}}> Passaporte </option>
                <option value="CNH" {{old('document_type', $data->exists() ? $data->document_type : '') == "CNH" ? 'selected' : ''}}> CNH </option>
            </select>
            <div class="input-group-append">
                <span class="input-group-text "><i class="fas fa-address-card"></i></span>
            </div>
        </div>
        @if($errors->has('document_type'))
            <span class="help-block">{{$errors->first('document_type')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('document') ? 'has-error' : ''}} ">
        <label for="document">Documento </label>
        <div class="input-group mb-3">
            <input type="text" name="document" id="document" class="form-control" placeholder="Documento"
                   value="{{old('document', $data->exists() ? $data->document : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-card"></i></span>
            </div>
        </div>
        @if($errors->has('document'))
            <span class="help-block">{{$errors->first('document')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('cellphone') ? 'has-error' : ''}} ">
        <label for="cellphone">Celular</label>
        <div class="input-group mb-3">
            <input type="text" name="cellphone" id="cellphone" class="form-control" placeholder="Celular"
                   value="{{old('cellphone', $data->exists() ? $data->cellphone : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-cellphone"></i></span>
            </div>
        </div>
        @if($errors->has('cellphone'))
            <span class="help-block">{{$errors->first('cellphone')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('telephone') ? 'has-error' : ''}} ">
        <label for="telephone">Telefone</label>
        <div class="input-group mb-3">
            <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Telefone fixo"
                   value="{{old('telephone', $data->exists() ? $data->telephone : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
        </div>
        @if($errors->has('telephone'))
            <span class="help-block">{{$errors->first('telephone')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('zip_code') ? 'has-error' : ''}} ">
        <label for="zip_code">Cep </label>
        <div class="input-group mb-3">
            <input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="Máximo de emissoras"
                   value="{{old('zip_code', $data->exists() ? $data->zip_code : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('zip_code'))
            <span class="help-block">{{$errors->first('zip_code')}}</span>
        @endif
    </div>

    <div class="col-md-6 form-group {{$errors->has('street') ? 'has-error' : ''}} ">
        <label for="street">Rua</label>
        <div class="input-group mb-3">
            <input type="text" name="street" id="street" class="form-control" placeholder="Rua, Avenida, alameda..."
                   value="{{old('street', $data->exists() ? $data->street : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('street'))
            <span class="help-block">{{$errors->first('street')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('neighborhood') ? 'has-error' : ''}} ">
        <label for="neighborhood">Bairro</label>
        <div class="input-group mb-3">
            <input type="text" name="neighborhood" id="neighborhood" class="form-control" placeholder="Bairro"
                   value="{{old('neighborhood', $data->exists() ? $data->neighborhood : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('neighborhood'))
            <span class="help-block">{{$errors->first('neighborhood')}}</span>
        @endif
    </div>

    <div class="col-md-2 form-group {{$errors->has('address_number') ? 'has-error' : ''}} ">
        <label for="address_number">Número</label>
        <div class="input-group mb-3">
            <input type="text" name="address_number" id="address_number" class="form-control" placeholder="Número"
                   value="{{old('address_number', $data->exists() ? $data->address_number : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('address_number'))
            <span class="help-block">{{$errors->first('address_number')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('complement') ? 'has-error' : ''}} ">
        <label for="complement">Complemento</label>
        <div class="input-group mb-3">
            <input type="text" name="complement" id="complement" class="form-control" placeholder="Complemento"
                   value="{{old('complement', $data->exists() ? $data->complement : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('complement'))
            <span class="help-block">{{$errors->first('complement')}}</span>
        @endif
    </div>

    <div class="col-md-6 form-group {{$errors->has('city_id') ? 'has-error' : ''}} ">
        <label for="city_id">Cidade</label>
        <div class="input-group mb-3">
            <select name="city_id" id="city_id" class="form-control">
                @if(old('city_id', $data->exists() ? $data->city_id : ''))
                    <option value="{{$data->city_id}}">{{$data->city_name}}</option>
                @endif
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-map"></i></span>
            </div>
        </div>
        @if($errors->has('city_id'))
            <span class="help-block">{{$errors->first('city_id')}}</span>
        @endif
    </div>
    <div class="col-md-9 form-group {{$errors->has('plan_id') ? 'has-error' : ''}} ">
        <label for="plan_id">Plano *</label>
        <div class="input-group mb-3">
            <select name="plan_id" id="plan_id" class="form-control">
                <option value="">Selecione</option>
                @foreach($plans as $plan)
                    <option value="{{$plan->id}}" {{old('plan_id', $data->exists() ? $data->plan_id : '') == $plan->id ? 'selected' : ''}}>{{$plan->name}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-parking"></i></span>
            </div>
        </div>
        @if($errors->has('plan_id'))
            <span class="help-block">{{$errors->first('plan_id')}}</span>
        @endif
    </div>


    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="is_active">Ativo</label>
        <div class="input-group mb-3">
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{old('is_active', $data->exists() ? $data->is_active : '') == "1" ? 'selected' : ''}}> SIM </option>
                <option value="0" {{old('is_active', $data->exists() ? $data->is_active : '') == "0" ? 'selected' : ''}}> NÃO </option>
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-adjust"></i></span>
            </div>
        </div>
        @if($errors->has('is_active'))
            <span class="help-block">{{$errors->first('is_active')}}</span>
        @endif
    </div>
    <div class="col-md-12 form-group {{$errors->has('broadcast') ? 'has-error' : ''}} ">
        <label for="broadcast">Emissoras *</label>
        <div class="input-group mb-3">
            <select name="broadcast[]" id="broadcast" class="form-control" multiple>
                @foreach($broadcast as $value)
                    <option value="{{$value->emissoraID}}" selected>{{$value->desc_servico.'-'.$value->razao_social."({$value->desc_municipio} {$value->desc_uf})"}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-radio-tower"></i></span>
            </div>
        </div>
        @if($errors->has('broadcast'))
            <span class="help-block">{{$errors->first('broadcast')}}</span>
        @endif
    </div>
    <div class="col-md-12 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
        <label for="description">Observações</label>
        <textarea rows="7" name="description" class="form-control"
                  id="description">{{$data->exists() && $data->description  ? $data->description : ''}}</textarea>
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('client.index')}}" class="btn btn-primary m-r-5">
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
        .select2-container--default.select2-container--focus .select2-selection--multiple{
            height: auto;
        }
        .select2-container--default .select2-selection--multiple{
            height: auto;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/forms.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        $('#document_type').change(function () {
            var type = $(this).val();
            if(type && type=='Rg'){
                $('#document').mask('AA.AA.AA.AA.AA', {
                    reverse: false, onKeyPress: function (value, event, currentField, options) {
                    }
                });
            }else if(type && type=='CPF'){
                $('#document').mask('000.000.000-00', {
                    reverse: false, onKeyPress: function (value, event, currentField, options) {
                    }
                });
            }else if(type && type=='CNPJ'){
                $('#document').mask('00.000.000/0000-00', {
                    reverse: false, onKeyPress: function (value, event, currentField, options) {
                  }
                });
            }else if(type && type=='Passaporte'){
                $('#document').mask('AA.AA.AA.AA.AA', {
                    reverse: false, onKeyPress: function (value, event, currentField, options) {
                    }
                });
            }else if(type && type=='CNH'){
                $('#document').mask('AA.AA.AA.AA.AA', {
                    reverse: false, onKeyPress: function (value, event, currentField, options) {
                    }
                });
            }


        })

        $('#zip_code').mask('99.999-999', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
        });
        $('#cellphone,#telephone').mask('(99) 9999-99999', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
        });
        $("#plan_id").select2({
            width: 'calc(100% - 38px)',
            placeholder: 'Selecione',
            allowClear: true
        });
        $("#city_id").select2({
            width: 'calc(100% - 38px)',
            minimumInputLength: 3,
            placeholder: 'Selecione',
            allowClear: true,
            ajax: {
                url: '{{route('city.search')}}',
                data: function (params) {
                    var query = {
                        search: params.term,
                        search_select: '1'
                    }
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }
            }

        });
        $("#broadcast").select2({
            width: 'calc(100% - 38px)',
            minimumInputLength: 3,
            placeholder: 'Selecione',
            allowClear: true,
            tag: true,
            ajax: {
                url: '{{route('broadcast.search')}}',
                data: function (params) {
                    var query = {
                        search: params.term,
                        client_id: '{{$data->id}}',
                        search_select: '1'
                    }
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }
            }

        });


    </script>
@endsection