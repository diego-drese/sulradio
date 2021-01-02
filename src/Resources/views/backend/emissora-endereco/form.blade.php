<div class="row">
    <div class="col-md-7 form-group {{$errors->has('tipo_enderecoID') ? 'has-error' : ''}} ">
        <label for="tipo_enderecoID">Tipo de endereço *</label>
        <select class="form-control select2" id="tipo_enderecoID" name="tipo_enderecoID" required>
            <option value="">Selecione</option>
            @foreach($tipoEndereco as $key=>$value)
                <option {{isset($data->exists) && $value->tipo_enderecoID==$data->tipo_enderecoID ? 'selected="selected"' : '' }} value="{{$value->tipo_enderecoID}}">{{$value->desc_tipo_endereco}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 form-group {{$errors->has('logradouro') ? 'has-error' : ''}} ">
        <label for="numero_ato">Logradouro *</label>
        <input type="logradouro" class="form-control" value="{{old('logradouro',$data->exists() ? $data->logradouro : '')}}"
               name="logradouro"
               id="logradouro"
               required>
        @if($errors->has('logradouro'))
            <span class="help-block">{{$errors->first('logradouro')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('numero') ? 'has-error' : ''}} ">
        <label for="numero">Número</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('numero',$data->exists() ? $data->numero : '')}}"
               name="numero"
               id="numero">
        @if($errors->has('numero'))
            <span class="help-block">{{$errors->first('numero')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('complemento') ? 'has-error' : ''}} ">
        <label for="complemento">Complemento</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('complemento',$data->exists() ? $data->complemento : '')}}"
               name="complemento"
               id="complemento">
        @if($errors->has('complemento'))
            <span class="help-block">{{$errors->first('complemento')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('cep') ? 'has-error' : ''}} ">
        <label for="cep">CEP</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('cep',$data->exists() ? $data->cep : '')}}"
               name="cep"
               id="cep">
        @if($errors->has('cep'))
            <span class="help-block">{{$errors->first('cep')}}</span>
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
    <div class="col-md-2 form-group {{$errors->has('ufID') ? 'has-error' : ''}} ">
        <label for="ufID" class="d-block">Uf *</label>
        <select class="form-control select2" id="ufID" name="ufID" required
                data-municipioID="{{$data->municipioID}}">
            <option value="">Selecione</option>
            @foreach($uf as $key=>$value)
                <option data-municipioID="{{json_encode($value->municipios)}}"
                        {{isset($data->exists) && (string)$value->ufID===(string)$data->ufID ? 'selected="selected"' : '' }} value="{{$value->ufID}}">{{$value->desc_uf}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group ">
        <label for="municipioID" class="d-block">Município *</label>
        <select class="form-control select2" id="municipioID" name="municipioID" required>

        </select>
    </div>
</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.endereco.index', [$emissoraID])}}" class="btn btn-primary m-r-5">
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
        $('#data_protocolo,#data_ult_mov').daterangepicker({
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