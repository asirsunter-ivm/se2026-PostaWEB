<?php
require_once __DIR__ . '/config.php';

function encryptData($plaintext) {
    if (empty($plaintext)) return null;
    $ivLen     = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
    $iv        = openssl_random_pseudo_bytes($ivLen);
    $encrypted = openssl_encrypt($plaintext, ENCRYPTION_CIPHER, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($encrypted) {
    if (empty($encrypted)) return null;
    $data = base64_decode($encrypted, true);
    if ($data === false) return $encrypted;
    
    $ivLen = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
    if (strlen($data) < $ivLen) return $encrypted;
    
    $iv = substr($data, 0, $ivLen);
    $enc = substr($data, $ivLen);
    $result = @openssl_decrypt($enc, ENCRYPTION_CIPHER, ENCRYPTION_KEY, 0, $iv);
    return $result !== false ? $result : $encrypted;
}
?>