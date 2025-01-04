<?php

include_once "../db/db-connection.php";

$field = $_POST["field"];
$action = $_POST["action"];
$ids = $_POST["ids"];

if($field == "conta") {
    $field = "conta_id";
} else {
    $field = "pagamento";
}

if($action=="vencimento"){
    $value = "vencimento";
} else if($action== "hoje"){
    $value = "CURDATE()";
} else if($action== "ontem"){
    $value = "DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
} else if($action== "credito"){
    $value = "(SELECT conta_credito_id FROM contas_padrao LIMIT 1)";
} else if($action== "debito"){
    $value = "(SELECT conta_debito_id FROM contas_padrao LIMIT 1)";
}

$sql = "
    UPDATE
        movimentos
    SET
        {$field} = {$value}
    WHERE
        id IN ({$ids})";
// print_r($sql);

$cursor = $conn->prepare($sql);
$cursor->execute();
$result = [
    "status" => true,
    "msg" => "ok"];

echo json_encode($result);
