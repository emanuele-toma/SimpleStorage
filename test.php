<?php

include 'settings.php';


include 'utilities.php';


if (!validateCaptcha($_GET['token'], $hcaptcha_sitekey_register, $hcaptcha_secret)) {
    
    http_response_code(400);

    
    echo json_encode("Captcha non valido");
    exit();
} else {
    
    http_response_code(200);

    
    echo json_encode("Captcha valido");
    exit();
}
?>