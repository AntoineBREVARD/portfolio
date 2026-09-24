<?php
/**
 * Title: Réalisation — Une image de marque, 300 écrans
 * Slug: brevard-releve/contenu-teams
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * Contenu de la fiche « teams », repris du site statique. Il sert au premier
 * remplissage : une fois la fiche modifiée dans WordPress, c'est la version
 * enregistrée qui fait foi.
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<dl class="cas-meta">
    <div><dt>Demandeur</dt><dd>Service communication</dd></div>
    <div><dt>Déploiement</dt><dd>GPO à l'ouverture de session</dd></div>
    <div><dt>Licences achetées</dt><dd>0</dd></div>
</dl>

<div class="cas-etape">
  <h2>Ce qui était demandé</h2>
  <p>Les collaborateurs enchaînent les réunions avec des partenaires, des journalistes, des prestataires et des équipes externes. Un arrière-plan homogène, c'est l'image de l'organisation qui reste cohérente d'un appel à l'autre.</p>
  <p>La demande venait du service communication, qui avait déjà produit les visuels. Restait à les mettre entre les mains de tout le monde — sans compter sur la bonne volonté de chacun.</p>
</div>

<div class="cas-etape">
  <h2>La contrainte qui a tout décidé</h2>
  <p>Le nouveau client Teams ne se contente pas d'un dossier d'images. Il exige une <strong>structure de fichiers particulière</strong> : un nom au format GUID, et une miniature associée à chaque image, sans quoi l'arrière-plan n'apparaît simplement pas dans l'interface.</p>
  <p>C'est ce détail, invisible dans l'énoncé de la demande, qui a déterminé la faisabilité de la solution retenue.</p>
</div>

<div class="cas-etape">
  <h2>Trois chemins possibles</h2>
  <p>La solution officielle existait. Elle se payait.</p>
  <div class="solutions">
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Mettre le pack à disposition, chacun l'importe</h3>
    <ul>
      <li class="pour">Aucune infrastructure, aucun développement</li>
      <li class="contre">Déploiement très long, et dépendant de chaque utilisateur</li>
      <li class="contre">Versions anciennes qui subsistent, aucune homogénéité</li>
    </ul>
  </div>
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Gestion centralisée via Teams Premium</h3>
    <ul>
      <li class="pour">Solution officiellement supportée, administration centralisée</li>
      <li class="contre">Nécessite des licences Teams Premium</li>
      <li class="contre">Coût supplémentaire pour un besoin cosmétique</li>
    </ul>
  </div>
  <div class="solution est-retenue">
    <span class="solution-verdict">Retenue</span>
    <h3>Déploiement automatisé par stratégie de groupe</h3>
    <ul>
      <li class="pour">Aucune action utilisateur, tout le monde au même niveau</li>
      <li class="pour">Mise à jour centralisée : une archive à remplacer</li>
      <li class="contre">Développement et maintenance du script à assumer</li>
    </ul>
  </div>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que ça change</h2>
  <div class="ba" data-ba>
    <div class="ba-cadre">
      <div class="ba-avant">
        <span class="ba-tag">Avant</span>
        <h3>Chacun se débrouille.</h3>
        <p>Le pack est disponible quelque part, à charge pour chacun de l'importer dans son client Teams. En pratique : une minorité le fait, personne n'a la même version, et l'image reste hétérogène.</p>
      </div>
      <div class="ba-apres">
        <span class="ba-tag">Après</span>
        <h3>Tout le monde les a, sans rien faire.</h3>
        <p>Le script récupère l'archive depuis le partage interne à l'ouverture de session, génère les noms et les miniatures attendus par le client Teams, et place les fichiers au bon endroit. Renouveler les visuels revient à remplacer une archive.</p>
      </div>
      <div class="ba-ligne" aria-hidden="true"></div>
      <input class="ba-curseur" type="range" min="0" max="100" value="50"
             id="ba-teams" aria-label="Comparer la situation avant et après">
    </div>
    <p class="ba-aide">Faites glisser pour comparer</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que je referais autrement</h2>
  <div class="recul">
    <h3>La solution dépend d'un format qui n'est pas le mien</h3>
    <p>Le script fonctionne parce qu'il reproduit exactement ce qu'attend une version donnée du client Teams : noms en GUID, miniatures, emplacements. Rien de tout cela n'est documenté comme une interface stable.</p>
    <p>Une évolution du client peut donc casser le déploiement sans prévenir. Ce que j'ajouterais : un contrôle après exécution qui vérifie que les arrière-plans sont bien visibles, plutôt que de supposer que la copie a suffi.</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que cette mission démontre</h2>
  <p>Chaque étiquette renvoie à la grille de compétences.</p>
  <div class="preuves">
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Déployer un service</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Accompagner les utilisateurs</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Installer et configurer des éléments d'infrastructure</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Élaborer un dossier de choix</a>
  </div>
</div>

<div class="cas-etape">
  <div class="renvoi">
    <p><strong>Le détail est dans le dossier professionnel :</strong> l'architecture du déploiement, la gestion des erreurs, les contextes d'exécution pris en charge et le tableau de recette.</p>
    <div class="renvoi-actions">
      <a class="btn btn--plein" href="<?php echo esc_url( get_theme_file_uri( 'assets/documents/Dossier_Professionnel_Antoine_Brevard.pdf' ) ); ?>" target="_blank" rel="noopener">Le dossier (PDF)</a>
      <a class="btn" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Autres réalisations</a>
    </div>
  </div>
</div>
<!-- /wp:html -->
