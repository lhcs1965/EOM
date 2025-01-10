<?php
    include_once "db/db-connection.php";
    $cursor = $conn->prepare("SELECT id, nome FROM contas ");
    $cursor->execute();
    $html_select="";
    while($item = $cursor->fetch(PDO::FETCH_ASSOC)){
        $id = $item["id"];
        $nome = $item["nome"];
        $html_select .= "<option value='$id'>$nome</option>";
    }
?>

<div class="modal " tabindex="-1" id="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="dialog-title">ALTERAR <?=$menu?></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="<?=$page?>-id" hidden></span>
                <input type='text' id='id' value='0' hidden>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="documento"><strong>Documento:</strong></label>
                    </div>
                    <div class="col">
                        <input class="form-control form-control-sm" type="text" id="documento" value="" onblur="save('documento',0)">
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="emissao"><strong>Emissão:</strong></label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="date" id="emissao" value="" onblur="save('emissao',1)" required>
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="fornecedor"><strong>Fornecedor:</strong></label>
                    </div>
                    <div class="col">
                        <input disabled class="form-control form-control-sm" type="text" id="fornecedor" value="" onblur="save('fornecedor',0)">
                     </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="descricao"><strong>Descrição:</strong></label>
                    </div>
                    <div class="col">
                        <input class="form-control form-control-sm" type="text" id="descricao" value="" onblur="save('descricao',0)">
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="valor"><strong>Valor:</strong></label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="number" id="valor" value="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="save('valor',1)" required>
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="vencimento"><strong>Vencimento:</strong></label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="date" id="vencimento" value="" onblur="save('vencimento',1)" required>
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="pagamento"><strong>Pagamento:</strong></label>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control form-control-sm" type="date" id="pagamento" value="" onblur="save('pagamento',0)">
                    </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="conta"><strong>Conta:</strong></label>
                    </div>
                    <div class="col">
                        <input disabled class="form-control form-control-sm" type="text" id="conta" value="" onblur="save('conta',0)">
                        <select class="form-select"aria-label="Default select example">
                            <?=$html_select?>
                        </select>
                     </div>
                </div>
                <div class="row g-1 m-1 mb-2">
                    <div class="col-3">
                        <label for="obs"><strong>Observações:</strong></label>
                    </div>
                    <div class="col">
                        <input class="form-control form-control-sm" type="text" id="obs" value="" onblur="save('obs',0)">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
