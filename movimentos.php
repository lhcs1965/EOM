<!DOCTYPE html>
<html lang="pt-br">
    <?php
        $page = "movimentos";
        $menu = $page; //$_GET["menu"];
        $over = $page; //$_GET["over"];
        $local_filter = $_GET["local_filter"] ?? null;
        $owner_filter = $_GET["owner_filter"] ?? null;
        $table_filter = $_GET["table_filter"] ?? null;
        $field_filter = $_GET["field_filter"] ?? null;
        $empty_filter = $_GET["empty_filter"] ?? null;
        $menu_items = [
            ["Inicio"   ,"index.php"],
            ["Movimentos","movimentos.php"],
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <link rel='stylesheet' href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
        <!-- <link rel='stylesheet' href='/css/dataTables.dataTables.css'> -->
        <link rel='stylesheet' href='css/dic.css'>
    </head>
    <body>
        <div class="d-flex flex-row p-3 fixed-top justify-content-between align-items-center bg-success text-bg-primary fw-bold">
            <div class="col-sm-auto">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-light" onclick="import_objects('delete','<?=$page?>')" data-toggle="tooltip" title="Importar <?=$over?> dos <?=$menu?> selecionados">
                        <i class="bi bi-file-earmark-arrow-down"></i>    
                    </button>
                    <button type="button" class="btn btn-light" onclick="delete_objects('delete','<?=$page?>')" data-toggle="tooltip" title="Excluir <?=$menu?> selecionados com todas as <?=$over?>">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
            <div class="col-sm-auto text-light">
                <span id="show-hide-sigrah" <?php if($page=="local"){echo "hidden";}else{echo "";}?> >
                    &#160;<span id="sigrah-filter"></span>
                    <a href="javascript:void(0)" onclick="set_filter('local_filter','SIGRAH.pbh')" class="btn btn-sm btn-outline-light text-decoration-none fw-bold">
                        SIGRAH
                    </a>
                </span>
                <span id="show-hide-empty" <?php if($page=="local" or $page=="owner"){echo "hidden";}else{echo "";}?> >
                    &#160;
                    tabelas vazias
                    <input type="checkbox" id="empty-filter" value="" onclick="btn_empty()">
                    &#160;
                </span>
                <span id="hide-local-filter" hidden>
                    &#160;
                    <b>servidor=[&#160;<span class="text-warning" id="local_filter"><?=$local_filter?></span>&#160;]</b>
                    <a href="javascript:void(0)" onclick="set_filter('local_filter','')" class="text-warning text-decoration-none">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                <span id="hide-owner-filter" hidden>
                    &#160;
                    <b>schema=[&#160;<span class="text-warning" id="owner_filter"><?=$owner_filter?></span>&#160;]</b>
                    <a href="javascript:void(0)" onclick="set_filter('owner_filter','')" class="text-warning text-decoration-none">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                <span id="hide-table-filter" hidden> 
                    &#160;
                    <b>tabela=[&#160;<span class="text-warning" id="table_filter"><?=$table_filter?></span>&#160;]</b>
                    <a href="javascript:void(0)" onclick="set_filter('table_filter','')" class="text-warning text-decoration-none">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
                <span id="hide-field-filter" hidden>
                    &#160;
                    <b>coluna=[&#160;<span class="text-warning" id="field_filter"><?=$field_filter?></span>&#160;]</b>
                    <a href="javascript:void(0)" onclick="set_filter('field_filter','')" class="text-warning text-decoration-none">
                        <i class="bi bi-x"></i>
                    </a>
                </span>
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
        <div class='container-fluid mt-4 pt-5 px-4'>
            <br>
            <table id='data-table' class='table display nowarp ' style='width:100%'>
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

        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="js/<?=$page?>.js"></script>
    </body>
</html>

