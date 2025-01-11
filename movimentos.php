<!DOCTYPE html>
<html lang="pt-br">
    <?php
        //data-bs-theme="dark"
        $empresa = $_GET["empresa"] ?? "MATRIZ"; 
        $page = "movimentos";
        $menu = strtoupper($page); 
        require_once "includes/header.php";
    ?>
    <body>
        <div class="d-flex flex-row p-3 fixed-top justify-content-between align-items-center bg-success text-bg-primary">
            <?php require_once "includes/main_menu.php"; ?>
            <?php require_once "includes/actions.php"; ?>
            <?php require_once "$page/$page-filters.php"; ?>
            <div class="col-md-auto"></div>
            <div class="col-sm-auto text-light">
                <a href="javascript:void(0)" onclick="troca_empresa()" class="btn btn-sm btn-outline-light text-decoration-none fw-bold">
                    <span id="empresa"><?=$empresa?></span>
                </a>
            </div>
        </div>
        <div class='container-fluid mt-5 pt-5 px-4'>
            <?php require_once "$page/$page-actions.php"; ?>
            <table id='data-table' class='table table-hover table-striped small' style='width:100%'>
                <thead></thead>
                <tbody></tbody>
            </table>
            <br>
        </div>

        <?php require_once "$page/$page-dialog.php"; ?>
        <!-- <php require_once "includes/$page/dialog2.php"; ?> -->
        <!-- <php require_once "includes/confirm-dialog.php"; ?> -->
        <?php require_once "includes/footer.php"; ?>
        <script src="<?=$page?>/<?=$page?>.js"></script>
        <script src="<?=$page?>/<?=$page?>-dialog.js"></script>
    </body>
</html>

