<?php
session_start();
require_once("./check_reliability.php");
require_once("connect.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('API_KEY', 'api_key=1cf50e6248dc270629e802686245c2c8');
define('BASE_URL', 'https://api.themoviedb.org/3');
define('API_URL', BASE_URL . '/discover/movie?sort_by=vote_count.desc&' . API_KEY);
define('IMG_URL', 'https://image.tmdb.org/t/p/w500');
define('SEARCH_URL', BASE_URL . '/search/movie?' . API_KEY);
define('SINGLE_MOVIE_URL', BASE_URL . '/movie/');

class MovieDB {
    private $objdb;
    
    function __construct() {
        $this->objdb = connectToDatabase();
        if(!$this->objdb) {
            exit;
        }
    }
    function retrieveUserData() {
        if (!isset($_SESSION["login"], $_SESSION["password"])) {
            return false;
        }
        $login = $_SESSION["login"];
        $password = $_SESSION["password"];
        $query = "SELECT * FROM users WHERE pseudo=:pseudo AND password=:password";
        
        try {
            $stmt = $this->objdb->prepare($query);
    
            $stmt->bindParam(':pseudo', $login);
            $stmt->bindParam(':password', $password);
    
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result ? $result : null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    function insertFilterButtons() { 
        $query = "INSERT INTO filtrer (id, genre) VALUES 
            (10751, 'Family'), 
            (10770, 'TV Movie'), 
            (35, 'Comedy'), 
            (10749, 'Romance'), 
            (10402, 'Music'), 
            (28, 'Action'), 
            (80, 'Crime')";
        $results = $this->objdb->exec($query);
        if($results) {
            echo "Insertion réussie !";
        } else {
            echo "Echec !";
            exit;
        }
    }    
    function displayFilterButtons() {
        $query = "SELECT * FROM filtrer";
        $results = $this->objdb->query($query);
        $results = $results->fetchAll();
        $results_in_json = json_encode($results);
        echo "<script>
            const tagsEl = document.getElementById('tags');
            let selectedGenre = [];
            let genres = JSON.parse('".$results_in_json."');
            setGenre();

            function setGenre() {
                tagsEl.innerHTML = '';
                genres.forEach(genre => {
                    const t = document.createElement('div');
                    t.classList.add('tag');
                    t.id = genre['id'];
                    t.innerText = genre['genre'];
                    t.addEventListener('click', () => {
                        if (selectedGenre.length == 0) {
                            selectedGenre.push(genre['id']);
                        } else {
                            if (selectedGenre.includes(genre['id'])) {
                                selectedGenre.forEach((id, idx) => {
                                    if (id == genre['id']) {
                                        selectedGenre.splice(idx, 1);
                                    }
                                });
                            } else {
                                selectedGenre.push(genre['id']);
                            }
                        }
                        getMovies('". API_URL ."'+ '&with_genres=' + encodeURI(selectedGenre.join(',')));
                        highlightSelection();
                    });
                    tagsEl.append(t);
                });
            }
    
            function highlightSelection() {
                const tags = document.querySelectorAll('.tag');
                tags.forEach(tag => {
                    tag.classList.remove('highlight');
                })
                clearBtn();
                if (selectedGenre.length != 0) {
                    selectedGenre.forEach(id => {
                        const hightlightedTag = document.getElementById(id);
                        hightlightedTag.classList.add('highlight');
                    })
                }
            }
    
            function clearBtn() {
                let clearBtn = document.getElementById('clear');
        
                if (clearBtn) {
                    clearBtn.classList.add('highlight');
                } else {
                    let clear = document.createElement('div');
                    clear.classList.add('tag', 'highlight');
                    clear.id = 'clear';
                    clear.innerText = 'Clear x';
                    clear.addEventListener('click', () => {
                        selectedGenre = [];
                        setGenre();
                        getMovies('". API_URL ."');
                    });
                    tagsEl.append(clear);
                }
            } 

            function getMovies(url) {
                lastUrl = url;
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.results.length !== 0) {
                            showMovies(data.results);
                            tagsEl.scrollIntoView({
                                behavior: 'smooth'
                            });
                        } else {
                            main.innerHTML = `<h1 class='no-results'>No Results Found</h1>`;
                        }
                    }).catch(function(err) {
                        // Une erreur est survenue
                        console.log(err);
                    });
            }
        </script>";
    }
    function handleSearch() {
        echo '<script>
            const searchInput = document.getElementById("search-bar");
            
            searchInput.addEventListener("input", (e) => {
                selectedGenre = [];
                setGenre();
                const inputValue = e.target.value.replace(/<[^>]*>/g, "");
                if (!inputValue) {
                    getMovies(API_URL);
                } else {
                    getMovies("'. SEARCH_URL .'" + "&query=" + inputValue);
                }
            });
        </script>';
    }
    function defaultMovies() {
        $this->handleSearch();

        $data = $this->getMovies(API_URL);
        $this->displayMovies($data);
    }
    function getMovies($api) {
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            echo 'Erreur cURL : impossible de récupérer les données';
            exit;
        }
    
        curl_close($curl);
    
        return $response;
    }
    function getColor($vote) {
        if ($vote >= 8) {
            return "green";
        } else if ($vote >= 5) {
            return "orange";
        } else {
            return "red";
        }
    }
    function displayMovies($data) {
        // $json_data = json_encode($data);
        // echo '<script>showMovies('. $json_data .');</script>';
        echo '<script>
          const data = ' . $data . ';
          const { results } = data;
          showMovies(results);
          function showMovies(data) {
            main.innerHTML = "";
        
            data.forEach(movie => {
              const {
                title,
                poster_path,
                vote_average,
                overview,
                id
              } = movie;
              
              const movieEl = document.createElement("div");
              movieEl.classList.add("movie");
        
              movieEl.innerHTML = `
                <img src="${poster_path ? "'. IMG_URL .'"+poster_path : "http://via.placeholder.com/1080x1580" }" alt="${title}">
        
                <div class="movie-info">
                  <h3>${title}</h3>
                  <span class="vote-average ${getColor(vote_average)}">${vote_average}</span>
                </div>
        
                <div class="overview">
                  <h3>Overview</h3>
                  ${overview}
                  <br/> 
                  <button class="rate-movie">
                    <a href="rate.php?id=${id}" class="rate-movie-link">rate this movie</a>
                  </button>
                </div>
              `;
        
              main.appendChild(movieEl).classList.add("show");
            });
          }
          
          function getColor(vote) {
            if (vote >= 8) {
              return "green";
            } else if (vote >= 5) {
              return "orange";
            } else {
              return "red";
            }
          }
        </script>';
    }
    function displaySingleMovie($data) {
        echo '<script>
            data = '. $data .';
            let singleMovieInfos;
            // console.log(data);
            showMovies(data);
            function showMovies(data) {
                main.innerHTML = "";

                if(data) {
                    const {
                        title,
                        poster_path,
                        vote_average,
                        overview,
                        id,
                        tagline,
                        release_date,
                        vote_count,
                        original_language
                    } = data;

                    // console.log(data);
                    
                    singleMovieInfos = {
                        "title": title, 
                        "poster_path": poster_path, 
                        "vote_average": vote_average, 
                        "overview": overview, 
                        "id": id,
                        "tagline": tagline,
                        "release_date": release_date,
                        "vote_count": vote_count,
                        "original_language": original_language
                    };
                    const movieEl = document.createElement("div");
                    movieEl.classList.add("movie");
                    movieEl.setAttribute("id", "single-movie");
                    movieEl.innerHTML = `
                        <img src="${poster_path ? "'. IMG_URL .'"+poster_path : "http://via.placeholder.com/1080x1580" }" alt="${title}">
        
                        <div id="single-movie-info" class="movie-info">
                            <h2>${title}</h2>
                            <p><small><span id="single-movie-release-date">${release_date}</span></small></p>
                            <h5><span>tagline:</span> ${tagline}</h5>
                            <p>${overview}</p>
                            <p><span class="${getColor(vote_average)}">${vote_average}</span> /10 for ${vote_count} voter(s).</p>
                            <form id="movie-rate-form" name="movie-rate-form" action="" method="POST">
                                <span for="current-movie-rate-for-label"><label for="current-movie-rate">rate</label></span>
                                <input type="range" id="current-movie-rate" name="current-movie-rate" min="0" max="10" value="5">
                                <span id="current-movie-rate-value">5</span> étoiles
                                </br></br>
                                <input id="rated-movie-submit" name="rated-movie-submit" type="submit" value="submit">
                                <input id="about-movie-id" name="about-movie-id" type="text" value="${id}" />
                            </form>
                        </div>
                    `;
    
                    main.appendChild(movieEl);
                    setTimeout(() => {
                        movieEl.classList.add("show");
                    }, 150);
                }
            }

            function getColor(vote) {
                if (vote >= 8) {
                    return "green";
                } else if (vote >= 5) {
                    return "orange";
                } else {
                    return "red";
                }
            }

            const form = document.getElementById("movie-rate-form");
            form.addEventListener("submit", (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                formData.append("movie", JSON.stringify(singleMovieInfos));

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "rate.php");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        setTimeout(() => {
                            window.location.href = "./rated_list.php";
                        }, 1000);   
                    } else {
                        console.log("Une erreur est survenue.");
                    }
                };
                xhr.send(formData);
            });

            const rateInput = document.getElementById("current-movie-rate");
            const rateValue = document.getElementById("current-movie-rate-value");
            rateInput.addEventListener("input", () => {
                rateValue.textContent = rateInput.value;
            });
        </script>';
    }
    function getUserId() {
        $res = $this->retrieveUserData();
        if ($res) {
            return $res['user_id'];
        }
        return false;
    }
    function insertIntoFilms($filmId, $titre, $description, $langue, $dateTournage) {
        try {
            $stmt = $this->objdb->prepare("SELECT COUNT(*) FROM films WHERE film_id = ?");
            $stmt->execute([$filmId]);
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                $query = "INSERT INTO films (film_id, titre, description, langue, date_tournage) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->objdb->prepare($query);
                $stmt->execute([$filmId, $titre, $description, $langue, $dateTournage]);
            } 
            // else {
            //     echo "already in";
            // }
            return true;
        } catch (PDOException $e) {
            echo "Echec d'insertion : " . $e->getMessage();
            return false;
        }
    }
    function insertIntoNoter($userId, $filmId, $noteGlobale, $title, $poster_path) {
        try {
            $this->objdb->beginTransaction();
    
            $selectStmt = $this->objdb->prepare("SELECT COUNT(*) FROM noter WHERE user_id = ? AND film_id = ?");
            $selectStmt->execute([$userId, $filmId]);
            $count = $selectStmt->fetchColumn();
    
            $query = ($count == 0) ? 
            ("INSERT INTO noter (user_id, film_id, note_globale, date_note, title, cover_img) 
            VALUES (?, ?, ?, strftime('%d-%m-%Y %H:%M:%S', 'now', 'localtime'), ?, ?)") : 
            ("UPDATE noter SET note_globale = ?, date_note = strftime('%d-%m-%Y %H:%M:%S', 'now', 'localtime') WHERE user_id = ? AND film_id = ?");
            
            $stmt = $this->objdb->prepare($query);
    
            if ($count == 0) {
                $stmt->execute([$userId, $filmId, $noteGlobale, $title, $poster_path]);
            } else {
                $stmt->execute([$noteGlobale, $userId, $filmId]);
            }
            $this->objdb->commit();
            return true;
        } catch (PDOException $e) {
            $this->objdb->rollBack();
            echo "Echec d'insertion : " . $e->getMessage();
            return false;
        }
    }    
    function displayRatedList() {
        try {
            $select = $this->objdb->prepare("SELECT * FROM noter");
            $select->execute();
            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                $poster_path = $row["cover_img"] ? IMG_URL . $row["cover_img"] : "http://via.placeholder.com/1080x1580";
                $vote_average = $row["note_globale"];
                $title = $row["title"];
                $date_note = $row["date_note"];
                $color = $this->getColor($vote_average);
                echo "
                <div id='rated-movie' class=''>
                    <img src='$poster_path' alt='$title'>
                    <div id='' class=''>
                        <h2>$title</h2>
                        <p>rate date: <small><span id=''>$date_note</span></small></p>
                        <p>given rate: <span class='$color'>$vote_average</span>/10</p>
                    </div>
                </div>
                ";
            }
        } catch(PDOException $e) {
            echo "Echec d'insertion : " . $e->getMessage();
            return false;
        }
    }   
}