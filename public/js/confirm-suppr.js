// Déclaration des variables sans initialisation
let links;

// Attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {

    // Initialisation des variables
    links = document.querySelectorAll(".confirm-delete");

    // Ajout d'une confirmation de suppression sur les liens ayant la classe confirm-delete
    if (links.length > 0) {
        links.forEach(function (link) {
            link.addEventListener("click", function (event) {
                if (!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
                    event.preventDefault();
                }
            });
        });
    }
});