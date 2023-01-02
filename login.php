<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once 'settings.php';


require_once 'utilities.php';


$_POST = json_decode(file_get_contents('php://input'), true);


$token = $_POST['token'];


header('Content-Type: application/json');


if ($_SESSION['loggedin'] == true && false) {
    
    http_response_code(200);

    
    echo json_encode("Login avvenuto con successo");
    exit();
}


if (!validateCaptcha($token, $hcaptcha_sitekey_login, $hcaptcha_secret)) {
    
    http_response_code(400);

    
    echo json_encode("Captcha non valido");
    exit();
}


if (!validateUsername($_POST['username']) && !validateEmail($_POST['username'])) {
    
    http_response_code(400);

    
    echo json_encode("Credenziali errate.");
    exit();
}


if (!validatePassword($_POST['password'])) {
    
    http_response_code(400);

    
    echo json_encode("Credenziali errate.");
    exit();
}


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    
    http_response_code(500);

    
    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


$sql = "SELECT * FROM utenti WHERE LOWER(username) = LOWER('" . $_POST['username'] . "') OR LOWER(email) = LOWER('" . $_POST['username'] . "')";


$result = $conn->query($sql);


if ($result->num_rows > 0) {
    
    $row = $result->fetch_assoc();

    
    if (!password_verify($_POST['password'], $row['password'])) {
        
        echo json_encode("Credenziali errate.");
        exit();
    }

    
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['picture'] = $row['picture'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['userid'] = $row['user_id'];

    
    $_SESSION['loggedin'] = true;

    
    http_response_code(200);

    
    echo json_encode("Login avvenuto con successo");
    
    
    $conn->close();

    
    exit();
}
