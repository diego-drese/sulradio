<div class="modal-content" style="height: 100%">
    <div class="modal-header">
        <h5 class="modal-title">{{$emissora->entidade['entidade_nome_entidade']}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" id="modal">
       <iframe src="http://sistemas.anatel.gov.br/se/public/view/b/form.php?id={{$emissora->id}}&state={{$emissora->state}}&time={{time()}}" frameborder="0" style="width: 100%;height: 100%"></iframe>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
    </div>
</div>