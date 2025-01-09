<div class="col-sm-auto">
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Situação:
        </button>
        <ul class="dropdown-menu p-2">
            <li>
                <input class="form-check-input" type="checkbox" id="cb-vencida" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-vencida">Vencidas</label>
            </li>
            <li>
                <input class="form-check-input" type="checkbox" id="cb-quitada" value="" onclick="set_fix_filter()">
                <label class="form-check-label" for="cb-quitada">Quitadas</label>    
            </li>
        </ul>
    </div>    
</div>
<div class="col-sm-auto">
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Vencimento:
        </button>
        <ul class="dropdown-menu p-2">
            <li>
                <input class="form-check-input" type="checkbox" id="cb-hoje" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-hoje">Hoje</label>
            </li>
            <li>
            <input class="form-check-input" type="checkbox" id="cb-amanha" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-amanha">Amanhã</label>    
            </li>
            <li>
            <input class="form-check-input" type="checkbox" id="cb-semana" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-semana">Nesta Semana</label>    
            </li>
            <li>
                <input class="form-check-input" type="checkbox" id="cb-proxima" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-proxima">Próxima Semana</label>    
            </li>
            <li>
                <input class="form-check-input" type="checkbox" id="cb-breve" value="" onclick="set_fix_filter()" checked>
                <label class="form-check-label" for="cb-breve">Em Breve</label>    
            </li>
        </ul>
    </div>    
</div>
<div class="col-sm-auto">
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Listar somente:
        </button>
        <ul class="dropdown-menu p-2">
            <li>
                <input class="form-check-input" type="checkbox" id="cb-conta" value="" onclick="set_fix_filter()" >
                <label class="form-check-label" for="cb-conta">Sem Conta</label>
            </li>
            <li>
                <input class="form-check-input" type="checkbox" id="cb-fornecedor" value="" onclick="set_fix_filter()" >
                <label class="form-check-label" for="cb-fornecedor">Sem Fornecedor</label>    
            </li>
        </ul>
    </div>    
</div>
