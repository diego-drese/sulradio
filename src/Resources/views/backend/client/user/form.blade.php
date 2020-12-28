<div class="callout callout-success">
    <h5>Empresa: <b>{{$client->company_name}}</b></h5>
    <h6>Nome: <b>{{$client->name}}</b></h6>
    <h6>{{$client->document_type}}: <b>{{$client->document}}</b></h6>
</div>
<div class="row">
    <div class="col-md-4 form-group {{$errors->has('name') ? 'text-danger' : ''}} ">
        <label for="name">Nome *</label>
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
    <div class="col-md-4 form-group {{$errors->has('lastname') ? 'text-danger' : ''}} ">
        <label for="lastname">Sobrenome *</label>
        <div class="input-group mb-3">
            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Nome" required
                   value="{{old('lastname', $data->exists() ? $data->lastname : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
            </div>
        </div>
        @if($errors->has('lastname'))
            <span class="help-block">{{$errors->first('lastname')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('cell_phone') ? 'text-danger' : ''}} ">
        <label for="cell_phone">Celular *</label>
        <div class="input-group mb-3">
            <input type="text" name="cell_phone" id="cell_phone" class="form-control" placeholder="Nome" required
                   value="{{old('cell_phone', $data->exists() ? $data->cell_phone : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
            </div>
        </div>
        @if($errors->has('cell_phone'))
            <span class="help-block">{{$errors->first('cell_phone')}}</span>
        @endif
    </div>

    <div class="col-md-8 form-group {{$errors->has('email') ? 'text-danger' : ''}} ">
        <label for="email">Email *</label>
        <div class="input-group mb-3">
            <input type="text" name="email" id="email" class="form-control" placeholder="Email" required
                   value="{{old('email', $data->exists() ? $data->email : '')}}">
            <div class="input-group-append">
                <span class="input-group-text"><i class="mdi mdi-gmail"></i> </span>
            </div>
        </div>
        @if($errors->has('email'))
            <span class="help-block">{{$errors->first('email')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('profile_id') ? 'text-danger' : ''}} ">
        <label for="profile_id">Perfil*</label>
        <div class="input-group mb-3">
            <select name="profile_id" id="profile_id" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($profiles as $profile)
                    <option value="{{$profile->id}}" {{old('profile_id', $data->exists() ? $data->profile_id : '')==$profile->id? 'selected': ''}}>{{$profile->name}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <span class="input-group-text "><i class="fas fa-address-card"></i></span>
            </div>
        </div>
        @if($errors->has('profile_id'))
            <span class="help-block">{{$errors->first('profile_id')}}</span>
        @endif
    </div>

    <div class="col-md-4 form-group {{$errors->has('resource_default_id') ? 'text-danger' : ''}} ">
        <label for="resource_default_id">Página inicial *</label>
        <div class="input-group mb-3">
            <select name="resource_default_id" id="resource_default_id" class="form-control" required>
                <option value="">Selecione um perfil</option>
            </select>
            <div class="input-group-append">
                <span class="input-group-text "><i class="fas fa-address-card"></i></span>
            </div>
        </div>
        @if($errors->has('resource_default_id'))
            <span class="help-block">{{$errors->first('resource_default_id')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('active') ? 'text-danger' : ''}} ">
        <label for="active">Ativo *</label>
        <div class="input-group mb-3">
            <select name="active" id="active" class="form-control">
                <option value="1" {{old('active', $data->exists() ? $data->active : '') == "1" ? 'selected' : ''}}> SIM </option>
                <option value="0" {{old('active', $data->exists() ? $data->active : '') == "0" ? 'selected' : ''}}> NÃO </option>
            </select>
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-adjust"></i></span>
            </div>
        </div>
        @if($errors->has('active'))
            <span class="help-block">{{$errors->first('active')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('is_active') ? 'text-danger' : ''}} ">
        <label for="is_active">Recebe notificações </label>
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

    <div class="col-md-6 form-group {{$errors->has('password') ? 'text-danger' : ''}} ">
        <label for="password">Senha *</label>
        <div class="input-group mb-3">
            <input type="password" name="password" id="password" class="form-control" autocomplete="off" placeholder="Senha" {{!$data->_id ? 'required' : ''}}
                   value="">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
            </div>
        </div>
        @if($errors->has('password'))
            <span class="help-block">{{$errors->first('password')}}</span>
        @endif
    </div>

    <div class="col-md-6 form-group {{$errors->has('password_confirmation') ? 'text-danger' : ''}} ">
        <label for="password_confirmation">Confirmar Senha *</label>
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="off" placeholder="Confirmação de senha" {{!$data->_id ? 'required' : ''}}
                   value="">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-address-book"></i></span>
            </div>
        </div>
        @if($errors->has('password_confirmation'))
            <span class="help-block">{{$errors->first('password_confirmation')}}</span>
        @endif
    </div>
    <div class="col-md-12 form-group {{$errors->has('description') ? 'text-danger' : ''}} ">
        <label for="description">Observações</label>
        <textarea rows="7" name="description" class="form-control"
                  id="description">{{old('description', $data->exists() ? $data->description : '')}}</textarea>
    </div>

</div>

<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('client.user',[$client->_id])}}" class="btn btn-primary m-r-5">
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
        .callout {
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            background-color: #ffffff;
            border-left: 5px solid #e9ecef;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .callout a {
            color: #495057;
            text-decoration: underline;
        }

        .callout a:hover {
            color: #e9ecef;
        }

        .callout p:last-child {
            margin-bottom: 0;
        }

        .callout.callout-danger {
            border-left-color: #bd2130;
        }

        .callout.callout-warning {
            border-left-color: #d39e00;
        }

        .callout.callout-info {
            border-left-color: #117a8b;
        }

        .callout.callout-success {
            border-left-color: #1e7e34;
        }
    </style>
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/forms.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/select2.js')}}></script>
    <script>
        var resource_default_id = "{{old('resource_default_id', $data->exists() ? $data->resource_default_id : '')}}";

        $('#zip_code').mask('99.999-999', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
        });
        $('#cell_phone,#telephone').mask('(99) 9999-99999', {
            reverse: false, onKeyPress: function (value, event, currentField, options) {
            }
        });
        $("#plan_id").select2({
            width: 'calc(100% - 38px)',
            placeholder: 'Selecione',
            allowClear: true
        });
        function getResourcesByProfileId($id) {
            var url = '{{route('admin.users.resourcesDefault', [':id'])}}';
            url = url.replace(':id', $id)
            $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (data) {
                    populateResourcesDefault(data);
                },
                error: function (erro) {
                    console.log(erro.responseJSON.message);
                    toastr["error"](erro.responseJSON.message, "Error");
                }
            })
        }

        function populateResourcesDefault(arrayResources) {
            var jsonData = arrayResources;
            var selectResources = $('#resource_default_id');
            selectResources.empty();
            jsonData.forEach((function (element) {
                selectResources.append(`<option ${element.id == resource_default_id ? 'selected="selected"' : ''} value="${element.id}">${element.menu}</option>`);
            }))
        }

        $('#profile_id').change(function (){
            if(this.value){
                getResourcesByProfileId(this.value);
            }else{
                $('#resource_default_id').html('<option value="">Selecione um perfil</option>');
            }
        });
        $('#profile_id').trigger('change')



    </script>
@endsection