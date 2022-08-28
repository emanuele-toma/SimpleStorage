<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require_once('settings.php');


header('Content-Type: application/json');


$_PATCH = json_decode(file_get_contents('php://input'), true);


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if (empty($_PATCH['email']) || empty($_PATCH['vecchia_password']) || empty($_PATCH['immagine'])) {
    http_response_code(400);

    echo json_encode("Devi riempire tutti i campi obbligatori.");
    exit();
}


if (!filter_var($_PATCH['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);

    echo json_encode("Devi inserire un indirizzo email valido.");
    exit();
}


if ($_PATCH['immagine'] < 1 || $_PATCH['immagine'] > 6) {
    http_response_code(400);

    echo json_encode("Immagine non valida.");
    exit();
}


$sql = "SELECT * FROM utenti WHERE user_id = '" . $_SESSION['userid'] . "'";
$result = $conn->query($sql);


if (!password_verify($_PATCH['vecchia_password'], $result->fetch_assoc()['password'])) {
    http_response_code(400);

    echo json_encode("La vecchia password non corrisponde.");
    exit();
}


if (empty($_PATCH['nuova_password'])) {
    
    $sql = "UPDATE utenti SET email = '" . $_PATCH['email'] . "', picture = 'assets/user_" .  $_PATCH['immagine'] . ".png' WHERE user_id = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($sql);

    
    $file = fopen("debug.txt", "a");
    fwrite($file, $sql . "\n");
    fclose($file);

    
    if (!$result) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }
}


if (!empty($_PATCH['nuova_password'])) {
    
    $sql = "UPDATE utenti SET email = '" . $_PATCH['email'] . "', password = '" . password_hash($_PATCH['nuova_password'], PASSWORD_DEFAULT) . "', picture = 'assets/user_" .  $_PATCH['immagine'] . ".png' WHERE user_id = '" . $_SESSION['userid'] . "'";
    $result = $conn->query($sql);

    
    if (!$result) {
        http_response_code(500);

        echo json_encode("Si è verificato un errore, riprova più tardi.");
        exit();
    }
}


$_SESSION['email'] = $_PATCH['email'];
$_SESSION['picture'] = "assets/user_" . $_PATCH['immagine'] . ".png";


echo json_encode("Impostazioni modificate con successo. Ricarica la pagina per rendere effettive le modifiche.");


?>