<div class="row">
    <div class="col-md-6 form-group {{$errors->has('desc_municipio') ? 'has-error' : ''}} ">
        <label for="name">Nome *</label>
        <div class="input-group mb-3">
            <input type="text" name="desc_municipio" id="desc_municipio" class="form-control" placeholder="Nome do município" required
            value="{{old('desc_municipio', $data->exists() ? $data->desc_municipio : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-city"></i></span>
            </div>
        </div>
        @if($errors->has('desc_municipio'))
            <span class="help-block">{{$errors->first('desc_municipio')}}</span>
        @endif
    </div>

    <div class="col-md-6 form-group {{$errors->has('value') ? 'has-error' : ''}} ">
        <label for="value">UF *</label>
        <div class="input-group mb-3">
            <select class="form-control select2" id="ufID" name="ufID" required>
                <option value="">Selecione</option>
                @foreach($ufs as $key=>$value)
                    <option {{old('ufID', $data->exists() ? $data->ufID : '') == $value->ufID ? 'selected="selected"' : ''}} value="{{$value->ufID}}">{{$value->uf_extenso}}</option>
                @endforeach
            </select>

        </div>
        @if($errors->has('value'))
            <span class="help-block">{{$errors->first('value')}}</span>
        @endif
    </div>
</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('municipio.index')}}" class="btn btn-primary m-r-5">
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