// Attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {

    // Rendre cliquable les lignes d'un tableau et rediriger vers l'URL spécifiée
    document.querySelectorAll('.clickable-row').forEach(function (row) {
        row.addEventListener('click', function () {
            window.location.href = row.getAttribute('data-url');
        });
    });

});