<?php
session_start();
require_once("./connect.php");
$allowed_user = true;

$login = $_SESSION['login'];
$pwd = $_SESSION['pwd'];
if(isset($login) &&
    is_string($login) &&
    isset($pwd) &&
    is_string($pwd)) {
    $db = connectToDatabase();

    $stmt = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo AND password = :password");
    $stmt->bindParam(':pseudo',  $login);
    $stmt->bindParam(':password', $pwd);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user) {
        $allowed_user = false;
    }
} else {
    $allowed_user = false;
}

if (!$allowed_user) {
    require_once("./deconnex.php");
}
?>