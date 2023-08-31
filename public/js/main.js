// Ajout d'une confirmation de supression sur les liens ayant la classe confirm-delete
// Attend que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function () {

    // Récupère les liens avec la classe confirm-delete
    const links = document.querySelectorAll('.confirm-delete');

    // On vérifie qu'il existe au moins un lien
    if (links.length > 0) {
        links.forEach(function (link) {
            // Ajout d'un écouteur d'évènement au clic d'un lien
            link.addEventListener('click', function (event) {
                // confirm() Permet d'afficher une confirmation de suppression en annulant l'action par défaut du lien
                if (!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
                    event.preventDefault();
                }
            });
        });
    }
});

// NAVBAR - Fonction pour ouvrir ou fermer le bandeau de la navbar
function toggleMenu() {
    const hamburger_button = document.querySelector('.hamburger');
    const nav_list = document.querySelector('.nav-links');
    // const navOverlay = document.querySelector('.overlay');
    if (!hamburger_button.classList.contains('is-active')) {
        hamburger_button.classList.add('is-active');
        nav_list.classList.add('is-active');
        // navOverlay.style.zIndex = 1;
        // navOverlay.style.display = 'block';
    } else {
        hamburger_button.classList.remove('is-active');
        nav_list.classList.remove('is-active');
        // navOverlay.style.zIndex = -1;
        // navOverlay.style.display = 'none';
    }
}