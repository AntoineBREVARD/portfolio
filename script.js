/* ============================================
   Portfolio BTS SIO SISR — script.js
   ============================================
   Pour publier ton site sur GitHub Pages avec TES infos visibles par
   TOUT LE MONDE (pas seulement dans ton navigateur), modifie directement
   les valeurs de l'objet SITE_DATA ci-dessous, puis commit/push.

   Le bouton "Éditer" et le formulaire "Ajouter une veille" écrivent dans
   le localStorage du navigateur : pratique pour prévisualiser en local,
   mais ça ne change rien pour tes visiteurs sur GitHub Pages tant que tu
   n'as pas reporté les infos ici.
*/

const SITE_DATA = {
  fullName: "Prénom Nom",
  tagline: "Futur administrateur systèmes & réseaux — j'installe, je sécurise et je documente les infrastructures qui font tourner les organisations.",
  about: "Étudiant en BTS SIO option SISR, je m'intéresse particulièrement à l'administration des systèmes, aux réseaux et à la cybersécurité. Renseigne cette section via le bouton « Éditer » : parcours, stage/alternance, projets qui te tiennent à cœur.",
  photo: "images/avatar-placeholder.svg",
  email: "prenom.nom@example.com",
  ville: "Ville, France",
  linkedin: "",
  github: "",
  cv: "images/cv.pdf",
  formation: [
    { periode: "2024 — 2026", titre: "BTS SIO option SISR", lieu: "Nom de l'établissement" }
  ],
  competences: [
    "Windows Server", "Active Directory", "Linux", "Réseaux (Cisco / Huawei)",
    "Virtualisation", "Sécurité informatique", "Supervision", "Scripting PowerShell / Bash"
  ],
  projets: [
    {
      tag: "TP réseau",
      titre: "Mise en place d'un plan d'adressage IP",
      description: "Conception et déploiement d'une infrastructure réseau segmentée en VLANs pour un site fictif."
    },
    {
      tag: "Systèmes",
      titre: "Déploiement d'un serveur Active Directory",
      description: "Installation, configuration et sécurisation d'un contrôleur de domaine Windows Server."
    },
    {
      tag: "Sécurité",
      titre: "Durcissement d'un serveur Linux",
      description: "Application des bonnes pratiques de sécurisation (pare-feu, SSH, mises à jour, supervision)."
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

/* Fusion des infos par défaut (SITE_DATA) + surcharge locale (formulaire Éditer) */
function getProfile(){
  const overrides = loadLocal("psio_profile", {});
  return { ...SITE_DATA, ...overrides };
}

/* Veilles = veilles publiées (SITE_DATA) + veilles ajoutées localement */
function getVeilles(){
  const local = loadLocal("psio_veilles", []);
  return [...SITE_DATA.veilles, ...local];
}

/* ---------- Navigation par onglets ---------- */
function initTabs(){
  const buttons = $$(".tab-btn");
  const panels = $$(".panel");

  function activate(tabName){
    buttons.forEach(b => b.classList.toggle("is-active", b.dataset.tab === tabName));
    panels.forEach(p => p.classList.toggle("is-active", p.dataset.panel === tabName));
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  buttons.forEach(btn => {
    btn.addEventListener("click", () => activate(btn.dataset.tab));
  });

  $$("[data-goto]").forEach(el => {
    el.addEventListener("click", () => activate(el.dataset.goto));
  });
}

/* ---------- Rendu du profil ---------- */
function renderProfile(){
  const p = getProfile();

  $("#hero-name").textContent = p.fullName;
  $("#hero-tagline").textContent = p.tagline;
  $("#heroPhoto").src = p.photo || "images/avatar-placeholder.svg";
  $("#cvLink").href = p.cv || "#";
  $("#aboutText").textContent = p.about;

  $("#miniEmail").textContent = p.email;
  $("#miniVille").textContent = p.ville;
  $("#miniLinkedin").href = p.linkedin || "#";
  $("#miniGithub").href = p.github || "#";

  $("#contactEmail").href = "mailto:" + p.email;
  $("#contactEmail").textContent = p.email;
  $("#contactLinkedin").href = p.linkedin || "#";
  $("#contactGithub").href = p.github || "#";

  $("#footerName").textContent = p.fullName;

  // Formation
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

  // Compétences
  const skillsGrid = $("#skillsGrid");
  skillsGrid.innerHTML = "";
  (p.competences || []).forEach(skill => {
    const card = document.createElement("div");
    card.className = "skill-card";
    card.innerHTML = `<span class="skill-name">${escapeHtml(skill)}</span>`;
    skillsGrid.appendChild(card);
  });

  // Projets
  const projectsGrid = $("#projectsGrid");
  projectsGrid.innerHTML = "";
  (p.projets || []).forEach(proj => {
    const card = document.createElement("div");
    card.className = "project-card";
    card.innerHTML = `
      <span class="project-tag">${escapeHtml(proj.tag)}</span>
      <h3>${escapeHtml(proj.titre)}</h3>
      <p>${escapeHtml(proj.description)}</p>
    `;
    projectsGrid.appendChild(card);
  });
}

function escapeHtml(str = ""){
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
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
    form.fullName.value = p.fullName === SITE_DATA.fullName ? (p.fullName === "Prénom Nom" ? "" : p.fullName) : p.fullName;
    form.tagline.value = p.tagline || "";
    form.about.value = p.about || "";
    form.photo.value = p.photo || "";
    form.email.value = p.email || "";
    form.ville.value = p.ville || "";
    form.linkedin.value = p.linkedin || "";
    form.github.value = p.github || "";
    form.cv.value = p.cv || "";
    form.formation.value = (p.formation || [])
      .map(f => `${f.periode} | ${f.titre} | ${f.lieu}`).join("\n");
    form.competences.value = (p.competences || []).join(", ");
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
      close();
    }
  });

  form.addEventListener("submit", e => {
    e.preventDefault();
    const fd = new FormData(form);

    const formation = String(fd.get("formation") || "")
      .split("\n")
      .map(line => line.trim())
      .filter(Boolean)
      .map(line => {
        const [periode, titre, lieu] = line.split("|").map(s => (s || "").trim());
        return { periode: periode || "", titre: titre || "", lieu: lieu || "" };
      });

    const competences = String(fd.get("competences") || "")
      .split(",")
      .map(s => s.trim())
      .filter(Boolean);

    const overrides = {};
    ["fullName", "tagline", "about", "photo", "email", "ville", "linkedin", "github", "cv"]
      .forEach(key => {
        const val = String(fd.get(key) || "").trim();
        if (val) overrides[key] = val;
      });
    if (formation.length) overrides.formation = formation;
    if (competences.length) overrides.competences = competences;

    saveLocal("psio_profile", overrides);
    renderProfile();
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

  if (activeTheme !== "Tous"){
    veilles = veilles.filter(v => v.theme === activeTheme);
  }

  veilles = [...veilles].sort((a, b) => (b.date || "").localeCompare(a.date || ""));

  list.innerHTML = "";
  empty.hidden = veilles.length !== 0;

  veilles.forEach((v, idx) => {
    const card = document.createElement("div");
    card.className = "veille-card";
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
        ${v.resume ? `<p class="veille-summary">${escapeHtml(v.resume)}</p>` : ""}
      </div>
      <div class="veille-actions">
        ${v.fichier ? `<a class="btn btn-ghost" href="${v.fichier}" target="_blank" rel="noopener">Voir le PDF</a>` : `<span class="form-hint">Pas de fichier</span>`}
        ${isLocal ? `<button class="veille-delete" data-idx="${idx}" title="Supprimer cette veille locale">✕</button>` : ""}
      </div>
    `;
    list.appendChild(card);
  });

  $("#statVeilles").textContent = getVeilles().length;

  $$(".veille-delete", list).forEach(btn => {
    btn.addEventListener("click", () => {
      const localVeilles = loadLocal("psio_veilles", []);
      // Retrouve l'élément local correspondant par titre+date (simple, suffisant ici)
      const target = veilles[Number(btn.dataset.idx)];
      const filtered = localVeilles.filter(v => !(v.titre === target.titre && v.date === target.date));
      saveLocal("psio_veilles", filtered);
      renderVeilles();
    });
  });
}

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
    localVeilles.push({ titre, date, theme, resume, fichier, __local: true });
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

/* ---------- Init ---------- */
document.addEventListener("DOMContentLoaded", () => {
  $("#footerYear").textContent = new Date().getFullYear();
  initTabs();
  renderProfile();
  initEditModal();
  initVeilleFilters();
  renderVeilles();
  initVeilleModal();
  initContactForm();
});
