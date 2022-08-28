<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    
    header('Content-Type: application/json');

    
    http_response_code(200);

    
    echo json_encode(array(
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'picture' => $_SESSION['picture'],
        'role' => $_SESSION['role'],
        'userid' => $_SESSION['userid']
    ));

    exit();
?>