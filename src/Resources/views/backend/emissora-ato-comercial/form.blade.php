<h4 class="card-title">
    {{$emissora->razao_social}}<br/>
    @if($emissora->desc_status_sead=="ATIVO")
        <span class="font-10 badge badge-success">ATIVO</span>
    @elseif($emissora->desc_status_sead=="INATIVO")
        <span class="font-10 badge badge-danger">INATIVO</span>
    @elseif($emissora->desc_status_sead=="CONCORRENCIA")
        <span class="font-10 badge badge-info">CONCORRENCIA</span>
    @else
        ---
    @endif <br/>
    <span class="font-10">{{$emissora->desc_municipio}} ({{$emissora->desc_uf}})</span>
</h4>
<div class="row">
    <div class="col-md-7 form-group {{$errors->has('tipo_ato_juridicoID') ? 'has-error' : ''}} ">
        <label for="tipo_ato_juridicoID">Categoria *</label>
        <select class="form-control select2" id="tipo_ato_juridicoID" name="tipo_ato_juridicoID" required>
            <option value="">Selecione</option>
            @foreach($tipoAtoJuridico as $key=>$value)
                <option {{isset($data->exists) && $value->tipo_ato_juridicoID==$data->tipo_ato_juridicoID ? 'selected="selected"' : '' }} value="{{$value->tipo_ato_juridicoID}}">{{$value->desc_tipo_ato_juridico}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group {{$errors->has('data_protocolo') ? 'has-error' : ''}} ">
        <label for="data_assinatura">Data de assinatura</label>
        <input type="text" class="form-control"
               value="{{old('data_assinatura',$data->exists() ? $data->data_assinatura : '')}}"
               name="data_assinatura"
               id="data_assinatura">
        @if($errors->has('data_assinatura'))
            <span class="help-block">{{$errors->first('data_assinatura')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('sicap') ? 'has-error' : ''}} ">
        <label for="arquivo_junta">Nº arquivamento junta comercial</label>
        <input type="arquivo_junta" class="form-control"
               value="{{old('arquivo_junta',$data->exists() ? $data->arquivo_junta : '')}}"
               name="arquivo_junta"
               id="arquivo_junta">
        @if($errors->has('arquivo_junta'))
            <span class="help-block">{{$errors->first('arquivo_junta')}}</span>
        @endif
    </div>


    <div class="col-md-2 form-group {{$errors->has('nire') ? 'has-error' : ''}} ">
        <label for="nire">Nire</label>
        <input type="text" class="form-control" value="{{old('nire',$data->exists() ? $data->nire : '')}}"
               name="nire"
               id="nire">
        @if($errors->has('nire'))
            <span class="help-block">{{$errors->first('nire')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('data_arquivo_junta') ? 'has-error' : ''}} ">
        <label for="data_arquivo_junta">Data de arq. junta comercial</label>
        <input type="text" class="form-control"
               value="{{old('data_arquivo_junta',$data->exists() ? $data->data_arquivo_junta : '')}}"
               name="data_arquivo_junta"
               id="data_arquivo_junta">
        @if($errors->has('data_arquivo_junta'))
            <span class="help-block">{{$errors->first('data_arquivo_junta')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('livro') ? 'has-error' : ''}} ">
        <label for="livro">Registro civil pessoa jurídica</label>
        <input type="text" class="form-control" value="{{old('livro',$data->exists() ? $data->livro : '')}}"
               name="livro"
               id="livro">
        @if($errors->has('livro'))
            <span class="help-block">{{$errors->first('livro')}}</span>
        @endif
    </div>


    <div class="col-md-12 form-group {{$errors->has('observacao') ? 'has-error' : ''}} ">
        <label for="observacoes">Observações</label>
        <textarea rows="7" name="observacoes" class="form-control"
                  id="observacoes">{{$data->exists() && $data->observacoes  ? $data->observacoes : ''}}</textarea>
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.atos.comercial.index', [$emissoraID])}}"
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
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>
    <script>
        $('#data_assinatura,#data_arquivo_junta').daterangepicker({
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