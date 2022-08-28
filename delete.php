<?php
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    
    require_once('settings.php');

    
    header('Content-Type: application/json');

    
    $_DELETE = json_decode(file_get_contents('php://input'), true);

    
    $mode = $_DELETE['mode'];

    
    if ($mode != "inventario" && $mode != "ordini") {
        http_response_code(400);

        echo json_encode("Errore durante la richiesta.");
        exit();
    }

    
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    
    $query = "SELECT * FROM " . $mode . " WHERE personal_id = '" . $_DELETE['id'] . "' AND proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($query);

    
    if (!$result) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    if ($result->num_rows == 0) {
        http_response_code(404);

        echo json_encode("Elemento non trovato.");
        exit();
    }

    
    if ($mode == "inventario") {
        $query = "DELETE FROM ordini WHERE id_prodotto = '" . $_DELETE['id'] . "'";
        $result = $conn->query($query);

        
        if (!$result) {
            http_response_code(500);

            echo json_encode("Si è verificato un errore, riprova più tardi.");
            exit();
        }
    }

    
    $query = "DELETE FROM " . $mode . " WHERE personal_id = " . $_DELETE['id'] . " AND proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($query);

    
    if (!$result) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    http_response_code(200);

    
    echo json_encode("Elemento eliminato con successo.");

    
    $conn->close();

?>