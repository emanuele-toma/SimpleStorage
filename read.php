<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once 'settings.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');


$mode = $_GET['mode'];


if ($mode != "inventario" && $mode != "ordini") {
    http_response_code(400);
    echo json_encode("Errore durante la richiesta.");
    exit();
}


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


$limit = $_GET['limit'] ?? 10;


$offset = $_GET['offset'] ?? 0;


$first_query = "SELECT * FROM ".$mode." WHERE proprietario = ".$_SESSION['userid']." ORDER BY personal_id ASC LIMIT ".$limit." OFFSET ".$offset;


$first_data = $conn->query($first_query);


if (!$first_data) {
    
    $debug_file = fopen("debug.txt", "a");
    fwrite($debug_file, "Query: " . $first_query . "\nError: " . $conn->error . "\n");
    fclose($debug_file);

    http_response_code(500);
    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


$first_data = $first_data->fetch_all(MYSQLI_ASSOC);


if ($mode == "inventario") {
    
    foreach ($first_data as &$row) {
        
        $row['prezzo'] = number_format($row['prezzo'], 2, ".", "") . " €";

        
        unset($row['id']);

        
        $row['id'] = $row['personal_id'];

        
        unset($row['personal_id']);

        unset($row['proprietario']);
    }

    
    $total_rows = $conn->query("SELECT COUNT(*) FROM inventario WHERE proprietario = ".$_SESSION['userid'])->fetch_row()[0];

    

    
    http_response_code(200);
    echo json_encode(array("data" => $first_data, "length" => $total_rows));
    exit();
}


if ($mode == "ordini") {
    
    $second_query = "SELECT ordini.*, inventario.nome_prodotto FROM ordini JOIN inventario ON ordini.id_prodotto = inventario.personal_id WHERE ordini.proprietario = ".$_SESSION['userid']."  ORDER BY personal_id ASC LIMIT ".$limit." OFFSET ".$offset;

    
    $second_data = $conn->query($second_query);

    
    if (!$second_data) {
        http_response_code(500);
        echo json_encode("Si è verificato un errore, riprova più tardi.");

        exit();
    }

    
    $second_data = $second_data->fetch_all(MYSQLI_ASSOC);

    
    foreach ($second_data as &$row) {
        
        $row['prezzo'] = number_format($row['prezzo'], 2, ".", "") . " €";

        
        $row['data_ordine'] = date("d/m/Y", strtotime($row['data_ordine']));
        
        
        unset($row['id']);

        
        $row['id'] = $row['personal_id'];

        
        unset($row['personal_id']);

        unset($row['proprietario']);
        unset($row['id_prodotto']);
    }

    
    $total_rows = $conn->query("SELECT COUNT(*) FROM ordini WHERE proprietario = ".$_SESSION['userid'])->fetch_row()[0];

    
    http_response_code(200);
    echo json_encode(array("data" => $second_data, "length" => $total_rows));
    exit();
}


$conn->close();

?>