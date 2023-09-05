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
      nav_list.style.animation = "slide-in 0.5s both;"
      body.classList.add('overflow');
      nav_overlay.style.zIndex = 1;
      content.style.zIndex = "-1";
      nav_overlay.style.display = "block";
    } else {
      hamburger_button.classList.remove("is-active");
      nav_list.classList.remove("is-active");
      body.classList.remove('overflow');
      nav_list.style.animation = "slide-out 0.5s both;"
      nav_overlay.style.display = "none";
      nav_overlay.style.zIndex = -1;
      content.style.zIndex = "0";
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
    // console.log(event.target);
    // Si le menu est ouvert et que le clic n'est ni sur le bouton hamburger, ni dans le menu lui-même, alors fermez le menu
    if (hamburger_button.classList.contains("is-active")) {
      if (
        hamburger_button.contains(event.target) ||
        nav_overlay.contains(event.target)
      ) {
        toggleMenu(false);
      }
    } else {
      hamburger_button.contains(event.target) ? toggleMenu(true) : "";
    }
  });

  window.addEventListener("resize", function() {
    console.log(this.innerWidth);
    if (this.innerWidth > 991) {
      toggleMenu(false);
      nav_list.classList.remove("nav-active");
      nav_list.classList.remove("nav-out");
    } 

  })
});
