$(document).ready(function() {
    // $('#password, #password-confirm').mask('********'); 

    // Désactiver le bouton de soumission du formulaire
    $("#submit").attr("disabled", true);

    // Fonction de validation des données
    function validateData() {
        var pseudo = $("#pseudo").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var confirmPassword = $("#password-confirm").val();

        $("#pseudo-error").text("");
        $("#email-error").text("");
        $("#password-error").text("");
        $("#password-confirm-error").text("");

        // check pseudo
        if (!pseudo.match(/^[a-zA-Z]{2}[\w-]*$/)) {
            $("#pseudo-error").text("Le pseudo doit comporter au moins 2 caractères");
            $("#pseudo-error").css("display", "block");
        }

        // check email
        if (!email.match(/^\S+@\S+\.[a-zA-Z0-9]{2,4}$/)) {
            $("#email-error").text("L'adresse email n'est pas valide");
            $("#email-error").css("display", "block");
        }

        // check password
        if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/)) {
            $("#password-error").text("Le mot de passe doit contenir au moins 8 caractères dont au moins une minuscule, une majuscule, un chiffre et un caractère spécial");
            $("#password-error").css("display", "block");
        }

        // check confirmPassword
        if (confirmPassword !== password) {
            $("#password-confirm-error").text("Les mots de passe correspondent pas.");
            $("#password-confirm-error").css("display", "block");
        }

        if ($("#pseudo-error").text() === "" && $("#email-error").text() === "" && $("#password-error").text() === "" && $("#confirm-password-error").text() === "") {
            $("#submit").attr("disabled", false);
        } else {
            $("#submit").attr("disabled", true);
        }
    }

    // Fonctions d'écoute des changements des champs
    $("#pseudo").keyup(validateData);
    $("#email").keyup(validateData);
    $("#password").keyup(validateData);
    $("#password-confirm").keyup(validateData);

    // Soumettre le formulaire si tous les champs sont conformes
    $("#signup-form").submit(function(event) {
        if ($("#submit").attr("disabled")) {
            event.preventDefault();
            return false;
        }
    });
});
