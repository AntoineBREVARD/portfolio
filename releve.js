/* =========================================================================
   LE RELEVÉ — script partagé
   Trois comportements, rien de plus : la typo qui se déforme sous la charge
   du défilement, le rail qui dit où l'on est, et la matrice qui se lit dans
   les deux sens.
   ========================================================================= */

const $  = (s, c = document) => c.querySelector(s);
const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

const MOINS_DE_MOUVEMENT = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

/* ---------- chrono du rail ---------- */
function chrono(){
  const el = $("#chrono");
  if (!el) return;
  const n = new Date(), p = v => String(v).padStart(2, "0");
  el.textContent = `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
}

/* ---------- La typo sous la charge ----------
   Bricolage Grotesque est une police variable : sa graisse et sa largeur
   sont des axes continus. On les mappe sur la progression du défilement,
   donc le nom se comprime quand on descend. C'est le propos du site rendu
   physique — un système qui encaisse une charge — et non un effet ajouté.

   On écrit dans deux variables CSS plutôt que dans le style de l'élément :
   le CSS reste maître de la façon dont elles sont utilisées. */
function typoSousCharge(){
  const cible = $(".hero-nom");
  if (!cible || MOINS_DE_MOUVEMENT) return;

  const racine = document.documentElement;
  let enAttente = false;

  function appliquer(){
    enAttente = false;
    const h = window.innerHeight || 1;
    // 0 en haut de page, 1 quand on a défilé d'un écran complet
    const charge = Math.min(1, Math.max(0, window.scrollY / h));
    racine.style.setProperty("--wght", Math.round(760 - charge * 460));
    racine.style.setProperty("--wdth", (100 - charge * 24).toFixed(1));
  }

  window.addEventListener("scroll", () => {
    // une seule écriture par image : sinon on force un recalcul de style
    // à chaque événement de défilement
    if (!enAttente){ enAttente = true; requestAnimationFrame(appliquer); }
  }, { passive: true });
  appliquer();
}

/* ---------- Rail : secteur courant ---------- */
function rail(){
  const points = $$(".rail-secteur");
  const position = $("#position");
  if (!points.length) return;

  const blocs = points
    .map(p => ({ point: p, cible: document.getElementById(p.dataset.vers) }))
    .filter(b => b.cible);

  points.forEach(p => {
    p.addEventListener("click", () => {
      const c = document.getElementById(p.dataset.vers);
      if (c) c.scrollIntoView({ behavior: MOINS_DE_MOUVEMENT ? "auto" : "smooth", block: "start" });
    });
  });

  const obs = new IntersectionObserver(entrees => {
    entrees.forEach(e => {
      if (!e.isIntersecting) return;
      const b = blocs.find(x => x.cible === e.target);
      if (!b) return;
      points.forEach(p => p.classList.remove("is-on"));
      b.point.classList.add("is-on");
      if (position) position.textContent = b.point.dataset.nom || "";
    });
  }, { rootMargin: "-45% 0px -45% 0px" });

  blocs.forEach(b => obs.observe(b.cible));
}

/* ---------- Révélation au défilement ---------- */
function revelation(){
  const cibles = $$(".entree, .matrice-bloc, .jury-case, .hero-releve, .tete");
  if (!cibles.length) return;
  if (MOINS_DE_MOUVEMENT){ cibles.forEach(c => c.classList.add("est-vu")); return; }

  cibles.forEach(c => c.classList.add("leve"));
  const obs = new IntersectionObserver(entrees => {
    entrees.forEach(e => {
      if (!e.isIntersecting) return;
      e.target.classList.add("est-vu");
      obs.unobserve(e.target);
    });
  }, { threshold: .12 });

  cibles.forEach(c => {
    obs.observe(c);
    // filet de sécurité : si l'observateur ne se déclenche jamais (page
    // courte, navigateur capricieux), le contenu ne doit pas rester invisible
    setTimeout(() => c.classList.add("est-vu"), 2200);
  });
}

/* ---------- Matrice : lecture dans les deux sens ----------
   Survoler une compétence allume les réalisations qui la prouvent ;
   survoler une réalisation allume les compétences qu'elle couvre.
   C'est la traçabilité du référentiel rendue manipulable — la seule
   interaction du site qui apporte une information plutôt qu'un effet. */
function matriceCroisee(){
  const lignes = $$(".ligne[data-preuves]");
  if (!lignes.length) return;

  const clef = el => (el.dataset.preuves || "").split(/\s+/).filter(Boolean);

  function allumer(refs){
    const ens = new Set(refs);
    lignes.forEach(l => {
      const lie = clef(l).some(r => ens.has(r));
      l.classList.toggle("is-lie", lie);
      $$(".preuve", l).forEach(p => p.classList.toggle("is-lie", ens.has(p.dataset.ref)));
    });
  }
  function eteindre(){
    lignes.forEach(l => {
      l.classList.remove("is-lie");
      $$(".preuve", l).forEach(p => p.classList.remove("is-lie"));
    });
  }

  $$(".preuve[data-ref]").forEach(p => {
    p.addEventListener("mouseenter", () => allumer([p.dataset.ref]));
    p.addEventListener("focus",      () => allumer([p.dataset.ref]));
    p.addEventListener("mouseleave", eteindre);
    p.addEventListener("blur",       eteindre);
  });
}

/* ---------- Année du pied ---------- */
function annee(){
  const el = $("#annee");
  if (el) el.textContent = new Date().getFullYear();
}

document.addEventListener("DOMContentLoaded", () => {
  chrono();
  setInterval(chrono, 1000);
  annee();
  typoSousCharge();
  rail();
  revelation();
  matriceCroisee();
});
