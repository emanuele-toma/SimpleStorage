<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once 'settings.php';


require_once 'utilities.php';


$_POST = json_decode(file_get_contents('php://input'), true);


$token = $_POST['token'];


header('Content-Type: application/json');


if (!validateCaptcha($token, $hcaptcha_sitekey_register, $hcaptcha_secret)) {
    
    http_response_code(400);

    
    echo json_encode("Captcha non valido");
    exit();
}


if (!validateUsername($_POST['username'])) {
    
    http_response_code(400);

    
    echo json_encode("Username non valido");
    exit();
}


if (!validateEmail($_POST['email'])) {
    
    http_response_code(400);

    
    echo json_encode("Email non valida");
    exit();
}


if (!validatePassword($_POST['password'])) {
    
    http_response_code(400);

    
    echo json_encode("La password deve contenere almeno una maiuscola e un numero ed essere lunga almeno 8 caratteri.");
    exit();
}


$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    
    http_response_code(500);

    
    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


$sql = "SELECT * FROM utenti WHERE LOWER(username) = LOWER('" . $_POST['username'] . "') OR LOWER(email) = LOWER('" . $_POST['email'] . "')";


$result = $conn->query($sql);


if ($result->num_rows > 0) {
    
    http_response_code(400);

    
    echo json_encode("Username o email già in uso");
    exit();
}


$random = rand(1, 6);


$sql = "INSERT INTO utenti (personal_id_inv, personal_id_ord, username, password, email, picture, role) VALUES (1, 1, '" . $_POST['username'] . "', '" . $hash . "', '" . $_POST['email'] . "', '" . "assets/user_". $random . ".png', 'user')";


$result = $conn->query($sql);


if (!$result) {
    
    http_response_code(500);

    
    echo json_encode("Si è verificato un errore, riprova più tardi.");
    exit();
}


http_response_code(200);


echo json_encode("Registrazione avvenuta con successo");


$conn->close();
