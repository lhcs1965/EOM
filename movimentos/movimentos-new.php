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

// if($data[3]==0){
//     $sql = "
//         INSERT INTO fornecedores (cpf_cnpj, razao_social, nome_fantasia) 
//         VALUES ('{$items[0]}','{$items[1]}','{$items[2]}')
//     ";
//     $cursor = $conn->prepare($sql);
//     $cursor->execute();
//     // $data[3] = $conn->lastInsertId();
//     $data = get_data($conn,$items);
// }elseif($data[1]!=$items[1] or $data[2]!=$items[2]){
//     $sql = "
//         UPDATE fornecedores 
//         SET razao_social = '{$items[1]}', nome_fantasia = '{$items[2]}'
//         WHERE cpf_cnpj = '{$data[0]}'; 
//     ";
//     $cursor = $conn->prepare($sql);
//     $cursor->execute();
//     $data = get_data($conn,$items);
// }
$fornecedor_id=$data[3];
$emissao = $items[3];

$faturas = explode(";",$_POST["fatura"]);
$documento = array_shift($faturas);
$documento = explode("|",$documento);
$documento = $documento[0];

$futuras = [];
foreach($faturas as $item){
    $item = explode("|",$item);
    $item[0] = "{$documento}/{$item[0]}";
    $futuras[] = $item;
}

// $items="267512/|229.00|0.00;001|2025-01-06|38.20;002|2025-02-03|38.16;003|2025-03-05|38.16;004|2025-04-07|38.16;005|2025-05-05|38.16;006|2025-06-05|38.16;267512/|229.00|0.00;001|2025-01-06|38.20;002|2025-02-03|38.16;003|2025-03-05|38.16;004|2025-04-07|38.16;005|2025-05-05|38.16;006|2025-06-05|38.16;";
echo "<pre>";
var_dump($documento,$futuras);
// $faturas = explode(";",$items);
// $documento = $documento[0];

// print_r($proximas);
echo "</pre>";

// $id = $conn->lastInsertId();
//     $result = [
//         "status" => true,
//         "msg" => $id];

// echo json_encode($result);




