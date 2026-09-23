/* =========================================================================
   LE RELEVÉ — script partagé
   Site multi-pages. Trois comportements portent l'interaction : la typo qui
   se déforme sous la charge du défilement, la révélation dans les lettres du
   nom, et la matrice de compétences qui se lit dans les deux sens.
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

/* ---------- Révélation dans les lettres ----------
   Le nom est le masque. On déplace le centre du cercle de découpe en suivant
   le pointeur, avec un amortissement : viser directement la position de la
   souris donne un mouvement sec, l'interpolation le rend fluide.
   La zone sensible est le hero entier, pas seulement les lettres — sinon la
   révélation ne s'amorce que lorsqu'on est déjà pile sur un glyphe, et on ne
   découvre jamais l'effet. */
function revelationLettres(){
  const nom = $("#heroNom");
  const couche = $("#heroSystemes");
  const invite = $("#heroInvite");
  const zone = nom && nom.closest(".hero");
  if (!nom || !couche || !zone) return;

  const tactile = window.matchMedia("(pointer: coarse)").matches;

  // Pas de pointeur persistant sur tactile, et pas d'animation si elle est
  // refusée : on montre la trame en continu à faible présence plutôt que de
  // laisser un effet mort.
  if (tactile || MOINS_DE_MOUVEMENT){
    couche.style.webkitMaskImage = "none";
    couche.style.maskImage = "none";
    couche.style.opacity = ".5";
    if (invite) invite.classList.add("est-cache");
    return;
  }

  const cible  = { x: .5, y: .42 };
  const actuel = { x: .5, y: .42 };
  let anime = false;

  function rayon(){
    // la lentille suit la taille du texte : figée en pixels, elle était
    // minuscule sur un grand écran et couvrait tout sur un petit
    return Math.max(90, Math.min(260, nom.getBoundingClientRect().height * .42));
  }

  zone.addEventListener("mousemove", e => {
    const r = nom.getBoundingClientRect();
    cible.x = (e.clientX - r.left) / r.width;
    cible.y = (e.clientY - r.top) / r.height;
    if (!anime){ anime = true; requestAnimationFrame(boucle); }
  });

  function boucle(){
    const r = nom.getBoundingClientRect();
    actuel.x += (cible.x - actuel.x) * .13;
    actuel.y += (cible.y - actuel.y) * .13;
    couche.style.setProperty("--mx", (actuel.x * r.width).toFixed(1) + "px");
    couche.style.setProperty("--my", (actuel.y * r.height).toFixed(1) + "px");
    couche.style.setProperty("--r", rayon().toFixed(0) + "px");
    // on s'arrête quand le mouvement devient imperceptible : inutile de
    // garder une boucle d'animation vivante en permanence
    if (Math.abs(cible.x - actuel.x) > .0008 || Math.abs(cible.y - actuel.y) > .0008){
      requestAnimationFrame(boucle);
    } else {
      anime = false;
    }
  }

  // l'invite disparaît dès qu'on a compris : elle ne sert qu'une fois
  zone.addEventListener("mouseenter", () => {
    if (invite) setTimeout(() => invite.classList.add("est-cache"), 1400);
  }, { once: true });

  boucle();
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

/* ---------- Comparateur avant / après ----------
   Un input range invisible superposé au bloc : on hérite gratuitement du
   clavier, du tactile et de l'accessibilité au lieu de recoder un
   glisser-déposer. */
function comparateur(){
  $$("[data-ba]").forEach(bloc => {
    const curseur = $(".ba-curseur", bloc);
    const cadre = $(".ba-cadre", bloc);
    if (!curseur || !cadre) return;
    const appliquer = () => cadre.style.setProperty("--ba", curseur.value + "%");
    curseur.addEventListener("input", appliquer);
    appliquer();
  });
}

/* ---------- Année du pied ---------- */
function annee(){
  const el = $("#annee");
  if (el) el.textContent = new Date().getFullYear();
}


/* ---------- Lien courant dans le rail ----------
   Le rail est un fragment de gabarit, identique sur toutes les pages : il ne
   sait pas ou l'on se trouve. On compare donc les adresses au chargement.
   L'accueil ne compte que sur une correspondance exacte, sinon il resterait
   allume partout. */
function railCourant(){
  const liens = document.querySelectorAll(".rail-nav a");
  if (!liens.length) return;
  const chemin = u => { try { return new URL(u).pathname.replace(/\/+$/, "") || "/"; }
                        catch (e) { return null; } };
  const ici = location.pathname.replace(/\/+$/, "") || "/";
  let meilleur = null, meilleurLong = -1;
  liens.forEach(a => {
    const cible = chemin(a.href);
    if (cible === null) return;
    if (cible === "/" || cible === "/index.php") {
      if (ici === cible && meilleurLong < 0) { meilleur = a; meilleurLong = 0; }
      return;
    }
    if ((ici === cible || ici.startsWith(cible + "/")) && cible.length > meilleurLong) {
      meilleur = a; meilleurLong = cible.length;
    }
  });
  if (meilleur) meilleur.classList.add("est-active");
}

document.addEventListener("DOMContentLoaded", () => {
  chrono();
  setInterval(chrono, 1000);
  railCourant();
  annee();
  typoSousCharge();
  revelationLettres();
  revelation();
  matriceCroisee();
  comparateur();
});
