<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#data" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-radio"></i></span> <span class="hidden-xs-down">Dados cadastrais</span></a> </li>
    @if($data->emissoraID)
        @if($hasSocios)
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#partners" role="tab"><span class="hidden-sm-up"><i class="fas fa-users"></i></span> <span class="hidden-xs-down text-dark">Quadro Societário</span></a> </li>
        @endif
        @if($hasEndereco)
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#official_addresses" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-map"></i></span> <span class="hidden-xs-down text-dark">Endereços oficiais</span></a> </li>
        @endif
        @if($hasContato)
                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#contacts" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-contacts"></i></span> <span class="hidden-xs-down text-dark">Contatos</span></a> </li>
        @endif
    @endif
</ul>
<div class="card">
    <div class="card-body ">
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <div class="tab-pane active" id="data" role="tabpanel">
                @include('SulRadio::backend.emissora.form')

            </div>
            @if($data->emissoraID)
                <div class="tab-pane" id="partners" role="tabpanel">
                    @include('SulRadio::backend.emissora-socio.index')
                </div>
                <div class="tab-pane" id="official_addresses" role="tabpanel">
                    @include('SulRadio::backend.emissora-endereco.index')
                </div>
                <div class="tab-pane" id="contacts" role="tabpanel">
                    @include('SulRadio::backend.emissora-contato.index')
                </div>
            @endif
        </div>

    </div>
</div>