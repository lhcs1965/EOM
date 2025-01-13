<div class="d-flex gap-2 mb-3">
    <div class="btn-group" role="group">
        <button class="btn btn-secondary" type="button" onclick="insert()">
            <svg class="bi bi-file" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
            </svg>
        </button>
        <button class="btn btn-secondary" type="button" onclick="import_nfe()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
            </svg>
        </button>
    </div>
    <div class="dropdown">
        <button class="btn btn-md btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Aplicar ações sobre as linhas selecionadas
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><button class="dropdown-item" type="button" onclick="apply_action('pagamento','vencimento')">Pago na data de vencimento</button></li>
            <li><button class="dropdown-item" type="button" onclick="apply_action('pagamento','hoje')">Pago na data de hoje</button></li>
            <li><button class="dropdown-item" type="button" onclick="apply_action('pagamento','ontem')">Pago na data de ontem</button></li>
            <li><button class="dropdown-item" type="button" onclick="apply_action('conta','credito')">Aplicar conta crédito padrão</button></li>
            <li><button class="dropdown-item" type="button" onclick="apply_action('conta','debito')">Aplicar conta débito padrão</button></li>
        </ul>
    </div>
</div>