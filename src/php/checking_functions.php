<?php
function rmv_show($elt_id) {
    echo '<script>
        setTimeout(() => {
            var alert = document.getElementById("'. htmlspecialchars($elt_id) .'");
            alert.classList.remove("show");
            alert.classList.add("fade");
        }, 2000);
    </script>';
}
function successPopup($msg) {
    echo '<div id="mySuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success !</strong> '. htmlspecialchars($msg) .'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div> ';
    rmv_show("mySuccessAlert");
}
function redirectingToHomePopup($msg='') {
    echo '<div id="redirectingAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>redirecting to home ...</strong> '. htmlspecialchars($msg) .'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> ';

    echo '<script>
        setTimeout(() => {
            var alert = document.getElementById("redirectingAlert");
            alert.classList.remove("show");
            alert.classList.add("fade");
            // window.location.replace("./home.php");
        }, 1500);
    </script>';
}
function errorPopup($msg) {
    echo '<div id="myErrorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error !</strong> '. htmlspecialchars($msg) .'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    rmv_show("myErrorAlert");
}
?>