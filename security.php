<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['loggedin'] == false || !isset($_SESSION['loggedin'])) {
    header('Location: /');
    exit();
}

header('Content-type: application/octet-stream');
header('Content-Disposition: inline; filename="'.basename(urlencode($file['name'])).'"');
readfile($dir.basename($file[$_GET['data']]));

?>