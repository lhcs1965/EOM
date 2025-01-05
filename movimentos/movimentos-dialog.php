<?php
    $items = [
        ["documento" ,"Documento"  ],
        ["emissao"   ,"Emissão"    ],
        ["descricao" ,"Descrição"  ],
        ["fornecedor","Fornecedor" ],
        ["valor"     ,"Valor"      ],
        ["vencimento","Vencimento" ],
        ["pagamento" ,"Pagamento"  ],
        ["conta"     ,"Conta"      ],
        ["obs"       ,"Observações"]
    ];
    $items_html = "<input type='text' id='id' value='' hidden> ";
    foreach($items as $item){
        $items_html .= "<label for='{$item[0]}'><b>{$item[1]}:</b></label>";
        $items_html .= "<input class='form-control form-control-sm mb-2' type='text' id='{$item[0]}' value='' >";
    }
?>
<div class="modal modal-lg" tabindex="-1" id="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-light bg-primary">
                <h5 class="modal-title"><span id="dialog-title">ALTERAR <?=$menu?></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="<?=$page?>-id" hidden></span>
                <?=$items_html?>
            </div>
            <div class="modal-footer bg-secondary-subtle">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="update_note('<?=$page?>')">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>
