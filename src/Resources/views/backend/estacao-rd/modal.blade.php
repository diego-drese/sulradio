<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">{{$emissora->entidade['entidade_nome_entidade']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-md-12 form-group " style="">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#entidade" role="tab">
                        <span class="hidden-sm-up"><i class="fas fa-info-circle"></i></span> <span class="hidden-xs-down">Entidade</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#administrativo" role="tab">
                        <span class="hidden-sm-up"><i class="fas fa-dollar-sign"></i></span> <span class="hidden-xs-down text-dark">Administrativo</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#enderecos" role="tab">
                        <span class="hidden-sm-up"><i class="fas fa-donate"></i></span> <span class="hidden-xs-down text-dark">Endereços</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#plano_basico" role="tab">
                        <span class="hidden-sm-up"><i class="fas fa-university"></i></span> <span class="hidden-xs-down text-dark">Plano Básico</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sistema_princial" role="tab">
                        <span  class="hidden-sm-up"><i class="fas fa-paperclip"></i></span> <span class="hidden-xs-down text-dark">Sistema Principal</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sistema_trans_auxiliar" role="tab">
                        <span  class="hidden-sm-up"><i class="fas fa-paperclip"></i></span> <span class="hidden-xs-down text-dark">Sistema de Trans. Auxiliar</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#rds" role="tab">
                        <span  class="hidden-sm-up"><i class="fas fa-paperclip"></i></span> <span class="hidden-xs-down text-dark">RDS</span></a>
                </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="entidade" role="tabpanel">
                    @include('SulRadio::backend.estacao-rd.aba-entidade')
                </div>

                <div class="tab-pane" id="administrativo" role="tabpanel">
                    @include('SulRadio::backend.estacao-rd.aba-administrativo')
                </div>

                <div class="tab-pane" id="enderecos" role="tabpanel">
                    @include('SulRadio::backend.estacao-rd.aba-endereco')
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
    </div>
</div>