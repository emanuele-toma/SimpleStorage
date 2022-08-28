<?php


function validateCaptcha($token, $sitekey, $secret)
{
    

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=".$secret."&response=".$token."&sitekey=".$sitekey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    
    
    $decoded = json_decode($server_output, true);
    
    curl_close($ch);

    
    if ($decoded['success'] == true) 
    {
        return true;
    }
    else
    {
        return false;
    }
}


function validateUsername($username)
{
    
    if (strlen($username) >= 3 && strlen($username) <= 32 && preg_match("/^[a-zA-Z0-9._-]+$/", $username)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}


function validateEmail($email)
{
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}


function validatePassword($password)
{
    
    if (strlen($password) >= 8 && strlen($password) <= 255 && preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,255}$/", $password)) 
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>