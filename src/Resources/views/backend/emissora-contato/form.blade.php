<div class="row">
    <div class="col-md-8 form-group {{$errors->has('nome_contato') ? 'has-error' : ''}} ">
        <label for="nome_contato">Nome *</label>
        <input type="nome_contato" class="form-control" value="{{old('nome_contato',$data->exists() ? $data->nome_contato : '')}}"
               name="nome_contato"
               id="nome_contato"
                required>
        @if($errors->has('nome_contato'))
            <span class="help-block">{{$errors->first('nome_contato')}}</span>
        @endif
    </div>
    <div class="col-md-4 form-group {{$errors->has('funcaoID') ? 'has-error' : ''}} ">
        <label for="funcaoID">Função *</label>
        <select class="form-control select2" id="funcaoID" name="funcaoID" required>
            <option value="">Selecione</option>
            @foreach($funcao as $key=>$value)
                <option {{isset($data->exists) && (string)$value->funcaoID===(string)$data->funcaoID ? 'selected="selected"' : '' }} value="{{$value->funcaoID}}">{{$value->desc_funcao}}</option>
            @endforeach
        </select>
    </div>
</div>
<h4 class="card-title">Informações de contato</h4>
<hr />
<div class="row">

    <div class="col-sm-3">
        <div class="form-group">
            <input type="text" class="form-control" id="fone_contato" name="fone_contato[]" placeholder="Telefone">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <input type="text" class="form-control" id="cel_contato" name="cel_contato[]" placeholder="Celular">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <input type="text" class="form-control" id="email_contato" name="email_contato[]" placeholder="Email">
        </div>
    </div>

    <div class="col-sm-1">
        <div class="form-group">
            <button class="btn btn-success" type="button" onclick="add_contact();"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<div id="contatcs">

</div>
<div class="col-md-12 form-group">
    @if($hasStore || $hasUpdate)
        <button style="float: right;" type="submit" class="btn btn-success">Salvar</button>
    @endif
    <a style="float: right;" href="{{route('emissora.edit', [$emissoraID])}}" class="btn btn-primary m-r-5">
        <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
    </a>
</div>
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">

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
        var id= 0;
        var remove_contacts = function (id){
            $('#'+id).remove();
        }
        var add_contact = function (telefone, fax, celular, email){
            id++;
            var idHtmld = 'contact-'+id;
            var html = '<div class="row" id="'+idHtmld+'"><div class="col-sm-3">\n' +
                '        <div class="form-group">\n' +
                '            <input value="'+(telefone ?telefone : '')+'" type="text" class="form-control" id="fone_contato" name="fone_contato[]" placeholder="Telefone">\n' +
                '        </div>\n' +
                '    </div>\n' +
                '    <div class="col-sm-2">\n' +
                '        <div class="form-group">\n' +
                '            <input value="'+(celular ? celular: '')+'" type="text" class="form-control" id="cel_contato" name="cel_contato[]" placeholder="Celular">\n' +
                '        </div>\n' +
                '    </div>\n' +
                '    <div class="col-sm-4">\n' +
                '        <div class="form-group">\n' +
                '            <input value="'+(email ? email : '')+'" type="text" class="form-control" id="email_contato" name="email_contato[]" placeholder="Email">\n' +
                '        </div>\n' +
                '    </div>\n' +
                '\n' +
                '    <div class="col-sm-1">\n' +
                '        <div class="form-group">\n' +
                '            <button class="btn btn-danger" type="button" onclick="remove_contacts(\''+idHtmld+'\');"><i class="fa fa-minus"></i></button>\n' +
                '        </div>\n' +
                '    </div></div>';
            $('#contatcs').append(html);
        }
        var contacts = {!! json_encode($contatos) !!};
        for (var i=0; i<contacts.length;i++){
            var contact = contacts[i];
            add_contact(contact.fone_contato, null, contact.cel_contato,contact.email_contato)
        }

    </script>
@endsection