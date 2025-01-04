<div class="dropdown">
    <button class="btn btn-md btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
