<?php

require_once("db-connection.php");
$empresa = $_GET["empresa"] ?? "MATRIZ";
$quitada = $_GET["quitada"] ?? "";
$vencida = $_GET["vencida"] ?? "";
$hoje = $_GET["hoje"] ?? "";
$amanha = $_GET["amanha"] ?? "";
$semana = $_GET["semana"] ?? "";
$proxima = $_GET["proxima"] ?? "";
$breve = $_GET["breve"] ?? "";
$empresa_filter = $_GET["empresa_filter"] ?? "";
$search = $_REQUEST['search']['value'] ?? "";
$columns = array(
    null,
    "id",
    "vencimento",
    "pagamento",
    "valor",
    "tipo",
    "conta",
    "emissao",
    "documento",
    "fornecedor",
    "descricao",
    "vence",
    "empresa",
    "obs");
$sql_cols = "id,vencimento,pagamento,valor,tipo,conta,emissao,documento,fornecedor,descricao,vence,empresa,obs";
$sql_from = "FROM vw_movimentos";
$sql_where = "WHERE empresa='{$empresa}' ";
$vence = [];
if($quitada=="true"){
    $vence[]=0 ;
}
if($vencida=="true"){
    $vence[]=1 ;
}
if($hoje=="true"){
    $vence[]=2 ;
}
if($amanha=="true"){
    $vence[]=3 ;
}
if($semana=="true"){
    $vence[]=4 ;
}
if($proxima=="true"){
    $vence[]=5 ;
}
if($breve=="true"){
    $vence[]=6 ;
}

$vence = implode(",", $vence);
if($vence!=""){
    $sql_where .= " AND vence IN ($vence) ";
}

if($empresa_filter!=""){
    $sql_where .= " AND empresa = '{$empresa_filter}' ";
}


// se houver um parâmetro de pesquisa, $requestData['search']['value'] contém o parâmetro de pesquisa
if($search != ""){
    $sql_where .= " AND (";
    $sql_where .= " vencimento LIKE '%".$search."%' OR";
    $sql_where .= " conta LIKE '%".$search."%' OR";
    $sql_where .= " documento LIKE '%".$search."%' OR";
    $sql_where .= " fornecedor LIKE '%".$search."%' OR";
    $sql_where .= " descricao LIKE '%".$search."%' )";
}
//Ordenar o resultado
if(isset($_REQUEST["order"][0]["column"])){
    $sql_order = " ORDER BY "
        .$columns[$_REQUEST["order"][0]["column"]]."   "
        .$_REQUEST['order'][0]['dir'];
}else{
    $sql_order = " ORDER BY ID";
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
    $data_col[] = $data_row["id"];
    $data_col[] = $data_row["vencimento"];
    $data_col[] = $data_row["pagamento"];
    $data_col[] = $data_row["valor"];
    $data_col[] = $data_row["tipo"];
    $data_col[] = $data_row["conta"];
    $data_col[] = $data_row["emissao"];
    $data_col[] = $data_row["documento"];
    $data_col[] = $data_row["fornecedor"];
    $data_col[] = $data_row["descricao"];
    $data_col[] = $data_row["vence"];
    $data_col[] = $data_row["empresa"];
    $data_col[] = $data_row["obs"];
    $data[] = $data_col;
}

$result = [
    "recordsTotal" => intval($total_rows['total_rows']), // Quantidade de registros que há no banco de dados
    "recordsFiltered" => intval($total_rows['total_rows']), // Total de registros quando houver pesquisa
    "data" => $data // Array de dados com os registros retornados da tabela usuarios
];
echo json_encode($result);
?>