<div class="row">
    <div id="files">

    </div>

    <div class="col-md-6 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="name">Assunto *</label>
        <div class="input-group mb-3">
            <input type="text" name="subject" id="subject" class="form-control" placeholder="Assunto" required
            value="{{old('subject', $data->exists() ? $data->subject : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-file-document"></i></span>
            </div>
        </div>
        @if($errors->has('subject'))
            <span class="help-block">{{$errors->first('subject')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="priority_id">Prioridade</label>
        <div class="input-group mb-3">
            <select name="priority_id" id="priority_id" class="form-control">
                <option value="">Selecione</option>
                @foreach($priority as $value)
                    <option value="{{$value->id}}" {{old('priority_id', $data->exists() ? $data->priority_id : '') == $value->id ? 'selected' : ''}}> {{$value->name}} </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-priority-high"></i></span>
            </div>
        </div>
        @if($errors->has('priority_id'))
            <span class="help-block">{{$errors->first('priority_id')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="category_id">Categoria</label>
        <div class="input-group mb-3">
            <select name="category_id" id="category_id" class="form-control">
                <option value="">Selecione</option>
                @foreach($category as $value)
                    <option value="{{$value->id}}" {{old('category_id', $data->exists() ? $data->category_id : '') == $value->id ? 'selected' : ''}}> {{$value->name}} </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-ticket"></i></span>
            </div>
        </div>
        @if($errors->has('category_id'))
            <span class="help-block">{{$errors->first('category_id')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="status_id">Status</label>
        <div class="input-group mb-3">
            <select name="status_id" id="status_id" class="form-control">
                <option value="">Selecione</option>
                @foreach($status as $value)
                    <option value="{{$value->id}}" {{old('status_id', $data->exists() ? $data->status_id : '') == $value->id ? 'selected' : ''}}> {{$value->name}} </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-alert-box"></i></span>
            </div>
        </div>
        @if($errors->has('status_id'))
            <span class="help-block">{{$errors->first('status_id')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="agent_id">Responsavel</label>
        <div class="input-group mb-3">
            <select name="agent_id" id="agent_id" class="form-control">
                <option value="">Selecione</option>
                @foreach($users as $value)
                    <option value="{{$value->id}}" {{old('agent_id', $data->exists() ? $data->agent_id : '') == $value->id ? 'selected' : ''}}> {{$value->name}} </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
        </div>
        @if($errors->has('agent_id'))
            <span class="help-block">{{$errors->first('agent_id')}}</span>
        @endif
    </div>

    <div class="col-md-6 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="emissora_id">Emissora</label>
        <div class="input-group mb-3">
            <select name="emissora_id" id="emissora_id" class="form-control">
                @if(isset($emissora) && $emissora->emissoraID)
                    <option selected value="{{$emissora->emissoraID}}">{{$emissora->razao_social}}</option>
                @endif
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-radio"></i></span>
            </div>
        </div>
        @if($errors->has('emissora_id'))
            <span class="help-block">{{$errors->first('emissora_id')}}</span>
        @endif
    </div>

    <div class="col-md-8 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
        <label for="content">Conteudo</label>
        <textarea rows="12" name="content" class="form-control summernote" id="content">{{old('content', $data->exists() && $data->content  ? $data->content : '')}}</textarea>
    </div>
    <div class="col-md-4 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="start_forecast">Previsão de início *</label>
        <div class="input-group mb-3">
            <input type="text" name="start_forecast" id="start_forecast" class="form-control" placeholder="Previsão de inicio" required
                   value="{{old('start_forecast', $data->exists() && $data->start_forecast ? $data->start_forecast : date('d/m/Y'))}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            </div>
        </div>
        @if($errors->has('start_forecast'))
            <span class="help-block">{{$errors->first('start_forecast')}}</span>
        @endif
        <label for="end_forecast">Previsão de término *</label>
        <div class="input-group mb-3">
            <input type="text" name="end_forecast" id="end_forecast" class="form-control" placeholder="Previsão de término" required
                   value="{{old('end_forecast', $data->exists() && $data->end_forecast ? $data->end_forecast : date('d/m/Y'))}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
            </div>
        </div>
        @if($errors->has('end_forecast'))
            <span class="help-block">{{$errors->first('end_forecast')}}</span>
        @endif
    </div>


    </div>
    @if(!$data->id)
        <div class="col-md-12 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
            <label for="content">Anexos</label>
            <div id="dropzone">
                <div class="dz-message">Arraste o arquivo</div>
            </div>

        </div>
        @endif

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif

    @if($data->id)
        <a style="float: right;" href="{{route('ticket.ticket', [$data->id])}}" class="btn btn-primary m-r-5">
            <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
        </a>
    @else
        <a style="float: right;" href="{{route('ticket.index')}}" class="btn btn-primary m-r-5">
            <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
        </a>
    @endif

</div>
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/summernote.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/dropzone.css')}}">
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
        .note-toolbar-wrapper{height: inherit!important;}
        .note-toolbar{z-index: 5}
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/summernote.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/dropzone.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>

    <script>
        $('#start_forecast, #end_forecast').daterangepicker({
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
            var a = moment($("#start_forecast").val(), "DD/MM/YYYY"),
                b = moment($("#end_forecast").val(), "DD/MM/YYYY");
            if(b.unix() < a.unix()){
                $('#end_forecast').val(a.format('DD/MM/YYYY'));
                toastr.info('A data de término não pode sere menor que a data de início', "Data inválida", {timeOut: 6000});
            }

        }).on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $(".select2").select2({
            width: '100%',
            placeholder: 'Selecione',
            allowClear: true
        });
        $('.summernote').summernote({
            height: 250,

        });
        $(document).ready(function () {
            Dropzone.prototype.defaultOptions.dictDefaultMessage = "Arraste os arquivos aqui para enviar";
            Dropzone.prototype.defaultOptions.dictFallbackMessage = "Seu navegador não suporta uploads de arquivos arrastar e soltar.";
            Dropzone.prototype.defaultOptions.dictFallbackText = "Use o formulário substituto abaixo para enviar seus arquivos como antigamente.";
            Dropzone.prototype.defaultOptions.dictFileTooBig = "O arquivo é muito grande (@{{filesize}} MB). Tamanho máximo do arquivo: @{{maxFilesize}} MB.";
            Dropzone.prototype.defaultOptions.dictInvalidFileType = "você não pode fazer upload de arquivos deste tipo.";
            Dropzone.prototype.defaultOptions.dictResponseError = "O servidor respondeu com o código @{{statusCode}}.";
            Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar upload";
            Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = "Tem certeza que deseja cancelar este upload?";
            Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover";
            Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = "Você não pode enviar mais arquivos.";

            var drop = $("div#dropzone").dropzone({
                url: '{{route('ticket.upload', [$data->id])}}',
                uploadMultiple: true,
                addRemoveLinks: true,
                maxFilesize: 30,
                parallelUploads: 1,
                maxFiles: 10,
                preventDuplicates: true,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                init: function() {
                    this.on("error", function (file, message) {
                        toastr.error(message, "Erro", {timeOut: 6000});
                        this.removeFile(file);
                    });
                    this.on("addedfile", function(file) {
                        if (this.files.length) {
                            var _i, _len;
                            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++){
                                if(this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()){
                                    this.removeFile(file);
                                    toastr.info('Esse arquivo ja se encontra na listagem', "Duplicado", {timeOut: 6000});
                                }
                            }
                        }
                    });
                    this.on("removedfile", function(file){
                        $('#'+file.upload.uuid).remove()
                    });

                    this.on("complete", function(file) {

                    })
                },

                success: function(file, result) {
                    var files               = result.files[0];
                    var fileName            = files['file_name'];
                    var fileNameOriginal    = files['file_name_original'];
                    var valueAppend         = fileName+'[--]'+fileNameOriginal;
                    $('#files').append(' <input id="'+file.upload.uuid+'" type="hidden" name="files[]" value="'+valueAppend+'">')
                },
                error: function(file, result) {
                    toastr.error(result.message, "Erro", {timeOut: 6000});
                }
            }).addClass('dropzone');
        });

        $("#emissora_id").select2({
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
                        ignore_client: true,
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