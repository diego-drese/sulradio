<div class="row">
    <div class="col-md-6 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="name">Nome *</label>
        <div class="input-group mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome do plano" required
            value="{{old('name', $data->exists() ? $data->name : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-parking"></i></span>
            </div>
        </div>
        @if($errors->has('name'))
            <span class="help-block">{{$errors->first('name')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('value') ? 'has-error' : ''}} ">
        <label for="value">Valor *</label>
        <div class="input-group mb-3">
            <input type="text" name="value" id="value" class="form-control" placeholder="Valor do plano" required
                   value="{{old('value', $data->exists() ? $data->value : '')}}">
            <div class="input-group-append">
                <span class="input-group-text">R$</span>
            </div>
        </div>
        @if($errors->has('value'))
            <span class="help-block">{{$errors->first('value')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('frequency') ? 'has-error' : ''}} ">
        <label for="frequency">Frequência dias*</label>
        <div class="input-group mb-3">
            <input type="number" min="30" step="30" name="frequency" id="frequency" class="form-control" placeholder="Frequência de conbrança" required
                   value="{{old('frequency', $data->exists() ? $data->frequency : '')}}">
            <div class="input-group-append">
                <span class="input-group-text "><i class="mdi mdi-refresh"></i></span>
            </div>
        </div>
        @if($errors->has('frequency'))
            <span class="help-block">{{$errors->first('frequency')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('max_upload') ? 'has-error' : ''}} ">
        <label for="max_upload">Max Upload *</label>
        <div class="input-group mb-3">
            <input type="text" name="max_upload" id="max_upload" class="form-control" placeholder="Máximo em Gb" required
                   value="{{old('max_upload', $data->exists() ? $data->max_upload : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-cloud-upload"></i></span>
            </div>
        </div>
        @if($errors->has('max_upload'))
            <span class="help-block">{{$errors->first('max_upload')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('max_broadcast') ? 'has-error' : ''}} ">
        <label for="max_broadcast">Max Emissoras *</label>
        <div class="input-group mb-3">
            <input type="text" name="max_broadcast" id="max_broadcast" class="form-control" placeholder="Máximo de emissoras" required
                   value="{{old('max_broadcast', $data->exists() ? $data->max_broadcast : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-radio-tower"></i></span>
            </div>
        </div>
        @if($errors->has('max_broadcast'))
            <span class="help-block">{{$errors->first('max_broadcast')}}</span>
        @endif
    </div>

    <div class="col-md-3 form-group {{$errors->has('max_user') ? 'has-error' : ''}} ">
        <label for="max_user">Max Users *</label>
        <div class="input-group mb-3">
            <input type="text" name="max_user" id="max_user" class="form-control" placeholder="Máximo de usuários" required
                   value="{{old('max_user', $data->exists() ? $data->max_user : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="far fa-user"></i></span>
            </div>
        </div>
        @if($errors->has('max_user'))
            <span class="help-block">{{$errors->first('max_user')}}</span>
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
    <a style="float: right;" href="{{route('plan.index')}}" class="btn btn-primary m-r-5">
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
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        $(".select2").select2({
            width: '100%',
            placeholder: 'Selecione',
            allowClear: true
        });

    </script>
@endsection