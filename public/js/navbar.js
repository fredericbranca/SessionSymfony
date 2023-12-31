// Déclaration des variables sans initialisation
let hamburger_button, nav_list, nav_overlay, body, content;
// Attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", function () {
  // Initialisation des variables
  hamburger_button = document.querySelector(".hamburger");
  nav_list = document.querySelector(".nav-links");
  nav_overlay = document.querySelector("#overlay");
  body = document.querySelector("body");
  content = document.querySelector(".content");
  wrapper = document.querySelector('#wrapper');

  // Fonction pour ouvrir ou fermer le bandeau de la navbar
  function toggleMenu(open = true) {
    if (open) {
      hamburger_button.classList.add("is-active");
      nav_list.classList.add("is-active");
      nav_list.style.animation = "slide-in 0.5s both";
      content.style.transform = "translateX(90%)";
      content.style.transition = "transform 0.5s";
      content.style.zIndex = "-1";
      nav_overlay.style.zIndex = 1;
      nav_overlay.style.display = "block";
      wrapper.classList.add('overflow');
      body.classList.add('overflow');
    } else {
      hamburger_button.classList.remove("is-active");
      wrapper.classList.remove('overflow');
      body.classList.remove('overflow');
      nav_list.style.animation = "slide-out 0.5s both";
      content.style.transform = "translateX(0%)";
      nav_overlay.style.display = "none";
      nav_overlay.style.zIndex = -1;
      content.style.zIndex = "0";
    }
  }

  // Permet de faire l'animation du content avant de le mettre en display none
  nav_list.addEventListener('animationend', function (event) {
    if (event.animationName === 'slide-out') {
      nav_list.classList.remove("is-active");
    }
  });

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

  // Enlève les styles d'animation du menu burger et du bandeau quand la largeur de la fenêtre est > 991px (mode bureau)
  window.addEventListener("resize", function () {

    if (this.innerWidth > 991) {
      toggleMenu(false);
      content.style.transform = "none";
      content.style.transition = "none";
      nav_list.classList.remove("is-active");
      nav_list.style.animation = "none";
    }
  })
});
