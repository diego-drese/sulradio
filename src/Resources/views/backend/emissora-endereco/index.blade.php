@if($hasAdd)
    <div class="d-flex no-block align-items-center m-b-10">
        <h4 class="card-title">&nbsp;</h4>
        <div class="ml-auto">
            <div class="btn-group">
                <a  href="{{route('emissora.index')}}" class="btn btn-primary m-r-5">
                    <span class=" fas fa-arrow-left"></span> <b>Voltar</b>
                </a>
                @if($hasAddEndereco)
                    <a href="{{route('emissora.endereco.create',$data->emissoraID)}}"
                       class="btn btn-primary">
                        <span class="fa fa-plus"></span> <b>Adicionar</b>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
<div class="table-responsive">
    <table id="tableEndereco" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style="width: 80px">Ações</th>
            <th>Tipo de Endereço</th>
            <th>UF</th>
            <th>Município</th>
            <th>Endereço</th>

        </tr>
        <tr>
            <th>
                <spa class="btn btn-primary btn-xs m-r-5" id="clearFilterEnd">
                    <span class="fas fa-sync-alt"></span> <b>Limpar</b>
                </spa>
            </th>
            <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Tipo"></th>
            <th><input type="text" autocomplete="off" maxlength="4" class="fieldSearch form-control text-primary" id="data_protocolo" placeholder="Buscar Uf"></th>
            <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Município"></th>
            <th><input type="text" autocomplete="off" class="fieldSearch form-control text-primary" placeholder="Buscar Rua"></th>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
