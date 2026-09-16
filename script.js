/* ============================================================
   Portfolio Antoine Brévard — v2
   Script partagé par toutes les pages.
   ============================================================ */

const $  = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

function escapeHtml(str = ""){
  return String(str)
    .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;").replace(/'/g, "&#39;");
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
  const now = new Date(), pad = n => String(n).padStart(2, "0");
  el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}

/* ---------- Menu mobile ---------- */
function initNavToggle(){
  const header = $("#telemetry"), toggle = $("#navToggle");
  if (!toggle || !header) return;
  toggle.addEventListener("click", () => {
    const open = header.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
  });
}

/* ---------- Page active dans la nav ---------- */
function initNavActive(){
  const page = document.body.dataset.page;
  if (!page) return;
  $$(".telemetry-nav a").forEach(a => a.classList.toggle("is-active", a.dataset.nav === page));
}

/* ---------- Révélation au scroll ---------- */
function initReveal(){
  const targets = $$(".mission-card, .fact, .case-step, .matrix-bloc, .veille-entry, .jury, .contact-grid, .solutions");
  if (!targets.length) return;
  targets.forEach(el => el.classList.add("reveal"));

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting){
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: .1 });

  targets.forEach(el => {
    observer.observe(el);
    setTimeout(() => el.classList.add("is-visible"), 2500); // filet de sécurité
  });
}

/* ---------- Année du footer ---------- */
function initFooterYear(){
  const el = $("#footerYear");
  if (el) el.textContent = new Date().getFullYear();
}

/* ---------- Formulaire de contact (mailto, site statique) ---------- */
function initContactForm(){
  const form = $("#contactForm");
  if (!form) return;
  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);
    const body = `De : ${fd.get("nom") || ""} (${fd.get("email") || ""})\n\n${fd.get("message") || ""}`;
    const target = $("#contactEmail");
    const to = target ? target.textContent.trim() : "antoinebrevard8@gmail.com";
    window.location.href =
      `mailto:${to}?subject=${encodeURIComponent("Contact via portfolio")}&body=${encodeURIComponent(body)}`;
  });
}

/* ---------- Copie de l'adresse ----------
   Le mailto: ne fait rien sans client mail configuré : on garde une voie
   qui marche dans tous les cas. */
function initCopyEmail(){
  const btn = $("#copyEmail"), target = $("#contactEmail");
  if (!btn || !target) return;
  const idleLabel = btn.textContent;
  let resetTimer;

  async function copy(text){
    if (navigator.clipboard && window.isSecureContext){
      try { await navigator.clipboard.writeText(text); return true; }
      catch { /* on continue avec le repli ci-dessous */ }
    }
    const tmp = document.createElement("textarea");
    tmp.value = text;
    tmp.setAttribute("readonly", "");
    tmp.style.position = "fixed";
    tmp.style.top = "-1000px";
    document.body.appendChild(tmp);
    tmp.select();
    const ok = document.execCommand("copy");
    document.body.removeChild(tmp);
    return ok;
  }

  btn.addEventListener("click", async () => {
    let ok = false;
    try { ok = await copy(target.textContent.trim()); } catch { ok = false; }
    btn.textContent = ok ? "Adresse copiée" : "Copie impossible";
    btn.classList.toggle("is-copied", ok);
    clearTimeout(resetTimer);
    resetTimer = setTimeout(() => {
      btn.textContent = idleLabel;
      btn.classList.remove("is-copied");
    }, 2000);
  });
}

/* ---------- Feux de départ (accueil uniquement) ----------
   Séquence façon départ : 5 feux qui s'allument un par un, une pause de durée
   volontairement aléatoire — comme un vrai départ, pour qu'on ne puisse pas
   l'anticiper — puis extinction simultanée. Rejoue à chaque nouvelle visite
   (sessionStorage), pas à chaque clic sur "Accueil" pendant la navigation. */
function initStartLights(){
  const overlay = $("#startLights");
  if (!overlay) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (reduceMotion || sessionStorage.getItem("psio_intro_played")){
    overlay.remove();
    return;
  }

  document.body.classList.add("intro-lock");
  const lights = $$(".light", overlay);
  const skipBtn = $("#skipIntro", overlay);

  function finish(){
    try { sessionStorage.setItem("psio_intro_played", "1"); } catch {}
    overlay.classList.add("is-out");
    document.body.classList.remove("intro-lock");
    setTimeout(() => overlay.remove(), 700);
  }
  skipBtn.addEventListener("click", finish);

  let i = 0;
  (function lightNext(){
    if (i < lights.length){
      lights[i].classList.add("is-lit");
      i++;
      setTimeout(lightNext, 480);
      return;
    }
    setTimeout(() => {
      lights.forEach(l => l.classList.remove("is-lit"));
      overlay.classList.add("flash");
      setTimeout(finish, 480);
    }, 650 + Math.random() * 900);
  })();
}

/* ---------- Nappe technique du hero ----------
   Générée en JS plutôt qu'écrite à la main : la lentille doit toujours révéler
   quelque chose, jamais une zone vide. Libellés volontairement génériques —
   ce n'est PAS l'architecture réelle de l'entreprise. */
function initPlot(){
  const grid = $("#plotGrid"), ticks = $("#plotTicks"), cells = $("#plotCells");
  if (!grid || !ticks || !cells) return;
  const NS = "http://www.w3.org/2000/svg";

  let h = "", v = "";
  for (let y = 60; y < 900; y += 60) h += `M0 ${y}H1440`;
  for (let x = 60; x < 1440; x += 60) v += `M${x} 0V900`;
  [h, v].forEach(d => {
    const p = document.createElementNS(NS, "path");
    p.setAttribute("d", d);
    grid.appendChild(p);
  });

  let t = "";
  for (let x = 120; x < 1440; x += 240)
    for (let y = 90; y < 900; y += 180) t += `M${x - 5} ${y}h10M${x} ${y - 5}v10`;
  const tp = document.createElementNS(NS, "path");
  tp.setAttribute("d", t);
  ticks.appendChild(tp);

  const releves = [
    ["Liaison", "OK"], ["Latence", "faible"], ["Débit", "nominal"], ["Sessions", "élevé"],
    ["Uplink", "actif"], ["Redondance", "armée"], ["Charge AP", "haute"], ["Journal", "propre"],
    ["Sauvegarde", "à jour"], ["Onduleur", "secteur"], ["Supervision", "en ligne"], ["Bascule", "prête"]
  ];
  releves.forEach(([lab, val], i) => {
    const cx = 130 + (i % 4) * 360, cy = 120 + Math.floor(i / 4) * 270;
    const mk = (cls, x, y, txt) => {
      const el = document.createElementNS(NS, "text");
      el.setAttribute("class", cls); el.setAttribute("x", x); el.setAttribute("y", y);
      el.textContent = txt;
      cells.appendChild(el);
    };
    mk("plot-micro", cx, cy, lab.toUpperCase());
    mk("plot-micro-val", cx, cy + 17, val.toUpperCase());
    const r = document.createElementNS(NS, "path");
    r.setAttribute("class", "plot-grid");
    r.setAttribute("d", `M${cx} ${cy + 26}h58`);
    cells.appendChild(r);
  });
}

/* ---------- Révélation au curseur (hero) ----------
   Deux mécaniques distinctes, les deux comptent : la turbulence SVG rend le
   BORD liquide, le suivi amorti rend le DÉPLACEMENT fluide. */
function initReveal3D(){
  const stage = $("#stage"), systems = $("#layerSystems"),
        probe = $("#probe"), maskEl = $("#liquidMask"), blob = $("#maskBlob");
  if (!stage || !systems || !maskEl || !blob) return;

  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const isTouch = window.matchMedia("(pointer: coarse)").matches;

  // Pas de pointeur persistant sur tactile, et pas d'animation si l'utilisateur
  // la refuse : on montre la couche en fondu plutôt qu'un hero cassé.
  if (isTouch || reduceMotion){
    systems.style.webkitMask = "none";
    systems.style.mask = "none";
    systems.style.opacity = ".4";
    stage.style.cursor = "default";
    if (probe) probe.remove();
    const inv = $("#invite");
    if (inv) inv.textContent = "Sous le spectacle, les systèmes";
    return;
  }

  function syncMaskSize(){
    const r = stage.getBoundingClientRect();
    maskEl.setAttribute("width", r.width);
    maskEl.setAttribute("height", r.height);
  }
  syncMaskSize();
  window.addEventListener("resize", syncMaskSize);

  const target = { x: .66, y: .27 }, current = { x: .66, y: .27 };

  stage.addEventListener("mousemove", e => {
    const r = stage.getBoundingClientRect();
    target.x = (e.clientX - r.left) / r.width;
    target.y = (e.clientY - r.top) / r.height;
    if (probe){
      probe.style.left = (e.clientX - r.left) + "px";
      probe.style.top  = (e.clientY - r.top) + "px";
    }
  });
  stage.addEventListener("mouseenter", () => probe && probe.classList.add("is-on"));
  stage.addEventListener("mouseleave", () => probe && probe.classList.remove("is-on"));

  const lerp = (a, b, t) => a + (b - a) * t;
  (function tick(){
    const r = stage.getBoundingClientRect();
    current.x = lerp(current.x, target.x, .12);
    current.y = lerp(current.y, target.y, .12);
    blob.setAttribute("cx", current.x * r.width);
    blob.setAttribute("cy", current.y * r.height);
    requestAnimationFrame(tick);
  })();
}

/* ---------- Comparateur avant / apres ----------
   Un input range invisible superpose au bloc : on herite gratuitement du
   clavier, du tactile et de l'accessibilite, plutot que de recoder un
   glisser-deposer a la main. */
function initBeforeAfter(){
  $$("[data-ba]").forEach(bloc => {
    const input = $(".ba-range", bloc);
    const stage = $(".ba-stage", bloc);
    if (!input || !stage) return;
    const apply = () => stage.style.setProperty("--ba", input.value + "%");
    input.addEventListener("input", apply);
    apply();
  });
}

/* ---------- Sortie de virage ----------
   Pendant une transition entre pages, les deux pages sont des captures : rien
   d'anime sur le DOM n'y apparait. On joue donc les trainees APRES, quand la
   nouvelle page reprend la main — ce qui tombe juste, c'est le moment ou l'on
   deboule sur la ligne droite.
   pagereveal n'existe que la ou les transitions multi-pages existent : ailleurs
   il ne se passe rien, et la navigation reste normale. */
function lancerSortieDeVirage(){
  if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

  const couche = document.createElement("div");
  couche.className = "vitesse";
  couche.setAttribute("aria-hidden", "true");

  const N = 14;
  for (let i = 0; i < N; i++){
    const t = document.createElement("span");
    t.className = "vitesse-trait" + (i % 3 === 0 ? " pale" : "");
    t.style.top = (Math.random() * 100).toFixed(1) + "%";
    t.style.width = (26 + Math.random() * 38).toFixed(0) + "vw";
    t.style.animation = `filer ${(340 + Math.random() * 260).toFixed(0)}ms cubic-bezier(.3,0,.2,1) ${(Math.random() * 180).toFixed(0)}ms both`;
    couche.appendChild(t);
  }

  const vibreur = document.createElement("div");
  vibreur.className = "vitesse-vibreur";
  vibreur.style.animation = "vibreur-passe 520ms cubic-bezier(.35,0,.2,1) 40ms both";
  couche.appendChild(vibreur);

  document.body.appendChild(couche);
  // on retire la couche des qu'elle a fini : elle ne doit jamais rester
  // au-dessus de la page une fois l'effet joue
  setTimeout(() => couche.remove(), 1100);
}

function initTransitions(){
  if (!("onpagereveal" in window)) return;
  window.addEventListener("pagereveal", e => {
    if (!e.viewTransition) return;              // arrivee sans transition
    e.viewTransition.finished.then(lancerSortieDeVirage).catch(() => {});
  });
}

/* ---------- Contenu éditable via le CMS (content/*.json) ----------
   Chaque page garde son texte d'origine dans le HTML : c'est le secours si le
   fetch échoue ou si JS est désactivé. Ces fonctions le remplacent par la
   dernière version publiée depuis l'admin. */
function renderHome(d){
  setHTML("heroEyebrow", d.eyebrow);
  if (d.heroTitleLine1) setText("heroLine1", d.heroTitleLine1);
  if (d.heroTitleLine2) setText("heroLine2", d.heroTitleLine2);
  setHTML("heroTagline", d.heroTagline);
  const heroImg = (d.photoMode === "ia" && d.heroImageIa) ? d.heroImageIa : d.heroImageReelle;
  setSrc("heroImage", heroImg);
  const sys = $("#layerSystems img");
  if (sys && heroImg) sys.src = heroImg;   // les deux couches doivent rester alignées
  const cv = document.getElementById("cvLink");
  if (cv && d.cvFile) cv.href = d.cvFile;
}

function renderProfil(d){
  setHTML("aboutIntro", d.aboutIntro);
  setHTML("aboutBody", d.aboutBody);
  setSrc("aboutPhoto", d.photo);
  setHTML("aboutPhotoTag", d.photoTag);
  setText("expTitle", d.expTitle);
  setText("expPlace", d.expPlace);
  setText("expPeriod", d.expPeriod);
  setHTML("expContext", d.expContext);
  const list = document.getElementById("expMissions");
  if (list && Array.isArray(d.expMissions)){
    list.innerHTML = d.expMissions.map(m => `<li>${escapeHtml(m)}</li>`).join("");
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
    accueil: { file: "content/home.json",    render: renderHome },
    profil:  { file: "content/profil.json",  render: renderProfil },
    contact: { file: "content/contact.json", render: renderContact }
  };
  const entry = map[page];
  if (!entry) return Promise.resolve();

  const fetchJson = f => fetch(f, { cache: "no-store" })
    .then(r => (r.ok ? r.json() : null)).catch(() => null);

  return Promise.all([fetchJson(entry.file), fetchJson("content/settings.json")])
    .then(([data, settings]) => {
      if (!data) return;
      if (settings && settings.photoMode) data.photoMode = settings.photoMode;
      entry.render(data);
    });
}

/* ---------- Démarrage ---------- */
// pagereveal se declenche avant le premier rendu : on s'abonne tout de
// suite, pas au DOMContentLoaded qui arrive trop tard.
initTransitions();

document.addEventListener("DOMContentLoaded", () => {
  tickClock();
  setInterval(tickClock, 1000);
  initNavToggle();
  initNavActive();
  initFooterYear();
  initContactForm();
  initCopyEmail();
  initStartLights();
  initPlot();
  initReveal3D();
  initBeforeAfter();
  // Le contenu du CMS est injecté avant d'attacher les observateurs de scroll,
  // sinon les blocs reconstruits démarrent sans animation.
  initContent().finally(initReveal);
});
