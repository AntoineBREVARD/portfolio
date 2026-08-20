/* ============================================
   Portfolio — Antoine Brévard — script.js
   Partagé par toutes les pages (index, profil, competences, projets, contact).
   ============================================ */

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

function escapeHtml(str = ""){
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}
function setText(id, value){
  const el = document.getElementById(id);
  if (el && value != null) el.textContent = value;
}
function setHTML(id, value){
  // Champs qui autorisent volontairement un peu de balisage (ex: <strong>),
  // saisi tel quel dans le CMS — l'auteur est le seul éditeur du site.
  const el = document.getElementById(id);
  if (el && value != null) el.innerHTML = value;
}
function setSrc(id, value){
  const el = document.getElementById(id);
  if (el && value) el.src = value;
}

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
    const target = $("#contactEmail");
    const to = target ? target.textContent.trim() : "antoinebrevard8@gmail.com";
    window.location.href = `mailto:${to}?subject=${encodeURIComponent("Contact via portfolio")}&body=${encodeURIComponent(body)}`;
  });
}

/* ---------- Contenu éditable via le CMS (content/*.json) ----------
   Chaque page garde son texte d'origine dans le HTML (secours si le fetch
   échoue ou si JS est désactivé) ; ces fonctions le remplacent par la
   dernière version publiée via l'admin dès que le JSON correspondant est
   chargé. */
function renderHome(d){
  setText("heroEyebrow", d.eyebrow);
  if (d.heroTitleLine1) setText("heroLine1", d.heroTitleLine1.toUpperCase());
  if (d.heroTitleLine2) setText("heroLine2", d.heroTitleLine2.toUpperCase());
  setHTML("heroTagline", d.heroTagline);
  // "photoMode" vient de content/settings.json (réglage global IA/réelle) :
  // repli sur la version réelle si la version IA n'a pas été renseignée.
  const heroImg = (d.photoMode === "ia" && d.heroImageIa) ? d.heroImageIa : d.heroImageReelle;
  setSrc("heroImage", heroImg);
  const cv = document.getElementById("cvLink");
  if (cv && d.cvFile) cv.href = d.cvFile;
  setText("reperesTitle", d.reperesTitle);
  setHTML("structureValue", `${escapeHtml(d.structureLabel)}<br><span class="fact-sub">${escapeHtml(d.structureSub)}</span>`);
  setHTML("formationValue", `${escapeHtml(d.formationLabel)}<br><span class="fact-sub">${escapeHtml(d.formationSub)}</span>`);
}

function renderProfil(d){
  setHTML("aboutIntro", d.aboutIntro);
  setHTML("aboutBody", d.aboutBody);
  setSrc("aboutPhoto", d.photo);
  setHTML("aboutPhotoTag", d.photoTag);
  setHTML("formationValue", `${escapeHtml(d.formationLabel)}<br><span class="fact-sub">${escapeHtml(d.formationSub)}</span>`);
  setHTML("certificationValue", `${escapeHtml(d.certificationLabel)}<br><span class="fact-sub">${escapeHtml(d.certificationSub)}</span>`);
  setText("expTitle", d.expTitle);
  setText("expPlace", d.expPlace);
  setText("expPeriod", d.expPeriod);
  setHTML("expContext", d.expContext);
  const list = document.getElementById("expMissions");
  if (list && Array.isArray(d.expMissions)){
    list.innerHTML = d.expMissions.map(m => `<li>${escapeHtml(m)}</li>`).join("");
  }
}

function renderCompetences(d){
  const board = document.getElementById("skillsBoard");
  if (!board || !Array.isArray(d.items)) return;
  board.innerHTML = d.items.map(item => `
    <div class="skill-row">
      <span class="skill-status mono">ACTIF</span>
      <span class="skill-name">${escapeHtml(item.name)}</span>
      <span class="skill-context">${escapeHtml(item.context)}</span>
    </div>
  `).join("");
}

function renderProjets(d){
  const grid = document.getElementById("missionsGrid");
  if (grid && Array.isArray(d.missions)){
    grid.innerHTML = d.missions.map(m => `
      <article class="mission-card">
        <span class="mission-tag mono">${escapeHtml(m.tag)}</span>
        <h3>${escapeHtml(m.title)}</h3>
        <p>${escapeHtml(m.description)}</p>
      </article>
    `).join("");
  }
  const strip = document.getElementById("terrainStrip");
  if (strip && Array.isArray(d.terrain)){
    strip.innerHTML = d.terrain.map(t => {
      const img = (d.photoMode === "ia" && t.imageIa) ? t.imageIa : t.imageReelle;
      return `<figure><img src="${escapeHtml(img)}" alt="${escapeHtml(t.caption)}"><figcaption class="mono">${escapeHtml(t.caption)}</figcaption></figure>`;
    }).join("");
  }
}

function renderContact(d){
  setText("contactIntro", d.intro);
  const email = document.getElementById("contactEmail");
  if (email && d.email){ email.href = "mailto:" + d.email; email.textContent = d.email; }
  const li = document.getElementById("contactLinkedin");
  if (li && d.linkedin) li.href = d.linkedin;
  const gh = document.getElementById("contactGithub");
  if (gh && d.github) gh.href = d.github;
  setText("contactCity", d.city);
}

function initContent(){
  const page = document.body.dataset.page;
  const map = {
    accueil: { file: "content/home.json", render: renderHome },
    profil: { file: "content/profil.json", render: renderProfil },
    competences: { file: "content/competences.json", render: renderCompetences },
    projets: { file: "content/projets.json", render: renderProjets },
    contact: { file: "content/contact.json", render: renderContact }
  };
  const entry = map[page];
  if (!entry) return Promise.resolve();

  const fetchJson = (file) => fetch(file, { cache: "no-store" }).then(res => (res.ok ? res.json() : null)).catch(() => null);

  // content/settings.json porte le réglage global "photo IA / réelle" utilisé
  // par le hero et le terrain — on le charge à côté du contenu de la page,
  // sans bloquer le rendu si jamais ce fichier manque.
  return Promise.all([fetchJson(entry.file), fetchJson("content/settings.json")])
    .then(([data, settings]) => {
      if (!data) return;
      if (settings && settings.photoMode) data.photoMode = settings.photoMode;
      entry.render(data);
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
   Rejoue à chaque chargement de la page (pas juste une fois par session) —
   seule prefers-reduced-motion la désactive, pour l'accessibilité. */
function initStartLights(){
  const overlay = $("#startLights");
  if (!overlay) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  if (reduceMotion){
    overlay.remove();
    return;
  }

  document.body.classList.add("intro-lock");
  const lights = $$(".light", overlay);
  const skipBtn = $("#skipIntro", overlay);

  function finish(){
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
  initContactForm();
  initFooterYear();
  initStartLights();
  // Le contenu (content/*.json) doit être injecté avant d'attacher les
  // observateurs de révélation au scroll, sinon les blocs reconstruits
  // (compétences, missions, terrain) démarrent sans l'animation.
  initContent().finally(initReveal);
});
