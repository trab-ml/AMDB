<?php
session_start();
require_once("./checking_functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../html/log_or_sign_head.php"); 
?>
    <body>
        <?php
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && 
            isset($_POST['pseudo']) && 
            isset($_POST['email']) && 
            isset($_POST['password']) && 
            isset($_POST['password-confirm']) &&
            is_string($_POST['pseudo']) &&
            is_string($_POST['email']) &&
            is_string($_POST['password']) &&
            is_string($_POST['password-confirm'])) {
            
            require_once("./traitement.php");
        }
        ?>
        <div class="container my-4 ">
            <h1 class="text-center">Bienvenue Sur Artois Movie Database.</h1>
            <br>
            <h2 class="text-center">Créer un compte, ou <a class="link-primary" href="./login.php">se connecter</a>  it's free !</h2>

            <form id="signup-form" name="signup-form" action="" method="POST">
                <div class="form-group mb-3">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" maxlength="20" required>
                </div>
                <div id="pseudo-error" class="invalid-feedback"></div>

                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="60" required>
                </div>
                <div id="email-error" class="invalid-feedback"></div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="60" required>
                </div>
                <div id="password-error" class="invalid-feedback"></div>

                <div class="form-group mb-3">
                    <label for="password-confirm">Confirm Password</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
                </div>
                <div id="password-confirm-error" class="invalid-feedback"></div>

                <button type="submit" class="btn btn-primary mb-3" id="submit" name="submit">Sign up</button>
            </form>
            <div id="failed-signup"></div>
        </div>
        
        <script src="../js/validate_signup.js"></script> 
    </body>
</html>