<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once('settings.php');


header('Content-Type: application/json');


$_PUT = json_decode(file_get_contents('php://input'), true);


$mode = $_PUT['mode'];


if ($mode != "inventario" && $mode != "ordini") {
    http_response_code(400);

    echo json_encode("Errore durante la richiesta.");
    exit();
}


if ($mode == "inventario") {
    
    if (empty($_PUT['nome_prodotto']) || empty($_PUT['descrizione']) || empty($_PUT['categoria']) || empty($_PUT['quantita']) || empty($_PUT['prezzo'])) {
        http_response_code(400);

        echo json_encode("Devi riempire tutti i campi per aggiungere dati.");
        exit();
    }

    
    if (!is_int($_PUT['quantita']) || !is_numeric($_PUT['prezzo'])) {
        http_response_code(400);

        echo json_encode("Devi inserire un numero valido per quantità e/o prezzo.");
        exit();
    }
}


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($mode == "ordini") {
    
    if (empty($_PUT['nome']) || empty($_PUT['cognome']) || empty($_PUT['id_prodotto']) || empty($_PUT['quantita']) || empty($_PUT['data_ordine'])) {
        http_response_code(400);

        echo json_encode("Devi riempire tutti i campi per aggiungere dati.");

        exit();
    }

    
    if (!is_int($_PUT['quantita']) || (!empty($_PUT['prezzo']) && !is_numeric($_PUT['prezzo']))) {
        http_response_code(400);

        echo json_encode("Devi inserire un numero valido per quantita e/o prezzo.");
        exit();
    }

    
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_PUT['data_ordine']) || strtotime($_PUT['data_ordine']) > time()) {
        http_response_code(400);

        echo json_encode("Devi inserire una data valida.");
        exit();
    }

    
    $sql = "SELECT * FROM inventario WHERE personal_id = " . $_PUT['id_prodotto'] . " AND proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        http_response_code(400);

        echo json_encode("Prodotto non presente nel database.");
        exit();
    }
    
    
    if (empty($_PUT['prezzo'])) {
        $_PUT['prezzo'] = $result->fetch_assoc()['prezzo'];

        
        $_PUT['prezzo'] = $_PUT['prezzo'] * $_PUT['quantita'];
    }
}


if ($mode == "inventario") {
    
    $query_personal_id = "SELECT personal_id_inv FROM utenti WHERE user_id = '" . $_SESSION['userid'] . "'";

    
    $result_personal_id = $conn->query($query_personal_id);

    
    $personal_id_inv = $result_personal_id->fetch_assoc()['personal_id_inv'];

    
    if (!$result_personal_id) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    $query = "INSERT INTO inventario (personal_id, nome_prodotto, descrizione, categoria, quantita, prezzo, proprietario) VALUES ('". $personal_id_inv . "', '" . $_PUT['nome_prodotto'] . "', '" . $_PUT['descrizione'] . "', '" . $_PUT['categoria'] . "', '" . $_PUT['quantita'] . "', '" . $_PUT['prezzo'] . "', '" . $_SESSION['userid'] . "')";

    
    if (!$conn->query($query)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    $query_personal_id = "UPDATE utenti SET personal_id_inv = personal_id_inv + 1 WHERE user_id = '" . $_SESSION['userid'] . "'";

    
    if (!$conn->query($query_personal_id)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    http_response_code(200);

    
    echo json_encode("Elemento aggiunto con successo.");
    exit();
}


if ($mode == "ordini") {
    
    $query_personal_id = "SELECT personal_id_ord FROM utenti WHERE user_id = '" . $_SESSION['userid'] . "'";

    
    $result_personal_id = $conn->query($query_personal_id);
    $personal_id_ord = $result_personal_id->fetch_assoc()['personal_id_ord'];

    
    if (!$result_personal_id) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    $query_quantita = "SELECT quantita FROM inventario WHERE personal_id = '" . $_PUT['id_prodotto'] . "' AND proprietario = '" . $_SESSION['userid'] . "'";
    $result_quantita = $conn->query($query_quantita);
    $quantita = $result_quantita->fetch_assoc()['quantita'];

    
    if (!$result_quantita) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    if ($_PUT['quantita'] > $quantita) {
        http_response_code(400);

        echo json_encode("Quantità richiesta supera quantità disponibile.");
        exit();
    }

    
    $query_quantita = "UPDATE inventario SET quantita = quantita - " . $_PUT['quantita'] . " WHERE personal_id = '" . $_PUT['id_prodotto'] . "' AND proprietario = '" . $_SESSION['userid'] . "'";
    
    
    if (!$conn->query($query_quantita)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    $query = "INSERT INTO ordini (personal_id, nome, cognome, id_prodotto, quantita, prezzo, data_ordine, proprietario) VALUES ('" . $personal_id_ord . "', '" . $_PUT['nome'] . "', '" . $_PUT['cognome'] . "', '" . $_PUT['id_prodotto'] . "', '" . $_PUT['quantita'] . "', '" . $_PUT['prezzo'] . "', '" . $_PUT['data_ordine'] . "', '" . $_SESSION['userid'] . "')";

    
    if (!$conn->query($query)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova pià tardi.");
        exit();
    }

    
    $query_personal_id = "UPDATE utenti SET personal_id_ord = personal_id_ord + 1 WHERE user_id = '" . $_SESSION['userid'] . "'";

    
    if (!$conn->query($query_personal_id)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    http_response_code(200);

    
    echo json_encode("Elemento aggiunto con successo.");
    exit();
}


$conn->close();

?>