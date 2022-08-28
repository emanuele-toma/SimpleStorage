<?php
    ob_start("ob_gzhandler");

    
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    
    require_once('settings.php');

    $mode = $_GET['mode'];

    
    if ($mode != "inventario" && $mode != "ordini") {
        http_response_code(400);

        echo json_encode("Errore durante la richiesta.");
        exit();
    }

    
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    
    $query = "SELECT * FROM " . $mode . " WHERE proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($query);

    
    if (!$result) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    
    
    foreach ($rows as &$row) {
        $row['id'] = $row['personal_id'];
        unset($row['personal_id']);
        unset($row['proprietario']);
    }


    
    $fp = fopen('php://output', 'w');
    fputcsv($fp, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($fp, $row);
    }   
    fclose($fp);

    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $mode . '.csv";');
    header('Content-Length: ' . filesize('php://output'));

    readfile('php://output');

    
    $conn->close();

    ob_end_flush();
?>