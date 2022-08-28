<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once('settings.php');


header('Content-Type: application/json');


$_PATCH = json_decode(file_get_contents('php://input'), true);


$mode = $_PATCH['mode'];


if ($mode != "inventario" && $mode != "ordini") {
    http_response_code(400);

    echo json_encode("Errore durante la richiesta.");
    exit();
}


if (empty($_PATCH['id'])) {
    http_response_code(400);

    echo json_encode("Il campo id è obbligatorio.");
    exit();
}


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($mode == "inventario") {
    
    if ((!empty($_PATCH['quantita']) && !is_int($_PATCH['quantita'])) || (!empty($_PATCH['prezzo']) && !is_numeric($_PATCH['prezzo']))) {
        http_response_code(400);

        echo json_encode("Devi inserire un numero valido per quantita e/o prezzo.");
        exit();
    }

    
    $sql = "SELECT * FROM inventario WHERE personal_id = '" . $_PATCH['id'] . "' AND proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($sql);

    
    if ($result->num_rows == 0) {
        http_response_code(400);

        echo json_encode("Elemento non trovato.");
        exit();
    }

    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (empty($_PATCH['nome_prodotto'])) {
                $_PATCH['nome_prodotto'] = $row['nome_prodotto'];
            }
            if (empty($_PATCH['descrizione'])) {
                $_PATCH['descrizione'] = $row['descrizione'];
            }
            if (empty($_PATCH['categoria'])) {
                $_PATCH['categoria'] = $row['categoria'];
            }
            if (empty($_PATCH['quantita'])) {
                $_PATCH['quantita'] = $row['quantita'];
            }
            if (empty($_PATCH['prezzo'])) {
                $_PATCH['prezzo'] = $row['prezzo'];
            }
        }
    }
}


if ($mode == "ordini") {

    
    if ((!empty($_PATCH['quantita']) && !is_int($_PATCH['quantita'])) || (!empty($_PATCH['prezzo']) && !is_numeric($_PATCH['prezzo']))) {
        http_response_code(400);

        echo json_encode("Devi inserire un numero valido per quantita e/o prezzo.");
        exit();
    }

    
    if (!empty($_PATCH['data_ordine']) && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_PATCH['data_ordine']) || strtotime($_PATCH['data_ordine']) > time())) {
        http_response_code(400);

        echo json_encode("Devi inserire una data valida.");
        exit();
    }

    
    $sql_fill = "SELECT * FROM ordini WHERE personal_id = " . $_PATCH['id'] . " AND proprietario = '" . $_SESSION['userid'] . "'";
    $result_fill = $conn->query($sql_fill);

    
    if ($conn->error) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    if ($result_fill->num_rows == 0) {
        http_response_code(400);

        echo json_encode("Elemento non trovato.");
        exit();
    }

    
    if ($result_fill->num_rows > 0) {
        $row_fill = $result_fill->fetch_assoc();

        
        if (empty($_PATCH['nome'])) {
            $_PATCH['nome'] = $row_fill['nome'];
        }
        if (empty($_PATCH['cognome'])) {
            $_PATCH['cognome'] = $row_fill['cognome'];
        }
        if (empty($_PATCH['id_prodotto'])) {
            $_PATCH['id_prodotto'] = $row_fill['id_prodotto'];
        }
        if (empty($_PATCH['quantita'])) {
            $_PATCH['quantita'] = $row_fill['quantita'];
        }
        if (empty($_PATCH['prezzo'])) {
            $_PATCH['prezzo'] = $row_fill['prezzo'];
        }
        if (empty($_PATCH['data_ordine'])) {
            $_PATCH['data_ordine'] = $row_fill['data_ordine'];
        }

        
        $json = json_encode($_PATCH);
        $file = fopen("debug.txt", "a");
        fwrite($file, $json . "\n");
        fclose($file);
    }

    
    $sql = "SELECT * FROM inventario WHERE personal_id = " . $_PATCH['id_prodotto'] . " AND proprietario = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        http_response_code(400);

        echo json_encode("ID prodotto non presente nel database.");
        exit();
    }
}


if ($mode == "inventario") {
    
    $query = "UPDATE inventario SET nome_prodotto = '" . $_PATCH['nome_prodotto'] . "', descrizione = '" . $_PATCH['descrizione'] . "', categoria = '" . $_PATCH['categoria'] . "', quantita = '" . $_PATCH['quantita'] . "', prezzo = '" . $_PATCH['prezzo'] . "' WHERE personal_id = " . $_PATCH['id'] . " AND proprietario = '" . $_SESSION['userid'] . "'";

    
    if (!$conn->query($query)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    http_response_code(200);

    
    echo json_encode("Elemento modificato con successo.");

    exit();
}


if ($mode == "ordini") {
    
    $query = "UPDATE ordini SET nome = '" . $_PATCH['nome'] . "', cognome = '" . $_PATCH['cognome'] . "', id_prodotto = '" . $_PATCH['id_prodotto'] . "', quantita = '" . $_PATCH['quantita'] . "', prezzo = '" . $_PATCH['prezzo'] . "', data_ordine = '" . $_PATCH['data_ordine'] . "' WHERE personal_id = " . $_PATCH['id'] . " AND proprietario = '" . $_SESSION['userid'] . "'";
    

    
    if (!$conn->query($query)) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }

    
    http_response_code(200);

    
    echo json_encode("Elemento modificato con successo.");

    exit();
}


$conn->close();
