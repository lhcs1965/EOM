<?php

include_once "../db/db-connection.php";

$ids = $_POST["ids"];

$sql = "DELETE FROM movimentos WHERE id in ($ids) ";
$cursor = $conn->prepare($sql);
$cursor->execute();
$cursor->execute();

$result = [
    "status" => true,
    "msg" => "ok"];

echo json_encode($result);


