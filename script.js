/* ============================================
   Portfolio — Antoine Brévard — script.js
   Partagé par toutes les pages (index, profil, competences, projets, contact).
   ============================================ */

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

/* ---------- Horloge (bandeau télémétrie) ---------- */
function tickClock(){
  const el = $("#clock");
  if (!el) return;
  const now = new Date();
  const pad = n => String(n).padStart(2, "0");
  el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}

/* ---------- Menu mobile ---------- */
function initNavToggle(){
  const header = $("#telemetry");
  const toggle = $("#navToggle");
  if (!toggle) return;
  toggle.addEventListener("click", () => {
    const open = header.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", String(open));
  });
}

/* ---------- Page active dans la nav (site multi-pages, pas un scrollspy) ---------- */
function initNavActive(){
  const page = document.body.dataset.page;
  if (!page) return;
  $$(".telemetry-nav a").forEach(a => a.classList.toggle("is-active", a.dataset.nav === page));
}

/* ---------- Révélation au scroll ---------- */
function initReveal(){
  const targets = $$(".about-grid, .exp-card, .skill-row, .mission-card, .terrain-strip figure, .contact-grid");
  targets.forEach(el => el.classList.add("reveal"));

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting){
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  targets.forEach(el => {
    observer.observe(el);
    setTimeout(() => el.classList.add("is-visible"), 2500); // filet de sécurité
  });
}

/* ---------- Formulaire de contact (mailto, site statique) ---------- */
function initContactForm(){
  const form = $("#contactForm");
  if (!form) return;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const nom = fd.get("nom") || "";
    const email = fd.get("email") || "";
    const message = fd.get("message") || "";
    const body = `De : ${nom} (${email})\n\n${message}`;
    window.location.href = `mailto:antoinebrevard8@gmail.com?subject=${encodeURIComponent("Contact via portfolio")}&body=${encodeURIComponent(body)}`;
  });
}

/* ---------- Année du footer ---------- */
function initFooterYear(){
  const el = $("#footerYear");
  if (el) el.textContent = new Date().getFullYear();
}

/* ---------- Feux de départ (accueil uniquement) ----------
   Séquence façon départ F1 / 24H du Mans : 5 feux qui s'allument un par un,
   une pause (durée volontairement aléatoire, comme un vrai départ, pour
   qu'on ne puisse pas anticiper), puis extinction simultanée = display du site.
   Ne joue qu'une fois par session (sessionStorage) et jamais si l'utilisateur
   a demandé moins d'animations (le CSS le masque déjà, on sécurise en JS aussi). */
function initStartLights(){
  const overlay = $("#startLights");
  if (!overlay) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const alreadyPlayed = sessionStorage.getItem("psio_intro_played");

  if (reduceMotion || alreadyPlayed){
    overlay.remove();
    return;
  }

  document.body.classList.add("intro-lock");
  const lights = $$(".light", overlay);
  const skipBtn = $("#skipIntro", overlay);

  function finish(){
    sessionStorage.setItem("psio_intro_played", "1");
    overlay.classList.add("is-out");
    document.body.classList.remove("intro-lock");
    setTimeout(() => overlay.remove(), 700);
  }

  skipBtn.addEventListener("click", finish);

  let i = 0;
  const stepDelay = 480;
  function lightNext(){
    if (i < lights.length){
      lights[i].classList.add("is-lit");
      i++;
      setTimeout(lightNext, stepDelay);
    } else {
      const holdTime = 650 + Math.random() * 900; // aléatoire, comme un vrai départ
      setTimeout(() => {
        lights.forEach(l => l.classList.remove("is-lit"));
        overlay.classList.add("flash");
        setTimeout(finish, 480);
      }, holdTime);
    }
  }
  setTimeout(lightNext, 400);
}

document.addEventListener("DOMContentLoaded", () => {
  tickClock();
  setInterval(tickClock, 1000);
  initNavToggle();
  initNavActive();
  initReveal();
  initContactForm();
  initFooterYear();
  initStartLights();
});
