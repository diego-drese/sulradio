@if($hasAdd)
    <div class="d-flex no-block align-items-center m-b-10">
        <h4 class="card-title">&nbsp;</h4>
        <div class="ml-auto">
            <div class="btn-group">
                <a href="{{route('emissora.socio.create',$data->emissoraID)}}"
                   class="btn btn-primary">
                    <span class="fa fa-plus"></span> <b>Adicionar</b>
                </a>
            </div>
        </div>
    </div>
@endif
<div class="table-responsive">
    <table id="tableSocio" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="width: 80px">Ações</th>
            <th>Nome</th>
            <th>Categoria</th>
        </tr>
        <tr>
            <th>
                <spa class="btn btn-primary btn-xs m-r-5" id="clearFilterSocios">
                    <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                </spa>
            </th>
            <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Nome"></th>
            <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" placeholder="Buscar Categoria"></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

