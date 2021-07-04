<div class="row">
    <div class="col-md-6 form-group {{$errors->has('name') ? 'has-error' : ''}} ">
        <label for="name">Nome *</label>
        <div class="input-group mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome do status" required
            value="{{old('name', $data->exists() ? $data->name : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-file-document"></i></span>
            </div>
        </div>
        @if($errors->has('name'))
            <span class="help-block">{{$errors->first('name')}}</span>
        @endif
    </div>
    <div class="col-md-3 form-group {{$errors->has('color') ? 'has-error' : ''}} ">
        <label for="color">Cor</label>
        <div class="input-group mb-3">
            <input type="color" name="color" id="color" class="form-control" required
                   value="{{old('color', $data->exists() ? $data->color : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-adjust"></i></span>
            </div>
        </div>
        @if($errors->has('color'))
            <span class="help-block">{{$errors->first('color')}}</span>
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
    <div class="col-md-3 form-group {{$errors->has('is_active') ? 'has-error' : ''}} ">
        <label for="update_completed_at">
            Finaliza Automaticamente<br/>
            <span style="font-size: 10px;font-weight: 300;">Somente um status terá essa função</span>
        </label>
        <div class="input-group mb-3">
            <select name="update_completed_at" id="update_completed_at" class="form-control">
                <option value="0" {{old('update_completed_at', $data->exists() ? $data->update_completed_at : '') == "0" ? 'selected' : ''}}> NÃO </option>
                <option value="1" {{old('update_completed_at', $data->exists() ? $data->update_completed_at : '') == "1" ? 'selected' : ''}}> SIM </option>
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-adjust"></i></span>
            </div>
        </div>

        @if($errors->has('update_completed_at'))
            <span class="help-block">{{$errors->first('update_completed_at')}}</span>
        @endif
    </div>
    <div class="col-md-12 form-group {{$errors->has('description') ? 'has-error' : ''}} ">
        <label for="description">Descrição</label>
        <textarea rows="7" name="description" class="form-control"
                  id="description">{{$data->exists() && $data->description  ? $data->description : ''}}</textarea>
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('ticket.status.index')}}" class="btn btn-primary m-r-5">
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