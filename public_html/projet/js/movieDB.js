const IMG_URL = 'https://image.tmdb.org/t/p/w500';

function getColor(vote) {
    if (vote >= 8) {
        return "green";
    } else if (vote >= 5) {
        return "orange";
    } else {
        return "red";
    }
}

function handleRate() {
    const form = document.getElementById("movie-rate-form");
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append("movie", JSON.stringify(singleMovieInfos));

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "rate.php");
        xhr.onload = function() {
            if (xhr.status === 200) {
                // console.log(xhr.responseText);
                // alert("successfully made"); // must disappears after 100ms 
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
}

function showMovies(data) {
    const main = document.getElementById("main");
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
            <img src="${poster_path ?  IMG_URL + poster_path : "http://via.placeholder.com/1080x1580" }" alt="${title}">

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

document.addEventListener("DOMContentLoaded", (e) => {
    handleRate();
});