<?php
session_start();
require_once("checking_functions.php");
require_once("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo']) && isset($_POST['password'])) {
    $db = connectToDatabase();

    $stmt = $db->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $pseudo);

    $pseudo = trim(htmlspecialchars($_POST['pseudo']));
    $password = trim($_POST['password']);
    $pseudo = $db->quote($pseudo);
    $password = $db->quote($password);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = $user['pseudo'];
        $_SESSION['pwd'] = $user['password'];
        header("Location: home.php");
        exit;
    } else {
        $error_message = "pseudo ou mot de passe incorrect.";
    }
}

require_once("../html/log_or_sign_head.php");
?>
<body>
    <div class="container my-4">
        <h1 class="text-center">Bienvenue sur Artois Movie Database.</h1>
        <br>
        <h2 class="text-center">Connexion ou <a class="link-primary" href="./signup.php">Inscription</a></h2>
        <form id="login-form" name="login-form" action="" method="POST">
            <div class="form-group mb-3">
                <label for="pseudo">Pseudo</label>
                <input id="pseudo" name="pseudo" type="text" class="form-control" maxlength="20" required>
            </div>

            <div class="form-group mb-3">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" class="form-control" maxlength="60" required>
            </div>

            <button id="submit" name="submit" type="submit" class="btn btn-primary">Connexion</button>
            <?php if(isset($error_message)) { ?>
            <div class="invalid-feedback d-block">
                <?php $error_message ?>
            </div>
            <?php } ?>
        </form>
    </div>

    <script src="../js/validate_login.js"></script>
</body>
</html>