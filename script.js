/* ============================================
   Portfolio BTS SIO SISR — script.js
   ============================================
   Pour publier ton site sur GitHub Pages avec TES infos visibles par
   TOUT LE MONDE (pas seulement dans ton navigateur), modifie directement
   les valeurs de l'objet SITE_DATA ci-dessous, puis commit/push.

   Le bouton "Éditer" et le formulaire "Ajouter une veille" écrivent dans
   le localStorage du navigateur : pratique pour prévisualiser en local,
   mais ça ne change rien pour tes visiteurs sur GitHub Pages tant que tu
   n'as pas reporté les infos ici. Les compétences et les projets (arbres
   de décision) ne sont éditables que directement dans ce fichier.
*/

const SITE_DATA = {
  fullName: "Antoine Brévard",

  tagline: "Alternant Technicien Systèmes & Réseaux à l'Automobile Club de l'Ouest — j'apprends en résolvant de vrais problèmes, pas seulement en cours.",

  about: "Actuellement alternant Technicien Systèmes & Réseaux à l'Automobile Club de l'Ouest, en BTS SIO option SISR. J'y interviens sur le support utilisateurs, des projets d'infrastructure et l'administration d'un environnement Windows hybride, aux côtés de serveurs Linux et de l'administration réseau. [Complète cette section via le bouton « Éditer » : ce qui t'a amené vers l'informatique, ce que tu cherches ensuite.]",

  photo: "images/antoine-brevard.jpg",
  email: "antoinebrevard8@gmail.com",
  ville: "Le Mans, France",
  linkedin: "https://www.linkedin.com/in/antoine-br%C3%A9vard-20b483303/",
  github: "https://github.com/AntoineBREVARD",
  cv: "images/cv.pdf",

  formation: [
    { periode: "2025 — 2027", titre: "BTS SIO option SISR — Fab Academy", lieu: "Le Mans" }
  ],

  certifications: [
    { nom: "MOOC de l'ANSSI", detail: "Sensibilisation à la cybersécurité" }
  ],

  experience: {
    poste: "Technicien Systèmes et Réseaux (alternance)",
    lieu: "Automobile Club de l'Ouest (ACO)",
    periode: "2025 — 2027",
    missions: ["Support", "Projets", "Administration Windows (infra hybride)", "Linux", "Réseaux"]
  },

  // Compétences reliées à une situation concrète plutôt qu'un mur de tags.
  competences: [
    {
      skill: "Support technique",
      contexte: "Diagnostic et accompagnement des utilisateurs au quotidien, à l'ACO."
    },
    {
      skill: "Gestion de projets IT",
      contexte: "Participation à des projets d'infrastructure au sein du service informatique de l'ACO."
    },
    {
      skill: "Administration Windows (infrastructure hybride)",
      contexte: "Gestion d'un environnement Windows hybride (on-premise / cloud) en production."
    },
    {
      skill: "Administration Linux",
      contexte: "Maintien et configuration de serveurs Linux."
    },
    {
      skill: "Réseaux",
      contexte: "Configuration et maintien d'infrastructures réseau."
    }
  ],

  // Projets présentés comme un raisonnement : situation, options envisagées,
  // choix retenu et pourquoi, résultat, ce que j'en retiens.
  // Les 4 projets ci-dessous sont réels (menés à l'ACO) mais encore à détailler :
  // ajoute options / resultat / apprentissage pour chacun (retire "incomplete: true"
  // une fois rempli) et le rendu passera automatiquement en format "arbre de décision".
  projets: [
    {
      incomplete: true,
      tag: "Automatisation",
      titre: "Automatisation HPIA",
      situation: "Projet mené à l'ACO — déploiement automatisé de HP Image Assistant (mises à jour drivers/BIOS) sur le parc de postes HP. [Précise ici l'échelle du parc et la contrainte de départ.]"
    },
    {
      incomplete: true,
      tag: "Annuaire & synchronisation",
      titre: "Synchronisation des communes des contacts ACO",
      situation: "Projet mené à l'ACO. [Décris ici le contexte précis : quelle source de données, quel problème de cohérence à résoudre.]"
    },
    {
      incomplete: true,
      tag: "Supervision",
      titre: "Alerte boîtes mails pleines",
      situation: "Projet mené à l'ACO. [Décris ici le contexte précis : quel volume de boîtes à surveiller, pourquoi une alerte manuelle ne suffisait plus.]"
    },
    {
      incomplete: true,
      tag: "Support & productivité",
      titre: "Package automatique de fonds verts Teams",
      situation: "Projet mené à l'ACO. [Décris ici le contexte précis : le besoin des utilisateurs, pourquoi automatiser le déploiement.]"
    }
  ],

  // Veilles publiées pour tous les visiteurs : place le PDF dans images/veilles/
  // puis ajoute une entrée ici avec le chemin du fichier.
  veilles: [
    // Exemple :
    // {
    //   titre: "La sécurité des réseaux Wi-Fi d'entreprise",
    //   date: "2026-03-10",
    //   theme: "Sécurité",
    //   question: "Pourquoi le Wi-Fi d'entreprise reste-t-il souvent mal sécurisé ?",
    //   resume: "Panorama des risques et bonnes pratiques pour sécuriser un réseau Wi-Fi professionnel.",
    //   fichier: "images/veilles/wifi-securite.pdf"
    // }
  ]
};

/* ---------- Utilitaires ---------- */
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

function loadLocal(key, fallback){
  try{
    const raw = localStorage.getItem(key);
    return raw ? JSON.parse(raw) : fallback;
  }catch{
    return fallback;
  }
}
function saveLocal(key, value){
  localStorage.setItem(key, JSON.stringify(value));
}

function getProfile(){
  const overrides = loadLocal("psio_profile", {});
  return { ...SITE_DATA, ...overrides };
}
function getVeilles(){
  const local = loadLocal("psio_veilles", []);
  return [...SITE_DATA.veilles, ...local];
}
function escapeHtml(str = ""){
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}
function normalize(str = ""){
  return String(str)
    .toLowerCase()
    .normalize("NFD")
    .replace(/[̀-ͯ]/g, "");
}

function animateCount(el){
  const target = parseInt(el.textContent, 10);
  if (!Number.isFinite(target)) return;
  const duration = 700;
  const start = performance.now();
  function tick(now){
    const progress = Math.min(1, (now - start) / duration);
    el.textContent = Math.round(target * progress);
    if (progress < 1) requestAnimationFrame(tick);
    else el.textContent = target;
  }
  requestAnimationFrame(tick);
}

/* ---------- Révélation au scroll ---------- */
let revealObserver;
function initRevealObserver(){
  revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting){
        entry.target.classList.add("is-visible");
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
}
function observeReveal(selector){
  $$(selector).forEach(el => {
    revealObserver.observe(el);
    // Filet de sécurité si l'observateur ne se déclenche jamais.
    setTimeout(() => el.classList.add("is-visible"), 2500);
  });
}

/* ---------- Navigation ---------- */
function initTabs(){
  const links = $$(".navlink");
  const panels = $$(".panel");

  function activate(tabName){
    links.forEach(b => b.classList.toggle("is-active", b.dataset.tab === tabName));
    panels.forEach(p => p.classList.toggle("is-active", p.dataset.panel === tabName));
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
  window.__psioActivateTab = activate;

  links.forEach(btn => btn.addEventListener("click", () => activate(btn.dataset.tab)));
  $$("[data-goto]").forEach(el => el.addEventListener("click", () => activate(el.dataset.goto)));
}

/* ---------- Rendu du profil ---------- */
function renderProfile(){
  const p = getProfile();

  $("#hero-name").textContent = p.fullName;
  $("#hero-tagline").textContent = p.tagline;
  $("#heroPhoto").src = p.photo || "images/avatar-placeholder.svg";
  $("#cvLink").href = p.cv || "#";
  $("#cvLinkTier").href = p.cv || "#";
  $("#aboutText").textContent = p.about;
  $("#footerName2").textContent = p.fullName;

  $("#miniEmail").textContent = p.email;
  $("#miniVille").textContent = p.ville;
  $("#miniLinkedin").href = p.linkedin || "#";
  $("#miniGithub").href = p.github || "#";

  $("#contactEmail").href = "mailto:" + p.email;
  $("#contactEmail").textContent = p.email;
  $("#contactLinkedin").href = p.linkedin || "#";
  $("#contactGithub").href = p.github || "#";

  $("#footerName").textContent = p.fullName;

  const formationList = $("#formationList");
  formationList.innerHTML = "";
  (p.formation || []).forEach(f => {
    const li = document.createElement("li");
    li.className = "timeline-item";
    li.innerHTML = `
      <span class="timeline-date">${escapeHtml(f.periode)}</span>
      <span class="timeline-content"><strong>${escapeHtml(f.titre)}</strong><br>${escapeHtml(f.lieu)}</span>
    `;
    formationList.appendChild(li);
  });

  const certifList = $("#certifList");
  const certifTitle = $("#certifTitle");
  certifList.innerHTML = "";
  const certifs = p.certifications || [];
  certifTitle.hidden = certifs.length === 0;
  certifs.forEach(c => {
    const li = document.createElement("li");
    li.className = "timeline-item";
    li.innerHTML = `
      <span class="timeline-date">✓</span>
      <span class="timeline-content"><strong>${escapeHtml(c.nom)}</strong>${c.detail ? "<br>" + escapeHtml(c.detail) : ""}</span>
    `;
    certifList.appendChild(li);
  });

  const exp = p.experience;
  if (exp){
    $("#experienceCard").innerHTML = `
      <div class="experience-head">
        <h4>${escapeHtml(exp.poste)}</h4>
        <span class="experience-period">${escapeHtml(exp.periode)}</span>
      </div>
      <p class="experience-place">${escapeHtml(exp.lieu)}</p>
      <ul class="experience-missions">
        ${(exp.missions || []).map(m => `<li>${escapeHtml(m)}</li>`).join("")}
      </ul>
    `;
  }
}

/* ---------- Compétences ---------- */
function renderSkills(){
  const p = getProfile();
  const grid = $("#skillsList");
  grid.innerHTML = "";

  (p.competences || []).forEach(s => {
    const row = document.createElement("div");
    row.className = "skill-row reveal";
    row.innerHTML = `
      <span class="skill-name">${escapeHtml(s.skill)}</span>
      <span class="skill-context">${escapeHtml(s.contexte)}</span>
    `;
    grid.appendChild(row);
  });
  observeReveal(".skill-row");
}

/* ---------- Projets (arbre de décision) ---------- */
function getProjects(){
  const overrides = loadLocal("psio_projets_overrides", {});
  return SITE_DATA.projets.map((proj, i) => overrides[i] ? { ...proj, ...overrides[i] } : proj);
}

function renderProjects(){
  const projets = getProjects();
  const list = $("#projectsList");
  list.innerHTML = "";

  projets.forEach((proj, idx) => {
    const card = document.createElement("div");
    card.className = "project-card reveal";

    const optionsHtml = (proj.options || []).map(o => `
      <div class="decision-option ${o.chosen ? "is-chosen" : "is-rejected"}">
        <span class="decision-option-label">${escapeHtml(o.label)}</span>
        ${escapeHtml(o.raison || "")}
      </div>
    `).join("");

    const incomplete = proj.exemple || proj.incomplete;

    card.innerHTML = `
      <button class="project-edit-btn" data-idx="${idx}" title="Détailler ce projet">✎ Éditer</button>
      ${incomplete ? `<span class="project-example-ribbon">${proj.exemple ? "Modèle" : "À détailler"}</span>` : ""}
      <span class="project-tag">${escapeHtml(proj.tag || "")}</span>
      <h3>${escapeHtml(proj.titre)}</h3>
      <p class="project-situation">${escapeHtml(proj.situation || "")}</p>
      ${optionsHtml ? `<p class="decision-label">Options envisagées</p><div class="decision-options">${optionsHtml}</div>` : ""}
      ${proj.resultat ? `<div class="project-result"><strong>Résultat</strong>${escapeHtml(proj.resultat)}</div>` : ""}
      ${proj.apprentissage ? `<p class="project-learning">— ${escapeHtml(proj.apprentissage)}</p>` : ""}
    `;
    list.appendChild(card);
  });
  observeReveal(".project-card");

  $$(".project-edit-btn", list).forEach(btn => {
    btn.addEventListener("click", () => openProjetModal(Number(btn.dataset.idx)));
  });
}

/* ---------- Formulaire "Éditer mes infos" ---------- */
function initEditModal(){
  const modal = $("#editModal");
  const openBtn = $("#editToggle");
  const closeBtn = $("#closeEditModal");
  const form = $("#editForm");
  const resetBtn = $("#resetForm");

  function open(){
    const p = getProfile();
    form.fullName.value = (p.fullName && p.fullName !== SITE_DATA.fullName) ? p.fullName : p.fullName || "";
    form.tagline.value = p.tagline || "";
    form.about.value = p.about || "";
    form.photo.value = p.photo || "";
    form.email.value = p.email || "";
    form.ville.value = p.ville || "";
    form.linkedin.value = p.linkedin || "";
    form.github.value = p.github || "";
    form.cv.value = p.cv || "";
    form.formation.value = (p.formation || []).map(f => `${f.periode} | ${f.titre} | ${f.lieu}`).join("\n");
    form.competences.value = (p.competences || []).map(s => `${s.skill} | ${s.contexte}`).join("\n");
    modal.hidden = false;
  }
  function close(){ modal.hidden = true; }

  openBtn.addEventListener("click", open);
  closeBtn.addEventListener("click", close);
  modal.addEventListener("click", e => { if (e.target === modal) close(); });

  resetBtn.addEventListener("click", () => {
    if (confirm("Effacer tes modifications locales et revenir aux valeurs par défaut ?")){
      localStorage.removeItem("psio_profile");
      renderProfile();
      renderSkills();
      close();
    }
  });

  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);

    const formation = String(fd.get("formation") || "")
      .split("\n").map(l => l.trim()).filter(Boolean)
      .map(line => {
        const [periode, titre, lieu] = line.split("|").map(s => (s || "").trim());
        return { periode: periode || "", titre: titre || "", lieu: lieu || "" };
      });

    const competences = String(fd.get("competences") || "")
      .split("\n").map(l => l.trim()).filter(Boolean)
      .map(line => {
        const [skill, contexte] = line.split("|").map(s => (s || "").trim());
        return { skill: skill || "", contexte: contexte || "" };
      });

    const overrides = {};
    ["fullName", "tagline", "about", "photo", "email", "ville", "linkedin", "github", "cv"].forEach(key => {
      const val = String(fd.get(key) || "").trim();
      if (val) overrides[key] = val;
    });
    if (formation.length) overrides.formation = formation;
    if (competences.length) overrides.competences = competences;

    saveLocal("psio_profile", overrides);
    renderProfile();
    renderSkills();
    close();
  });
}

/* ---------- Mes veilles ---------- */
const THEMES = ["Tous", "Sécurité", "Réseaux", "Systèmes", "Cloud", "Autre"];
let activeTheme = "Tous";

function initVeilleFilters(){
  const container = $("#veilleFilters");
  container.innerHTML = "";
  THEMES.forEach(theme => {
    const chip = document.createElement("button");
    chip.className = "filter-chip" + (theme === activeTheme ? " is-active" : "");
    chip.textContent = theme;
    chip.addEventListener("click", () => {
      activeTheme = theme;
      renderVeilles();
      $$(".filter-chip", container).forEach(c => c.classList.toggle("is-active", c.textContent === theme));
    });
    container.appendChild(chip);
  });
}

function renderVeilles(){
  const list = $("#veillesList");
  const empty = $("#veillesEmpty");
  let veilles = getVeilles();

  if (activeTheme !== "Tous") veilles = veilles.filter(v => v.theme === activeTheme);
  veilles = [...veilles].sort((a, b) => (b.date || "").localeCompare(a.date || ""));

  list.innerHTML = "";
  empty.hidden = veilles.length !== 0;

  veilles.forEach((v, idx) => {
    const card = document.createElement("div");
    card.className = "veille-card reveal";
    const dateFmt = v.date ? new Date(v.date).toLocaleDateString("fr-FR", { year: "numeric", month: "short", day: "numeric" }) : "";
    const isLocal = v.__local === true;

    card.innerHTML = `
      <div class="veille-icon">PDF</div>
      <div class="veille-main">
        <h3>${escapeHtml(v.titre)}</h3>
        <div class="veille-meta">
          <span>${escapeHtml(dateFmt)}</span>
          <span class="veille-theme">${escapeHtml(v.theme || "Autre")}</span>
        </div>
        ${v.question ? `<p class="veille-question">— ${escapeHtml(v.question)}</p>` : ""}
        ${v.resume ? `<p class="veille-summary">${escapeHtml(v.resume)}</p>` : ""}
      </div>
      <div class="veille-actions">
        ${v.fichier ? `<a class="btn btn-ghost" href="${v.fichier}" target="_blank" rel="noopener">Voir le PDF</a>` : `<span class="form-hint">Pas de fichier</span>`}
        ${isLocal ? `<button class="veille-delete" data-idx="${idx}" title="Supprimer cette veille locale">✕</button>` : ""}
      </div>
    `;
    list.appendChild(card);
  });

  const statVeillesEl = $("#statVeilles");
  statVeillesEl.textContent = getVeilles().length;
  animateCount(statVeillesEl);
  observeReveal(".veille-card");

  $$(".veille-delete", list).forEach(btn => {
    btn.addEventListener("click", () => {
      const localVeilles = loadLocal("psio_veilles", []);
      const target = veilles[Number(btn.dataset.idx)];
      const filtered = localVeilles.filter(v => !(v.titre === target.titre && v.date === target.date));
      saveLocal("psio_veilles", filtered);
      renderVeilles();
    });
  });
}

/* ---------- Modale "Détailler un projet" ---------- */
function initProjetModal(){
  const modal = $("#projetModal");
  const closeBtn = $("#closeProjetModal");
  const cancelBtn = $("#cancelProjet");
  const form = $("#projetForm");

  function close(){ modal.hidden = true; }

  closeBtn.addEventListener("click", close);
  cancelBtn.addEventListener("click", close);
  modal.addEventListener("click", e => { if (e.target === modal) close(); });

  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);
    const idx = Number(fd.get("idx"));

    const options = String(fd.get("options") || "")
      .split("\n").map(l => l.trim()).filter(Boolean)
      .map(line => {
        const [label, raison, statut] = line.split("|").map(s => (s || "").trim());
        return { label: label || "", raison: raison || "", chosen: /retenue/i.test(statut || "") };
      });

    const overrides = loadLocal("psio_projets_overrides", {});
    overrides[idx] = {
      titre: String(fd.get("titre") || "").trim(),
      tag: String(fd.get("tag") || "").trim(),
      situation: String(fd.get("situation") || "").trim(),
      resultat: String(fd.get("resultat") || "").trim(),
      apprentissage: String(fd.get("apprentissage") || "").trim(),
      incomplete: false
    };
    if (options.length) overrides[idx].options = options;
    saveLocal("psio_projets_overrides", overrides);

    renderProjects();
    close();
  });

  window.__psioOpenProjetModal = idx => {
    const proj = getProjects()[idx];
    if (!proj) return;
    form.idx.value = idx;
    form.titre.value = proj.titre || "";
    form.tag.value = proj.tag || "";
    form.situation.value = (proj.situation || "").replace(/^Projet mené à l'ACO\s*[—.]?\s*/, "").replace(/\[.*?\]/g, "").trim();
    form.options.value = (proj.options || []).map(o => `${o.label} | ${o.raison} | ${o.chosen ? "retenue" : "écartée"}`).join("\n");
    form.resultat.value = proj.resultat || "";
    form.apprentissage.value = proj.apprentissage || "";
    modal.hidden = false;
  };
}
function openProjetModal(idx){ window.__psioOpenProjetModal(idx); }

function initVeilleModal(){
  const modal = $("#veilleModal");
  const openBtn = $("#addVeilleBtn");
  const closeBtn = $("#closeVeilleModal");
  const cancelBtn = $("#cancelVeille");
  const form = $("#veilleForm");

  function open(){ form.reset(); modal.hidden = false; }
  function close(){ modal.hidden = true; }

  openBtn.addEventListener("click", open);
  closeBtn.addEventListener("click", close);
  cancelBtn.addEventListener("click", close);
  modal.addEventListener("click", e => { if (e.target === modal) close(); });

  form.addEventListener("submit", async e => {
    e.preventDefault();
    const fd = new FormData(form);
    const titre = String(fd.get("titre") || "").trim();
    const date = String(fd.get("date") || "").trim();
    const theme = String(fd.get("theme") || "Autre");
    const question = String(fd.get("question") || "").trim();
    const resume = String(fd.get("resume") || "").trim();
    const file = fd.get("fichier");

    let fichier = "";
    if (file && file.size > 0){
      if (file.size > 4 * 1024 * 1024){
        alert("Ce PDF est volumineux : pour un vrai site publié, place-le plutôt dans images/veilles/ et référence-le dans SITE_DATA (script.js). L'aperçu local ne sera pas enregistré.");
      } else {
        fichier = await fileToDataUrl(file);
      }
    }

    const localVeilles = loadLocal("psio_veilles", []);
    localVeilles.push({ titre, date, theme, question, resume, fichier, __local: true });
    saveLocal("psio_veilles", localVeilles);

    renderVeilles();
    close();
  });
}

function fileToDataUrl(file){
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

/* ---------- Formulaire de contact (mailto) ---------- */
function initContactForm(){
  const form = $("#contactForm");
  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);
    const nom = fd.get("nom");
    const email = fd.get("email");
    const message = fd.get("message");
    const profile = getProfile();
    const subject = encodeURIComponent(`Contact portfolio — ${nom}`);
    const body = encodeURIComponent(`${message}\n\n— ${nom} (${email})`);
    window.location.href = `mailto:${profile.email}?subject=${subject}&body=${body}`;
  });
}

/* ---------- Recherche rapide locale (Ctrl+K) ---------- */
const FAQ = [
  { q: "Est-il disponible pour une alternance ou un stage ?", a: "Actuellement en alternance à l'ACO jusqu'en 2027 — pour toute opportunité, direction l'onglet Contact." },
  { q: "Où fait-il son alternance ?", a: "À l'Automobile Club de l'Ouest (ACO), l'organisation derrière les 24 Heures du Mans." },
  { q: "Quelles sont ses compétences principales ?", a: "Support technique, gestion de projets IT, administration Windows en environnement hybride, Linux et réseaux." },
  { q: "Comment le contacter ?", a: "Par e-mail ou LinkedIn — voir l'onglet Contact." },
  { q: "A-t-il des veilles technologiques disponibles ?", a: "Oui, consultables dans l'onglet Mes veilles." }
];

function buildSearchIndex(){
  const p = getProfile();
  const items = [];

  $$(".navlink").forEach(btn => {
    items.push({ type: "Section", label: btn.textContent, sub: "", action: () => window.__psioActivateTab(btn.dataset.tab) });
  });
  items.push({ type: "Section", label: "Contact", sub: "", action: () => window.__psioActivateTab("contact") });

  (p.competences || []).forEach(s => {
    items.push({ type: "Compétence", label: s.skill, sub: s.contexte, action: () => window.__psioActivateTab("competences") });
  });

  (p.projets || []).forEach(proj => {
    items.push({ type: "Projet", label: proj.titre, sub: proj.situation, action: () => window.__psioActivateTab("projets") });
  });

  getVeilles().forEach(v => {
    items.push({ type: "Veille", label: v.titre, sub: v.theme || "", action: () => window.__psioActivateTab("veilles") });
  });

  FAQ.forEach(f => {
    items.push({ type: "Question", label: f.q, sub: f.a, action: null, answer: f.a });
  });

  return items;
}

function initPrivacyModal(){
  const modal = $("#privacyModal");
  const openBtn = $("#privacyToggle");
  const closeBtn = $("#closePrivacyModal");
  $("#privacyEmail").textContent = getProfile().email;
  openBtn.addEventListener("click", () => { modal.hidden = false; });
  closeBtn.addEventListener("click", () => { modal.hidden = true; });
  modal.addEventListener("click", e => { if (e.target === modal) modal.hidden = true; });
}

function initSearch(){
  const overlay = $("#searchModal");
  const input = $("#searchInput");
  const resultsEl = $("#searchResults");
  const toggleBtn = $("#searchToggle");
  let items = [];
  let activeIndex = 0;

  function open(){
    items = buildSearchIndex();
    input.value = "";
    renderResults("");
    overlay.hidden = false;
    setTimeout(() => input.focus(), 0);
  }
  function close(){ overlay.hidden = true; }

  function renderResults(query){
    const q = normalize(query.trim());
    let filtered = items;
    if (q){
      filtered = items.filter(it => normalize(it.label).includes(q) || normalize(it.sub).includes(q));
    }
    filtered = filtered.slice(0, 8);
    activeIndex = 0;

    if (!filtered.length){
      resultsEl.innerHTML = `<p class="search-empty">Aucun résultat pour « ${escapeHtml(query)} ».</p>`;
      return;
    }

    resultsEl.innerHTML = filtered.map((it, i) => `
      <div class="search-result${i === 0 ? " is-active" : ""}" data-idx="${i}">
        <span class="search-result-type">${escapeHtml(it.type)}</span>
        <span class="search-result-label">${escapeHtml(it.label)}</span>
        ${it.sub ? `<span class="search-result-sub">${escapeHtml(it.sub)}</span>` : ""}
      </div>
    `).join("");

    $$(".search-result", resultsEl).forEach((el, i) => {
      el.addEventListener("click", () => selectResult(filtered[i]));
      el.addEventListener("mousemove", () => setActive(i));
    });

    function setActive(i){
      activeIndex = i;
      $$(".search-result", resultsEl).forEach((el, idx) => el.classList.toggle("is-active", idx === i));
    }

    resultsEl.__filtered = filtered;
    resultsEl.__setActive = setActive;
  }

  function selectResult(it){
    if (it.answer){
      resultsEl.innerHTML = `<div class="search-result is-active"><span class="search-result-type">${escapeHtml(it.type)}</span><span class="search-result-label">${escapeHtml(it.label)}</span><span class="search-result-sub">${escapeHtml(it.answer)}</span></div>`;
      return;
    }
    if (it.action) it.action();
    close();
  }

  toggleBtn.addEventListener("click", open);
  overlay.addEventListener("click", e => { if (e.target === overlay) close(); });

  input.addEventListener("input", () => renderResults(input.value));

  input.addEventListener("keydown", e => {
    const filtered = resultsEl.__filtered || [];
    if (e.key === "ArrowDown"){
      e.preventDefault();
      if (!filtered.length) return;
      activeIndex = Math.min(activeIndex + 1, filtered.length - 1);
      resultsEl.__setActive(activeIndex);
    } else if (e.key === "ArrowUp"){
      e.preventDefault();
      if (!filtered.length) return;
      activeIndex = Math.max(activeIndex - 1, 0);
      resultsEl.__setActive(activeIndex);
    } else if (e.key === "Enter"){
      e.preventDefault();
      if (filtered[activeIndex]) selectResult(filtered[activeIndex]);
    } else if (e.key === "Escape"){
      close();
    }
  });

  document.addEventListener("keydown", e => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k"){
      e.preventDefault();
      overlay.hidden ? open() : close();
    }
    if (e.key === "Escape" && !overlay.hidden) close();
  });
}

/* ---------- Init ---------- */
document.addEventListener("DOMContentLoaded", () => {
  $("#footerYear").textContent = new Date().getFullYear();
  initTabs();
  initRevealObserver();
  renderProfile();
  renderSkills();
  renderProjects();
  initEditModal();
  initProjetModal();
  initVeilleFilters();
  renderVeilles();
  initVeilleModal();
  initContactForm();
  initSearch();
  initPrivacyModal();
  observeReveal(".reveal");
});
