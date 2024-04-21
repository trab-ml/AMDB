<?php
session_start();
require_once("./check_reliability.php");
require_once("./movieDB.php");

$user_session = new MovieDB();

require_once("../html/head.php"); 
require_once("../html/nav.php"); 
?>
    <div class="content_wrapper">
        <div id="principal-title" class="title">
            <h2>Artois Movies Data Base, </h2>
            <h3>C'est des millions de films, émissions télévisées et artistes...</h3>
        </div>
        <div class="search">
            <form id="search-form" name="search-form" action="" method="">
                <input id="search-bar" name="search-bar" type="text" placeholder="Rechercher un film, une émission télévisée, un artiste..." value="" >
            </form>
        </div>
    </div>

    <div id="tags"></div>

    <main id="main"></main>

    <?php 
    $user_session->displayFilterButtons(); 
    $user_session->defaultMovies(); 
    ?>
    <script src="../js/home.js"></script>
    <!-- <script src="../js/movieDB.js"></script> -->
</body>
</html>