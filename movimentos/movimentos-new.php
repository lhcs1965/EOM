<?php

include_once "../db/db-connection.php";

$sql = "INSERT INTO movimentos (documento) VALUES (null) ";

$cursor = $conn->prepare($sql);
$cursor->execute();
$id = $conn->lastInsertId();
    $result = [
        "status" => true,
        "msg" => $id];

echo json_encode($result);




