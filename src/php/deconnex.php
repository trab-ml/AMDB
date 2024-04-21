<?php
session_start();

// destruction des variables de session et redirection vers login.php. 

$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

session_destroy();
session_write_close();

$sessionId = session_id();
if ($sessionId !== '') {
    $sessionPath = session_save_path();
    if (is_writable($sessionPath)) {
        $sessionFile = $sessionPath . '/sess_' . $sessionId;
        if (file_exists($sessionFile)) {
            unlink($sessionFile);
        }
    }
}

header("Location: ./login.php");
exit();
?>