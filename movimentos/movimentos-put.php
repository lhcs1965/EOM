<?php

include_once "../db/db-connection.php";

function put_action($conn){
    $action = $_POST["action"] ?? "";
    $field = $_POST["field"] ?? "";
    $ids = $_POST["ids"] ?? "";
    
    $field = $field == "conta" ? "conta_id" : "pagamento";

    switch ($action) {
        case "vencimento":
            $value = "vencimento";
            break;
        case "hoje":
            $value = "CURDATE()";
            break;
        case "ontem":
            $value = "DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
            break;
        case "credito":
            $value = "(SELECT conta_credito_id FROM contas_padrao LIMIT 1)";
            break;
        case "debito":
            $value = "(SELECT conta_debito_id FROM contas_padrao LIMIT 1)";
            break;
    }

    $sql = "UPDATE movimentos SET {$field} = {$value} WHERE id IN ({$ids})";

    $cursor = $conn->prepare($sql);
    $cursor->execute();
    $result = [
        "status" => true,
        "msg" => "ok"];

    echo json_encode($result);
}

function put($conn){
    $id = $_POST["id"];
    $field = $_POST["field"];
    $value = $_POST["value"];
    $type = $_POST["type"] ?? "str";
    
    if($id==0){
        $sql = "INSERT INTO movimentos (documento) VALUES (null) ";
        $cursor = $conn->prepare($sql);
        $cursor->execute();
        $id = $conn->lastInsertId();
    }

    $sql = "UPDATE movimentos SET $field = :value WHERE id = $id";
    
    $cursor = $conn->prepare($sql);
    if($type=="str"){
        $cursor->bindParam(":value" ,$value,PDO::PARAM_STR);
    } else {
        $cursor->bindParam(":value",$value);
    }    

    $cursor->execute();
    $result = [
        "status" => true,
        "msg" => $id];

    echo json_encode($result);
}

if(isset($_POST["action"])){
    put_action($conn);
} else {
    put($conn);
}


