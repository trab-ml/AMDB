<?php
// Path de la DB
define('DB_PATH', '../sqlite/movie_database.db');

// Connexion à la BD
function connectToDatabase() {
    $dsn = "sqlite:" . DB_PATH;
    
    try {
        $db = new PDO($dsn);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $except){
        error_log("Erreur de connexion à la base de données: " . $except->getMessage());
        header("Location: ./login.php");
        // throw new Exception("Erreur de connexion à la base de données.");
    }
}
?>