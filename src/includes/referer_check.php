<?php
function checkReferer() {
    $script = basename($_SERVER['SCRIPT_NAME']);
    
    // Faqja kryesore lejohet pa referer
    if ($script === 'index.php') return true;
    
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    
    // Nese nuk ka referer ose vjen nga jashte
    if (empty($referer)) {
        header('Location: /ProjektiWEB/index.php');
        exit;
    }
    
    $refererHost = parse_url($referer, PHP_URL_HOST);
    if ($refererHost !== $host) {
        header('Location: /ProjektiWEB/index.php');
        exit;
    }
    
    return true;
}
