// NAVBAR - Fonction pour ouvrir ou fermer le bandeau de la navbar
function toggleMenu() {
  const hamburger_button = document.querySelector(".hamburger");
  const nav_list = document.querySelector(".nav-links");
  const nav_overlay = document.querySelector("#overlay");
  const body = document.querySelector("body");
  const content = document.querySelector(".content");

  if (!hamburger_button.classList.contains("is-active")) {
    hamburger_button.classList.add("is-active");
    nav_list.classList.add("is-active");
    nav_overlay.style.zIndex = 1;
    nav_overlay.style.display = "block";
    body.style.backgroundColor = "#242424";
    content.style.display = "none";
  } else {
    closeMenu();
  }
}

function closeMenu() {
  const hamburger_button = document.querySelector(".hamburger");
  const nav_list = document.querySelector(".nav-links");
  const nav_overlay = document.querySelector("#overlay");
  const body = document.querySelector("body");
  const content = document.querySelector(".content");

  hamburger_button.classList.remove("is-active");
  nav_list.classList.remove("is-active");
  nav_overlay.style.zIndex = -1;
  nav_overlay.style.display = "none";
  body.style.backgroundColor = "var(--bg-color-1)";
  content.style.display = "block";
}

// Attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {
  // Ajout d'une confirmation de supression sur les liens ayant la classe confirm-delete
  // Récupère les liens avec la classe confirm-delete
  const links = document.querySelectorAll(".confirm-delete");

  // On vérifie qu'il existe au moins un lien
  if (links.length > 0) {
    links.forEach(function (link) {
      // Ajout d'un écouteur d'évènement au clic d'un lien
      link.addEventListener("click", function (event) {
        // confirm() Permet d'afficher une confirmation de suppression en annulant l'action par défaut du lien
        if (!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
          event.preventDefault();
        }
      });
    });
  }

  const hamburger_button = document.querySelector(".hamburger");
  const nav_list = document.querySelector(".nav-links");

  // Écoute les clics
  document.addEventListener("click", function (event) {
    console.log(event);
    // Si le menu est ouvert et que le clic n'est ni sur le bouton hamburger, ni dans le menu lui-même, alors fermez le menu
    if (hamburger_button.classList.contains("is-active")) {
      if (!hamburger_button.contains(event.target) && !nav_list.contains(event.target)) {
        closeMenu();
      }
    }
  });

  window.addEventListener('resize', function() {
    // ferme le menu si taille d'écran supérieur à 900px
    if (window.innerWidth >= 901) { 
        closeMenu();
    }
});

});

