<?php

require_once("../db/db-connection.php");
$empresa = $_GET["empresa"] ?? "MATRIZ";
$search = $_REQUEST['search']['value'] ?? "";
$columns = array("ano", "conta", "janeiro", "fevereiro", "marco", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "13", "total");
$sql_cols = "ano, conta, janeiro, fevereiro, marco, abril, maio, junho, julho, agosto, setembro, outubro, novembro, dezembro, total";
$sql_from = "FROM vw_contas_mensal ";

$sql_where = $sql_where = "WHERE empresa_id=1 ";

// if($empresa_filter!=""){
//     $sql_where .= " AND empresa = '{$empresa_filter}' ";
// }


// se houver um parâmetro de pesquisa, $requestData['search']['value'] contém o parâmetro de pesquisa
if($search != ""){
    $sql_where .= " AND (";
    // $sql_where .= " vencimento LIKE '%".$search."%' OR";
    $sql_where .= " conta LIKE '%$search%' OR";
    $sql_where .= "   ano LIKE '%$search%' )";
}
//Ordenar o resultado
if(isset($_REQUEST["order"][0]["column"])){
    $sql_order = " ORDER BY "
        .$columns[$_REQUEST["order"][0]["column"]]."   "
        .$_REQUEST['order'][0]['dir'];
}else{
    $sql_order = " ORDER BY ano DESC, conta ";
}
//limit
if(isset($_REQUEST['start']) && $_REQUEST['length'] != -1) {
    $sql_limit = "LIMIT ".intval($_REQUEST['start']).", ".intval($_REQUEST['length']);
}else{
    $sql_limit = "";
}
$sql = "SELECT COUNT(1) AS total_rows {$sql_from} {$sql_where}";
$cursor = $conn->prepare($sql);
$cursor->execute();
$total_rows = $cursor->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT {$sql_cols} {$sql_from} {$sql_where} {$sql_order} {$sql_limit} ";

$cursor = $conn->prepare($sql);
$cursor->execute();
$data=null;
while($data_row = $cursor->fetch(PDO::FETCH_ASSOC)){
    $data_col = [];
    $data_col[] = $data_row["ano"];
    $data_col[] = $data_row["janeiro"];
    $data_col[] = $data_row["fevereiro"];
    $data_col[] = $data_row["marco"];
    $data_col[] = $data_row["abril"];
    $data_col[] = $data_row["maio"];
    $data_col[] = $data_row["junho"];
    $data_col[] = $data_row["julho"];
    $data_col[] = $data_row["agosto"];
    $data_col[] = $data_row["setembro"];
    $data_col[] = $data_row["outubro"];
    $data_col[] = $data_row["novembro"];
    $data_col[] = $data_row["dezembro"];
    $data_col[] = $data_row["total"];
    $data_col[] = $data_row["conta"];
    $data[] = $data_col;
}

$result = [
    "recordsTotal" => intval($total_rows['total_rows']), // Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($total_rows['total_rows']), // Total de registros quando houver pesquisa
    "data" => $data // Array de dados com os registros retornados da tabela usuarios
];
echo json_encode($result);