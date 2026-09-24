/* =========================================================================
   LE RELEVÉ — script partagé
   Site multi-pages. Deux comportements portent l'interaction : la typo qui
   se déforme sous la charge du défilement et la révélation dans les lettres
   du nom. Le reste affiche les documents déposés depuis l'admin.
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
  const cibles = $$(".entree, .jury-case, .hero-releve, .tete");
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

/* ---------- Documents déposés ----------
   Les veilles et la grille de compétences sont des fichiers déposés depuis
   l'admin (/admin/). Decap CMS les range dans Veilles/ et documents/grille/,
   et tient la liste dans content/*.json : la page lit ce fichier au lieu
   d'être réécrite à chaque dépôt. */
async function lireContenu(chemin){
  // no-cache : un dépôt doit apparaître au rechargement suivant, pas après
  // l'expiration du cache de GitHub Pages
  const rep = await fetch(chemin, { cache: "no-cache" });
  if (!rep.ok) throw new Error(`${chemin} : ${rep.status}`);
  return rep.json();
}

function format(fichier){
  const ext = (fichier.split("?")[0].split(".").pop() || "").toLowerCase();
  return ext && ext !== fichier.toLowerCase() ? ext.toUpperCase() : "Fichier";
}

function dateLisible(iso){
  if (!iso) return "";
  const d = new Date(iso);
  if (isNaN(d)) return iso;
  return d.toLocaleDateString("fr-FR", { day: "numeric", month: "long", year: "numeric" });
}

function el(balise, classe, texte){
  const n = document.createElement(balise);
  if (classe) n.className = classe;
  if (texte != null) n.textContent = texte;
  return n;
}

function vide(cible, message){
  cible.replaceChildren(el("p", "vide", message));
}

async function veilles(){
  const liste = $("#veilles");
  if (!liste) return;
  try {
    const { items = [] } = await lireContenu("content/veilles.json");
    const deposees = items.filter(v => v && v.fichier);
    if (!deposees.length){
      vide(liste, "Aucune veille déposée pour l'instant.");
      return;
    }
    // la plus récente en tête : c'est elle qu'on vient chercher
    deposees.sort((a, b) => String(b.date || "").localeCompare(String(a.date || "")));
    liste.replaceChildren(...deposees.map((v, i) => {
      const lien = el("a", "entree");
      lien.href = v.fichier;
      lien.setAttribute("download", "");
      lien.append(el("span", "entree-num", String(i + 1).padStart(2, "0")));
      lien.append(el("h3", "entree-titre", v.titre || "Veille"));
      lien.append(el("p", "entree-mot", v.description || ""));
      const puces = el("span", "entree-comp");
      puces.append(el("span", "puce", format(v.fichier)));
      if (v.date) puces.append(el("span", "puce", dateLisible(v.date)));
      lien.append(puces);
      const fleche = el("span", "entree-fleche", "↓");
      fleche.setAttribute("aria-hidden", "true");
      lien.append(fleche);
      return lien;
    }));
  } catch (e){
    vide(liste, "La liste des veilles n'a pas pu être chargée. Ouvrez le site en ligne plutôt que depuis le disque.");
  }
}

async function grille(){
  const zone = $("#grille");
  if (!zone) return;
  try {
    const g = await lireContenu("content/grille.json");
    if (!g.fichier){
      vide(zone, "La grille de compétences n'a pas encore été déposée.");
      return;
    }
    const actions = el("div", "document-actions");
    const telecharger = el("a", "btn btn--plein", `Télécharger (${format(g.fichier)})`);
    telecharger.href = g.fichier;
    telecharger.setAttribute("download", "");
    const ouvrir = el("a", "btn", "Ouvrir dans un onglet");
    ouvrir.href = g.fichier;
    ouvrir.target = "_blank";
    ouvrir.rel = "noopener";
    actions.append(telecharger, ouvrir);

    const morceaux = [];
    if (g.miseAJour) morceaux.push(el("p", "etiquette", `Mise à jour le ${dateLisible(g.miseAJour)}`));
    if (g.commentaire) morceaux.push(el("p", "chapeau document-note", g.commentaire));
    morceaux.push(actions);

    // seul un PDF s'affiche dans la page ; un tableur se télécharge
    if (format(g.fichier) === "PDF"){
      const apercu = el("iframe", "apercu");
      apercu.src = g.fichier;
      apercu.title = "Grille de compétences";
      apercu.loading = "lazy";
      morceaux.push(apercu);
    }
    zone.replaceChildren(...morceaux);
  } catch (e){
    vide(zone, "La grille n'a pas pu être chargée. Ouvrez le site en ligne plutôt que depuis le disque.");
  }
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

document.addEventListener("DOMContentLoaded", () => {
  chrono();
  setInterval(chrono, 1000);
  annee();
  typoSousCharge();
  revelationLettres();
  revelation();
  veilles();
  grille();
  comparateur();
});
