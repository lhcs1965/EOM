<?php

include_once "../db/db-connection.php";

function get_data($conn,$items){
    $sql = "SELECT id, cpf_cnpj, razao_social, nome_fantasia FROM fornecedores WHERE cpf_cnpj='{$items[0]}'";

    $cursor = $conn->prepare($sql);
    $cursor->execute();
    $data=["","","",0];
    while($data_row = $cursor->fetch(PDO::FETCH_ASSOC)){
        $data[0] = $data_row["cpf_cnpj"];
        $data[1] = $data_row["razao_social"];
        $data[2] = $data_row["nome_fantasia"];
        $data[3] = $data_row["id"];
    }
    return $data;    
}

$items = $_POST["fornecedor"];
$items = explode("|",$items);
$data = get_data($conn, $items);

if($data[3]==0){
    $sql = "
        INSERT INTO fornecedores (cpf_cnpj, razao_social, nome_fantasia) 
        VALUES ('{$items[0]}','{$items[1]}','{$items[2]}')
    ";
    $cursor = $conn->prepare($sql);
    $cursor->execute();
    // $data[3] = $conn->lastInsertId();
    $data = get_data($conn,$items);
}elseif($data[1]!=$items[1] or $data[2]!=$items[2]){
    $sql = "
        UPDATE fornecedores 
        SET razao_social = '{$items[1]}', nome_fantasia = '{$items[2]}'
        WHERE cpf_cnpj = '{$data[0]}'; 
    ";
    $cursor = $conn->prepare($sql);
    $cursor->execute();
    $data = get_data($conn,$items);
}

$fornecedor_id=$data[3];
$emissao = $items[3];

$faturas = explode(";",$_POST["fatura"]);
$documento = array_shift($faturas);
$documento = explode("|",$documento);
$documento = $documento[0];

$futuras = [];
$sql = "
    INSERT INTO movimentos (fornecedor_id,documento,emissao,vencimento,valor) VALUES ";
foreach($faturas as $item){
    $item = explode("|",$item);
    if($item[0]!=""){
        $sql .= "($fornecedor_id,'$documento/{$item[0]}','$emissao','{$item[1]}',{$item[2]})";
        $futuras[] = $item;
    }
}   
$sql = str_replace(")(","),(",$sql).";";
$cursor = $conn->prepare($sql);
$cursor->execute();
    $result = [
        "status" => true,
        "msg" => "ok"];

echo json_encode($result);




