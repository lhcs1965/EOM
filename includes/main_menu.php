<?php
    $menu_items = [
        ["Movimentos","movimentos.php"],
        // ["Resumo"    ,"resumo.php"],
        ["Contas"    ,"contas.php"],
        // ["Tema Claro","includes/theme.php"]
    ];
    $menu_html = "";
    foreach($menu_items as $menu_item){
        $menu_html .= '<li><a class="dropdown-item" href="'.$menu_item[1].'">'.$menu_item[0].'</a></li>';
    }
?>

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
