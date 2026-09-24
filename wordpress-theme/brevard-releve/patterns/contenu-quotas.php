<?php
/**
 * Title: Réalisation — On l'apprenait toujours trop tard
 * Slug: brevard-releve/contenu-quotas
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * Contenu de la fiche « quotas », repris du site statique. Il sert au premier
 * remplissage : une fois la fiche modifiée dans WordPress, c'est la version
 * enregistrée qui fait foi.
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<dl class="cas-meta">
    <div><dt>Service</dt><dd>Exchange Online</dd></div>
    <div><dt>Seuil d'alerte</dt><dd>95 %</dd></div>
    <div><dt>Intervention utilisateur</dt><dd>0</dd></div>
</dl>

<div class="cas-etape">
  <h2>Ce qui n'allait pas</h2>
  <p>Rien ne surveillait le remplissage des boîtes aux lettres. Le service apprenait la saturation d'une boîte au moment où son propriétaire signalait qu'il ne recevait plus rien — donc après le blocage, jamais avant.</p>
  <p>Le coût n'est pas technique, il est humain : des messages perdus de vue, un collaborateur bloqué, et une intervention en urgence sur quelque chose qui se voyait venir.</p>
</div>

<div class="cas-etape">
  <h2>Le seuil a été mesuré, pas devine</h2>
  <p>En observant les cas réels, un constat : une boîte commence à présenter des dysfonctionnements — refus d'envoi, notamment — <strong>à partir de 97 % d'occupation</strong>.</p>
  <p>L'alerte a donc été fixée à <strong>95 %</strong>, pour conserver une marge d'intervention avant que l'utilisateur ne soit gêné. C'est le genre de choix qu'on ne peut pas faire sans avoir regardé le parc.</p>
</div>

<div class="cas-etape">
  <h2>Comment ça tourne</h2>
  <p>Une tâche planifiée se connecte à Exchange Online par authentification applicative avec certificat — aucun mot de passe stocké nulle part. Pour chaque boîte, le script relève la taille réellement utilisée, le quota d'envoi et le pourcentage d'occupation.</p>
  <p>Toute boîte au-delà du seuil est ajoutée à un rapport envoyé au service par le serveur SMTP interne. Aucune action n'est demandée aux utilisateurs, et rien ne s'exécute sur leurs postes.</p>
</div>

<div class="cas-etape">
  <h2>Ce que ça change</h2>
  <div class="ba" data-ba>
    <div class="ba-cadre">
      <div class="ba-avant">
        <span class="ba-tag">Avant</span>
        <h3>Le téléphone sonne.</h3>
        <p>La saturation se découvrait par un appel : « je ne reçois plus mes mails ». À ce stade le blocage est déjà là, et l'intervention se fait dans l'urgence.</p>
      </div>
      <div class="ba-apres">
        <span class="ba-tag">Après</span>
        <h3>Le service est prévenu avant.</h3>
        <p>La surveillance tourne seule et signale les boîtes qui approchent de leur limite. L'intervention devient planifiable, et le nombre d'incidents de saturation baisse.</p>
      </div>
      <div class="ba-ligne" aria-hidden="true"></div>
      <input class="ba-curseur" type="range" min="0" max="100" value="50"
             id="ba-quotas" aria-label="Comparer la situation avant et après">
    </div>
    <p class="ba-aide">Faites glisser pour comparer</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que je referais autrement</h2>
  <div class="recul">
    <h3>L'alerte prévient le service, pas l'utilisateur</h3>
    <p>La solution résout le problème du service informatique : il sait. Elle ne traite pas la cause, qui est en amont — des boîtes qui grossissent sans que leur propriétaire en ait conscience.</p>
    <p>Ce que j'ajouterais : un message à l'utilisateur concerné en même temps qu'au service, et un suivi dans le temps pour distinguer une boîte ponctuellement pleine d'une boîte structurellement trop petite.</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que cette mission démontre</h2>
  <p>Chaque étiquette renvoie à la grille de compétences.</p>
  <div class="preuves">
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Gérer des indicateurs et des fichiers d'activité</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Évaluer et améliorer la qualité d'un service</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Automatiser des tâches d'administration</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Déployer un service</a>
  </div>
</div>

<div class="cas-etape">
  <div class="renvoi">
    <p><strong>Le détail est dans le dossier professionnel :</strong> l'architecture de la collecte, la détermination du seuil, la campagne de tests et les résultats après mise en production.</p>
    <div class="renvoi-actions">
      <a class="btn btn--plein" href="<?php echo esc_url( get_theme_file_uri( 'assets/documents/Dossier_Professionnel_Antoine_Brevard.pdf' ) ); ?>" target="_blank" rel="noopener">Le dossier (PDF)</a>
      <a class="btn" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Autres réalisations</a>
    </div>
  </div>
</div>
<!-- /wp:html -->
