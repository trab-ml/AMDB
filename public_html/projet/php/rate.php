<?php 
session_start();
require_once("./check_reliability.php");
require_once("./movieDB.php");

$userSession = new MovieDB();

if (($_SERVER['REQUEST_METHOD'] === 'POST')) {
    $userId = $userSession->getUserId();  
    $movie = json_decode($_POST['movie'], true);
    $rate = htmlspecialchars($_POST['current-movie-rate']);

    $userSession->insertIntoFilms(
        $movie['id'], 
        $movie['title'], 
        $movie['overview'], 
        $movie['original_language'], 
        $movie['release_date']
    );

    $userSession->insertIntoNoter(
        $userId, 
        $movie['id'], 
        $rate, 
        $movie['title'],
        $movie['poster_path']
    );
}

require_once("../html/head.php"); 
require_once("../html/nav.php"); 
?>
    <main id="main"></main>

	<?php
		if (isset($_GET['id']) && is_string($_GET['id'])) {
			$id = trim(htmlspecialchars($_GET['id']));
            $url = SINGLE_MOVIE_URL . $id . "?" . API_KEY;
            $data = $userSession->getMovies($url);

            if($data) {
                $userSession->displaySingleMovie($data);
            }
		}
	?>

    <script src='../js/movieDB.js'></script>
    </body>
</html>