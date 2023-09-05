// Déclaration des variables sans initialisation
let hamburger_button, nav_list, nav_overlay, body, content, links;
// Attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {

  // Initialisation des variables
  hamburger_button = document.querySelector(".hamburger");
  nav_list = document.querySelector(".nav-links");
  nav_overlay = document.querySelector("#overlay");
  body = document.querySelector("body");
  content = document.querySelector(".content");
  links = document.querySelectorAll(".confirm-delete");

  // Fonction pour ouvrir ou fermer le bandeau de la navbar
  function toggleMenu(open = true) {
    if (open) {
      hamburger_button.classList.add("is-active");
      nav_list.classList.add("is-active");
      nav_overlay.style.zIndex = 1;
      nav_overlay.style.display = "block";
      nav_overlay.style.backgroundColor = "rgb(0, 0, 0, 0.85)";
      content.style.zIndex = "-1";
      nav_list.classList.remove("nav-out");
      content.classList.remove("content-out");
      content.classList.add("content-active");
      nav_list.classList.add("nav-active");
    } else {
      hamburger_button.classList.remove("is-active");
      nav_list.classList.remove("is-active");
      nav_overlay.style.zIndex = -1;
      nav_overlay.style.display = "none";
      content.style.zIndex = "0";
      nav_list.classList.remove("nav-active");
      content.classList.remove("content-active");
      nav_list.classList.add("nav-out");
      content.classList.add("content-out");
    }
  }

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

  // Écoute les clics
  document.addEventListener("click", function (event) {
    console.log(event.target);
    // Si le menu est ouvert et que le clic n'est ni sur le bouton hamburger, ni dans le menu lui-même, alors fermez le menu
    if (hamburger_button.classList.contains("is-active")) {
      if (
        hamburger_button.contains(event.target) ||
        nav_overlay.contains(event.target)
      ) {
        toggleMenu(false);
      }
    } else {
      toggleMenu(true);
    }
  });

  window.addEventListener('resize', function() {

    toggleMenu(false);
    
    if (this.innerWidth > 992) {

      
      nav_list.classList.remove("nav-active");
      nav_list.classList.remove("nav-out");
      content.classList.remove("content-active");
      content.classList.remove("content-out");

    }
});

});