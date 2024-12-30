<!DOCTYPE html>
<html lang="pt-br">
    <?php
        $page = "movimentos";
        $menu = strtoupper($page); //$_GET["menu"];
        $over = $page; //$_GET["over"];
        $local_filter = $_GET["local_filter"] ?? null;
        $owner_filter = $_GET["owner_filter"] ?? null;
        $table_filter = $_GET["table_filter"] ?? null;
        $field_filter = $_GET["field_filter"] ?? null;
        $empty_filter = $_GET["empty_filter"] ?? null;
        $menu_items = [
            ["Inicio"   ,"index.php"],
            ["MOVIMENTOS","movimentos.php"],
            ["Resumo"   ,"resumo.php"],
            ["Contas"   ,"contas.php"],
        ];
        $menu_html = "";
        foreach($menu_items as $menu_item){
            $menu_html .= '<li><a class="dropdown-item" href="'.$menu_item[1].'">'.$menu_item[0].'</a></li>';
        }
    ?>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>FC - <?=$menu?></title>
        <link rel='stylesheet' href='css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' href='css/datatables/dataTables.bootstrap5.css'>
        <link rel='stylesheet' href='css/datatables/dataTables.dataTables.css'>
        <link rel='stylesheet' href='css/custom.css'>
    </head>
    <body>
        <div class="d-flex flex-row p-3 fixed-top justify-content-between align-items-center bg-success text-bg-primary">
            <div class="col-sm-auto">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-light" onclick="import_objects('delete','<?=$page?>')" data-toggle="tooltip" title="Importar arquivox XML das NFe">
                        <i class="bi bi-file-earmark-arrow-down"></i>
                        Importar NFe    
                    </button>
                </div>
            </div>
            <div class="col-sm-auto text-light">
                <a href="javascript:void(0)" onclick="set_filter('local_filter','SIGRAH.pbh')" class="btn btn-sm btn-outline-light text-decoration-none fw-bold">
                    CONTAS
                </a>
            </div>
            
            <div class="col-sm-auto">
            <span class="h6">VENCE [</span>
                <input class="form-check-input" type="checkbox" id="cb-quitada" value="" onclick="set_fix_filter()">
                Quitadas
                &#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-vencida" value="" onclick="set_fix_filter()" checked>
                Vencidas
                &#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-hoje" value="" onclick="set_fix_filter()" checked>
                Hoje
                &#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-amanha" value="" onclick="set_fix_filter()" checked>
                Amanhã
                &#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-semana" value="" onclick="set_fix_filter()" checked>
                Nesta semana
                &#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-proxima" value="" onclick="set_fix_filter()" checked>
                Na próxima semana&#160;&#160;
                <input class="form-check-input" type="checkbox" id="cb-breve" value="" onclick="set_fix_filter()" checked>
                Em breve
                <span class="h6">]</span>
            </div>
            
            <div class="col-sm-auto">
                <div class="dropdown">
                    <a href="#" class="text-light text-decoration-none dropdown-toggle" role="button" data-bs-toggle="dropdown">
                        <?=$menu?>
                    </a>
                    <ul class="dropdown-menu">
                        <?=$menu_html?>
                    </ul>
                </div>
            </div>
        </div>
        <div class='container-fluid mt-5 pt-5 px-4'>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Aplicar ações sobre as linhas selecionadas
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" type="button">Pago na data de vencimento</button></li>
                    <li><button class="dropdown-item" type="button">Pago na data de hoje</button></li>
                    <li><button class="dropdown-item" type="button">Pago na data de ontem</button></li>
                    <li><button class="dropdown-item" type="button">Aplicar conta crédito padrão</button></li>
                    <li><button class="dropdown-item" type="button">Aplicar conta débito padrão</button></li>
                </ul>
            </div>
            <table id='data-table' class='table display nowarp small table-hover' style='width:100%'>
                <thead></thead>
                <tbody></tbody>
            </table>
            <br>
        </div>
        <div class="modal modal-xl" tabindex="-1" id="note-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span id="note-dialog-title">ALTERAR <?=$menu?></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="<?=$page?>-id" hidden></span>
                        <div class="row g-1 m-1">
                            <div class="col md-3" <?php if($page!="local"){echo "hidden";}else{echo "";}?> >
                                <label for="local-host"><b>LOCAL:</b></label>
                                <input class="form-control" type="text" id="local-host" value="" >
                            </div>
                            <div class="col md-3" >
                                <label for="local-name"><b>SERVIDOR:</b></label>
                                <input class="form-control" type="text" id="local-name" value="" <?php if($page!="local"){echo "disabled";}else{echo "";}?> >
                            </div>
                            <div class="col md-3" <?php if($page!="local"){echo "hidden";}else{echo "";}?> >
                                <label for="local-type"><b>TIPO:</b></label>
                                <input class="form-control" type="text" id="local-type" value="" >
                            </div>
                            <div class="col md-3" <?php if($page!="local"){echo "hidden";}else{echo "";}?> >
                                <label for="local-user"><b>LOGIN:</b></label>
                                <input class="form-control" type="text" id="local-user" value="" >
                            </div>
                            <div class="col md-3" <?php if($page!="local"){echo "hidden";}else{echo "";}?> >
                                <label for="local-pass"><b>SENHA:</b></label>
                                <input class="form-control" type="password" id="local-pass" value="" >
                            </div>
                            <div class="col md-3" <?php if($page=="local"){echo "hidden";}else{echo "";}?>>
                                <label for="owner-name"><b>SCHEMA:</b></label>
                                <input class="form-control" type="text" id="owner-name" value="" disabled>
                            </div>
                            <div class="col md-3" <?php if($page=="local" or $page=="owner"){echo "hidden";}else{echo "";}?>>
                                <label for="table-name"><b>TABELA:</b></label>
                                <input class="form-control" type="text" id="table-name" value="" disabled>
                            </div>
                            <div class="col md-3" <?php if($page=="local" or $page=="owner" or $page=="table"){echo "hidden";}else{echo "";}?>>
                                <label for="field-name"><b>COLUNA:</b></label>
                                <input class="form-control" type="text" id="field-name" value="" disabled>
                            </div>
                        </div>
                        <div class="row g-1 m-1">
                            <!-- <label for="<?=$page?>-note"><b>DESCRIÇÃO:</b></label> -->
                            <input class="form-control" type="text" id="<?=$page?>-note" value="" placeholder="incluir descrição">
                            <br><br>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab-form" role="tablist">
                                    <button class="nav-link active" id="nav-osql-tab" data-bs-toggle="tab" data-bs-target="#nav-osql-form" type="button" role="tab" aria-controls="nav-home"    aria-selected="true" ><b>SQL para Schemas</b></button>
                                    <button class="nav-link"        id="nav-tsql-tab" data-bs-toggle="tab" data-bs-target="#nav-tsql-form" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><b>SQL para Tabelas</b></button>
                                    <button class="nav-link"        id="nav-fsql-tab" data-bs-toggle="tab" data-bs-target="#nav-fsql-form" type="button" role="tab" aria-controls="nav-contact" aria-selected="false"><b>SQL para Colunas</b></button>
                                </div>
                            </nav>
                            <div class="font-monospace">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane show active" id="nav-osql-form" role="tabpanel" aria-labelledby="nav-osql-tab">
                                        <textarea class="form-control" rows="13" style="font-size: 8pt" id="<?=$page?>-osql" value="" placeholder="incluir SQL"></textarea>
                                    </div>
                                    <div class="tab-pane"             id="nav-tsql-form" role="tabpanel" aria-labelledby="nav-tsql-tab">
                                        <textarea class="form-control" rows="13" style="font-size: 8pt" id="<?=$page?>-tsql" value="" placeholder="incluir SQL"></textarea>
                                    </div>
                                    <div class="tab-pane"             id="nav-fsql-form" role="tabpanel" aria-labelledby="nav-fsql-tab">
                                        <textarea class="form-control" rows="13" style="font-size: 8pt" id="<?=$page?>-fsql" value="" placeholder="incluir SQL"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="update_note('<?=$page?>')">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="search-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">IMPORTANDO <?=$over?></h5>
                    </div>
                    <div class="modal-body">
                        <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="p-bar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="confirm-dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span id="cd-header"></span></h5>
                    </div>
                    <div class="modal-body">
                        <span id="cd-body"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"  data-bs-dismiss="modal" onclick="confirm()">Continuar</button>
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal" >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src='js/jquery-3.7.1.js'></script>
        <script src='js/bootstrap/bootstrap.bundle.min.js'></script>
        <script src='js/datatables/dataTables.js'></script>
        <script src="js/datatables/dataTables.bootstrap5.js"></script>
        <script src="js/datatables/moment.min.js"></script>
        <!-- <script src="js/datatables/dateTime.js"></script> -->
        <script src="js/<?=$page?>.js"></script>
    </body>
</html>

