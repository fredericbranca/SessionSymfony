// NAVBAR - Fonction pour ouvrir ou fermer le bandeau de la navbar
function toggleMenu() {
  const hamburger_button = document.querySelector(".hamburger");
  const nav_list = document.querySelector(".nav-links");
  const nav_overlay = document.querySelector("#overlay");
  const body = document.querySelector("body");
  const content = document.querySelector(".content");

  if (window.innerWidth < 992) {
    if (!hamburger_button.classList.contains("is-active")) {
      hamburger_button.classList.add("is-active");
      nav_list.classList.add("is-active");
      nav_overlay.style.zIndex = 1;
      nav_overlay.style.display = "block";
      body.style.backgroundColor = "rgb(0, 0, 0, 0.85)";
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
      body.style.backgroundColor = "var(--bg-color-1)";
      content.style.zIndex = "0";
      nav_list.classList.remove("nav-active");
      content.classList.remove("content-active");
      nav_list.classList.add("nav-out");
      content.classList.add("content-out");
    }
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
  content.style.zIndex = "0";
  nav_list.classList.remove("nav-active");
  content.classList.remove("content-active");
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
    console.log(event.target);
    // Si le menu est ouvert et que le clic n'est ni sur le bouton hamburger, ni dans le menu lui-même, alors fermez le menu
    if (hamburger_button.classList.contains("is-active")) {
      if (
        !hamburger_button.contains(event.target) &&
        !nav_list.contains(event.target)
      ) {
        toggleMenu();
      }
    }
  });

  window.addEventListener("resize", function () {
    closeMenu();
  });

  // Ajoute une icone before à la classe link
  const link = document.querySelectorAll(".link");
//  .setProperty('content',"url(/img/arrow-up.svg)")
for (var i in link){
  link[i].setAttribute('data-before', "/img/arrow-up.svg")
}

console.log(getComputedStyle(link, ':before').getPropertyValue('content'));
 
});