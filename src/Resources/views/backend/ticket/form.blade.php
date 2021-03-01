<div class="row">
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
                    <option selected value="{{$emissora->emissoraID}}">{{$emissora->nome_fantasia}}</option>
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


    <div class="col-md-12 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
        <label for="content">Conteudo</label>
        <textarea rows="12" name="content" class="form-control summernote" id="content">{{$data->exists() && $data->content  ? $data->content : ''}}</textarea>
    </div>

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
    <script>
        $(".select2").select2({
            width: '100%',
            placeholder: 'Selecione',
            allowClear: true
        });
        $('.summernote').summernote({
            height: 250,

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