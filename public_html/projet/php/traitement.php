<?php
require_once("./checking_functions.php");
require_once("./connect.php");

checkFormData();
function checkFormData() {
    $objdb = connectToDatabase();
    if (!$objdb) {
        header("Location: ./signup.php");
        exit;
    }

    $pseudo = trim(htmlspecialchars($_POST['pseudo']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password-confirm']);

    $pseudo = $objdb->quote($pseudo);
    $email = $objdb->quote($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    $password = $objdb->quote($password);
    $password_confirm = $objdb->quote($password_confirm);

    $check = $objdb->prepare("SELECT * FROM users WHERE pseudo=:pseudo OR email=:email");
    $check->bindParam(':pseudo', $pseudo);
    $check->bindParam(':email', $email);
    $check->execute();
    $results = $check->fetchAll(PDO::FETCH_ASSOC);

    if(count($results)>=1) {
        if($results[0]['pseudo']===$pseudo) {
            errorPopup("Ce pseudo n'est pas disponible. ");
        } else {
            errorPopup("Cet email n'est pas disponible. ");
        }
        exit;
    }

    if ($password !== $password_confirm) {
        errorPopup("Les mots de passe ne correspondent pas. ");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = $objdb->prepare("INSERT INTO users (pseudo, email, password) VALUES (:pseudo, :email, :hashed_password)");
    $insert_query->bindParam(':pseudo', $pseudo);
    $insert_query->bindParam(':email', $email);
    $insert_query->bindParam(':hashed_password', $hashed_password);
    $req = $insert_query->execute();
    
    if ($req) {
        successPopup("Inscription réussie, vous pouvez vous connectez. ");
        header("Location: ./login.php");
        exit;
    } else {
        errorPopup("Une erreur est survenue lors de l'inscription.");
        header("Location: ./signup.php");
        exit;
    }
}
?>