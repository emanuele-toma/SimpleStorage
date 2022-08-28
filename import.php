<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once('settings.php');


header('Content-Type: application/json');


$file_inv = $_FILES['inventario'];
$file_ord = $_FILES['ordini'];


if ($file_inv['size'] == 0 && $file_ord['size'] == 0) {
    http_response_code(400);

    echo json_encode("Errore durante la richiesta.");
    exit();
}


if ($file_inv['error'] != 0 || $file_ord['error'] != 0) {
    http_response_code(400);

    echo json_encode("Errore durante la richiesta.");
    exit();
}


$csv_inv = array_map('str_getcsv', file($file_inv['tmp_name']));
$csv_ord = array_map('str_getcsv', file($file_ord['tmp_name']));

$personal_id_inv = 0;
$personal_id_ord = 0;

$ids_inv = array();

foreach ($csv_inv as $row) {
    
    if ($row[0] == "id") {
        continue;
    }

    if (count($row) != 6) {
        http_response_code(400);

        echo json_encode("File inventario non valido. (numero di colonne non valido)");
        exit();
    }

    if (in_array($row[0], $ids_inv)) {
        http_response_code(400);

        echo json_encode("File inventario non valido. (id duplicato)");
        exit();
    }
    
    
    if (!is_numeric($row[0]) || !is_numeric($row[4]) || !is_numeric($row[5])) {
        http_response_code(400);

        echo json_encode("File inventario non valido. (id, prezzo e/o quantita non numerici)");
        exit();
    }

    $ids_inv[] = $row[0];

    
    if ($row[0] > $personal_id_inv) {
        $personal_id_inv = $row[0];
    }
}

$ids_ord = array();

foreach ($csv_ord as $key => $row) {
    
    if ($row[0] == "id") {
        continue;
    }

    if (count($row) != 7) {
        http_response_code(400);

        echo json_encode("File ordini non valido. (numero di colonne non valido)");
        exit();
    }

    if (in_array($row[0], $ids_ord)) {
        http_response_code(400);

        echo json_encode("File ordini non valido. (id duplicato)");
        exit();
    }
    $ids_ord[] = $row[0];

    if (!in_array($row[3], $ids_inv)) {
        http_response_code(400);

        echo json_encode("File ordini non valido. (id prodotto non valido)");
        exit();
    }

    
    if (!is_numeric($row[0]) || !is_numeric($row[3]) || !is_numeric($row[4])) {
        http_response_code(400);

        echo json_encode("File ordini non valido. (id, id prodotto e/o quantita non numerici)");
        exit();
    }

    
    if (empty($row[5])) {
        
        foreach ($csv_inv as $row_inv) {
            if ($row_inv[0] == $row[3]) {
                
                $csv_ord[$key][5] = $row_inv[5] * $row[4];
                break;
            }
        }

        
        $file = fopen('debug.txt', 'a');
        fwrite($file, implode(',', $row) . "\n");
        fclose($file);
    }

    
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $row[6])) {
        http_response_code(400);

        echo json_encode("File ordini non valido. (data non valida)");
        exit();
    }

    
    if ($row[0] > $personal_id_ord) {
        $personal_id_ord = $row[0];
    }

}


$personal_id_inv++;
$personal_id_ord++;


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


$query = "DELETE FROM inventario WHERE proprietario = '" . $_SESSION['userid'] . "'";
$result = $conn->query($query);


if (!$result) {
    http_response_code(500);

    
    $debug_file = fopen("debug.txt", "a");
    fwrite($debug_file, "Query: " . $query . "\nError: " . $conn->error . "\n");
    fclose($debug_file);

    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}

$query = "DELETE FROM ordini WHERE proprietario = '" . $_SESSION['userid'] . "'";
$result = $conn->query($query);


if (!$result) {
    http_response_code(500);

    
    $debug_file = fopen("debug.txt", "a");
    fwrite($debug_file, "Query: " . $query . "\nError: " . $conn->error . "\n");
    fclose($debug_file);

    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}



$query = "UPDATE utenti SET personal_id_ord = '" . $personal_id_ord . "', personal_id_inv = '" . $personal_id_inv . "' WHERE user_id = '" . $_SESSION['userid'] . "';";
$result = $conn->query($query);


if (!$result) {
    http_response_code(500);

    
    $debug_file = fopen("debug.txt", "a");
    fwrite($debug_file, "Query: " . $query . "\nError: " . $conn->error . "\n");
    fclose($debug_file);

    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


foreach ($csv_inv as $row) {
    
    if ($row[0] == "id") {
        continue;
    }

    $query = "INSERT INTO inventario (personal_id, nome_prodotto, descrizione, categoria, quantita, prezzo, proprietario) VALUES (" . $row[0] . ", '" . $row[1] . "', '" . $row[2] . "', '" . $row[3] . "', " . $row[4] . ", " . $row[5] . ", '" . $_SESSION['userid'] . "');";
    $result = $conn->query($query);

    
    if (!$result) {
        http_response_code(500);

        
        $debug_file = fopen("debug.txt", "a");
        fwrite($debug_file, "Query: " . $query . "\nError: " . $conn->error . "\n");
        fclose($debug_file);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }
} 


foreach ($csv_ord as $row) {
    
    if ($row[0] == "id") {
        continue;
    }
    
    $query = "INSERT INTO ordini (personal_id, nome, cognome, id_prodotto, quantita, prezzo, data_ordine, proprietario) VALUES (" . $row[0] . ", '" . $row[1] . "', '" . $row[2] . "', " . $row[3] . ", " . $row[4] . ", " . $row[5] . ", '" . $row[6] . "', '" . $_SESSION['userid'] . "');";
    $result = $conn->query($query);

    
    if (!$result) {
        http_response_code(500);

        
        $debug_file = fopen("debug.txt", "a");
        fwrite($debug_file, "Query: " . $query . "\nError: " . $conn->error . "\n");
        fclose($debug_file);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }
}


$conn->close();


http_response_code(200);

echo json_encode("File caricati con successo.");


unlink($file_inv['tmp_name']);
unlink($file_ord['tmp_name']);



?>