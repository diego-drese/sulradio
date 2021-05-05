<div class="row">
    <div class="col-md-3 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="numero_ato">Nome *</label>
        <input type="name" class="form-control" value="{{old('name',$data->exists() ? $data->name : '')}}"
               name="name"
               id="name"
               required>
        @if($errors->has('name'))
            <span class="help-block">{{$errors->first('name')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('document_type_id') ? 'has-error' : ''}} ">
        <label for="goal">Destino *</label>
        <select class="form-control select2" id="goal" name="goal" required>
            @if($id>0)
                <option value="{{\Oka6\SulRadio\Models\Document::GOAL_CLIENT}}" {{($data->goal==\Oka6\SulRadio\Models\Document::GOAL_CLIENT ? 'selected':'')}}>{{\Oka6\SulRadio\Models\Document::GOAL_CLIENT}}</option>
                <option value="{{\Oka6\SulRadio\Models\Document::GOAL_ENGINEERING}}" {{($data->goal==\Oka6\SulRadio\Models\Document::GOAL_ENGINEERING ? 'selected':'')}}>{{\Oka6\SulRadio\Models\Document::GOAL_ENGINEERING}}</option>
                <option value="{{\Oka6\SulRadio\Models\Document::GOAL_LEGAL}}" {{($data->goal==\Oka6\SulRadio\Models\Document::GOAL_LEGAL ? 'selected':'')}}>{{\Oka6\SulRadio\Models\Document::GOAL_LEGAL}}</option>
            @else
                <option value="{{$goal}}" selected>{{$goal}}</option>
            @endif
        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('document_folder_id') ? 'has-error' : ''}} ">
        <label for="document_folder_id">Pasta *</label>
        <select class="form-control select2" id="document_folder_id" name="document_folder_id" required data-selectable="{{$data->document_folder_id}}">

        </select>
    </div>
    <div class="col-md-3 form-group {{$errors->has('document_type_id') ? 'has-error' : ''}} ">
        <label for="document_type_id">Tipo *</label>
        <select class="form-control select2" id="document_type_id" name="document_type_id" required data-selectable="{{$data->document_type_id}}">

        </select>
    </div>

    <div class="col-md-12 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
        <label for="description">Observações</label>
        <textarea rows="7" name="description" class="form-control" id="description">{{$data->exists() && $data->description  ? $data->description : ''}}</textarea>
    </div>

    <div class="col-md-8 form-group {{$errors->has('document_type_id') ? 'has-error' : ''}} ">
        <label for="document_type_id">Arquivo *</label>
        <input type="file" name="file" class="form-control" {{!$data->id ? 'required' : ''}}>
        @if($data->id)
            <label for="document_type_id">Arquivo salvo: {{$data->file_name}}</label>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('validated') ? 'has-error' : ''}} ">
        <label for="numero_ato">Validade</label>
        <input type="validated" class="form-control" value="{{old('validated',$data->exists() ? $data->validated : '')}}"
               name="validated"
               id="validated">
        @if($errors->has('validated'))
            <span class="help-block">{{$errors->first('validated')}}</span>
        @endif
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{$back_url}}" class="btn btn-primary m-r-5">
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
        $('#validated').daterangepicker({
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
        });

        $('#goal').change(function (){
            var goal = $(this).val();
            var url = '{{ route('document.folder.type', [':goal']) }}';
            $.ajax({
                url: url.replace(':goal', goal),
                type: "get",
                dataType: 'json',
                data: {},
                beforeSend: function () {

                },
                success: function (data) {
                    $('#document_folder_id').html('<option value="">Selecione</option>').trigger('change');
                    $('#document_type_id').html('<option value="">Selecione</option>').trigger('change');
                    var folders         = data['folder'];
                    var folderSelected  =  $('#document_folder_id').attr('data-selectable');
                    var folder          = null;
                    var types           = data['type'];
                    var type            = null;
                    var typeSelected    =  $('#document_type_id').attr('data-selectable');

                    for (var i=0;i<folders.length;i++){
                        folder = folders[i];
                        $('#document_folder_id').append('<option value="'+folder.id+'" '+(Number(folderSelected)==Number(folder.id) ? 'selected': '')+'>'+folder.name+'</option>').trigger('change');
                    }

                    for (var i=0;i<types.length;i++){
                        type = types[i];
                        $('#document_type_id').append('<option value="'+type.id+'" '+(Number(typeSelected)==Number(type.id) ? 'selected': '')+'>'+type.name+'</option>').trigger('change');
                    }


                    // if(data.emissora_id){
                    //     //$('#broadcast').append('<option value="'+data.emissora_id+'">'+data.emissora_name+'</option>').trigger('change');
                    // }
                },
                error: function (erro) {
                    toastr.error(erro.responseJSON.message, 'Erro');
                }
            });
        }).trigger('change');

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