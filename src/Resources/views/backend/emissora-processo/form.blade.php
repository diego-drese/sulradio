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
    <div class="col-md-4 form-group {{$errors->has('sicap') ? 'has-error' : ''}} ">
        <label for="numero_ato">Processo *</label>
        <input type="sicap" class="form-control" value="{{old('sicap',$data->exists() ? $data->sicap : '')}}"
               name="sicap"
               id="sicap">
        @if($errors->has('sicap'))
            <span class="help-block">{{$errors->first('sicap')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('data_protocolo') ? 'has-error' : ''}} ">
        <label for="data_protocolo">Data do protocolo </label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('data_protocolo',$data->exists() ? $data->data_protocolo : '')}}"
               name="data_protocolo"
               id="data_protocolo">
        @if($errors->has('data_protocolo'))
            <span class="help-block">{{$errors->first('data_protocolo')}}</span>
        @endif
    </div>
    <div class="col-md-2 form-group {{$errors->has('processo_faseID') ? 'has-error' : ''}} ">
        <label for="processo_faseID">Situação *</label>
        <select class="form-control select2" id="processo_faseID" name="processo_faseID" required>
            <option value="">Selecione</option>
            @foreach($processoFase as $key=>$value)
                <option {{isset($data->exists) && $value->processo_faseID==$data->processo_faseID ? 'selected="selected"' : '' }} value="{{$value->processo_faseID}}">{{$value->desc_processo_fase}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group {{$errors->has('processo_vinculo') ? 'has-error' : ''}} ">
        <label for="processo_vinculo">Anexado ao processo</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('processo_vinculo',$data->exists() ? $data->processo_vinculo : '')}}"
               name="processo_vinculo"
               id="processo_vinculo">
        @if($errors->has('processo_vinculo'))
            <span class="help-block">{{$errors->first('processo_vinculo')}}</span>
        @endif
    </div>

    <div class="col-md-8 form-group {{$errors->has('assunto') ? 'has-error' : ''}} ">
        <label for="assunto">Assunto</label>
        <input type="text" autocomplete="off" class="form-control" value="{{old('assunto',$data->exists() ? $data->assunto : '')}}"
               name="assunto"
               id="assunto">
        @if($errors->has('assunto'))
            <span class="help-block">{{$errors->first('assunto')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('data_ult_mov') ? 'has-error' : ''}} ">
        <label for="data_ult_mov">Data última movimentação</label>
        <input type="text" autocomplete="off" class="form-control"
               value="{{old('data_ult_mov',$data->exists() ? $data->data_ult_mov : '')}}"
               name="data_ult_mov"
               id="data_ult_mov">
        @if($errors->has('data_ult_mov'))
            <span class="help-block">{{$errors->first('data_ult_mov')}}</span>
        @endif
    </div>

    <div class="col-md-12 form-group {{$errors->has('ultima_mov') ? 'has-error' : ''}} ">
        <label for="ultima_mov">Última movimentação</label>
        <textarea rows="7" name="ultima_mov" class="form-control"
                  id="ultima_mov">{{$data->exists() && $data->ultima_mov  ? $data->ultima_mov : ''}}</textarea>
    </div>


    <div class="col-md-12 form-group {{$errors->has('observacao') ? 'has-error' : ''}} ">
        <label for="observacao">Observações</label>
        <textarea rows="7" name="observacao" class="form-control"
                  id="observacao">{{$data->exists() && $data->observacao  ? $data->observacao : ''}}</textarea>
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
