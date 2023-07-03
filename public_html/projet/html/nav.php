<nav>
    <div id="left-side">
        <a href="#" title="artois movie database">AMDB</a>
        <a href="../php/home.php">Home</a>
        <a class="dropdown" href="#" id="film-dropdown">Films</a>
        <a href="#">Emissions Télévisées</a>
        <a href="../php/rated_list.php">Films Notés</a>
        <a href="#">Plus</a>
    </div>
    <div id="right-side">
        <a href="#"><?php echo isset($_SESSION["login"]) ? $_SESSION["login"] : "" ?></a>
        <a href="../php/deconnex.php">Log out</a>
    </div>
</nav>