$(document).ready(function () {
    $(".toggle-password").on("click", function () {
        const passwordField = $(this).siblings("input");
        const type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        // Change icon
        $(this).find("i").toggleClass("fa-eye fa-eye-slash");
    });
});
