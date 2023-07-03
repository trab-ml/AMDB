function validateData() {
    var pseudo = $("#pseudo").val();
    var password = $("#password").val();

    $("#bad-id").text("");

    if (!pseudo.match(/^[a-zA-Z]{2}[\w-]*$/)) {
        $("#submit").attr("disabled", true);
    } else {
        $("#submit").attr("disabled", false);
    }

    if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/)) {
        $("#submit").attr("disabled", true);
    } else {
        $("#submit").attr("disabled", false);
    }
}

$(document).ready(function() {

    // $("#submit").attr("disabled", true);

    $("#pseudo").keyup(validateData);
    $("#password").keyup(validateData);

    $("#login-form").submit(function(event) {
        if ($("#submit").attr("disabled")) {
            event.preventDefault();
            $("#bad-id").css("display", "block");
            $("#bad-id").text("Identifiants érronés !");
            return false;
        }
    });
});