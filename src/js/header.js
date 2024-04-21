function showDropdown() {
    var dropdown = $("<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'></ul>");
    var option1 = $("<li><a id='a-dropdown-item1' class='dropdown-item' href='#'>Populaire</a></li>");
    var option2 = $("<li><a id='a-dropdown-item2' class='dropdown-item' href='#'>Du moment</a></li>");
    var option3 = $("<li><a id='a-dropdown-item3' class='dropdown-item' href='#'>À venir</a></li>");

    dropdown.append(option1);
    dropdown.append(option2);
    dropdown.append(option3);

    $("#film-dropdown").append(dropdown);

    dropdown.hide();
}

$(document).ready(function() {
    showDropdown();

    $("#film-dropdown").hover(
        function() {
            $(this).find(".dropdown-menu").slideDown();
        },
        function() {
            $(this).find(".dropdown-menu").slideUp();
        }
    );
});