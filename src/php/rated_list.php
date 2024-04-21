<?php 
session_start();
require_once("./check_reliability.php");
require_once("./movieDB.php");

$userSession = new MovieDB();

require_once("../html/head.php"); 
require_once("../html/nav.php"); 
?>
    <main id="rated-list">
        <form id="rated-list-form" action="" method="post">
            <label for='sort'>croisant<label>
            <input id='ascending' name='sort' value='ascending' type='radio' />

            <label for='unsort'>decroisant<label>
            <input id='descending' name='sort' value='descending' type='radio' />
            
            <input id='submit-sort' name='submit-sort' type='submit' value='apply'/>
        </form>
        <?php
        $userSession->displayRatedList();
        ?>
    </main>
    <script src='../js/movieDB.js'></script>
    </body>
</html>