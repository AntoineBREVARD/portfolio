<?php
/**
 * Title: Réalisation — Poste par poste, à la main
 * Slug: brevard-releve/contenu-maj
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * Contenu de la fiche « maj », repris du site statique. Il sert au premier
 * remplissage : une fois la fiche modifiée dans WordPress, c'est la version
 * enregistrée qui fait foi.
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<dl class="cas-meta">
    <div><dt>Parc</dt><dd>HP et Dell</dd></div>
    <div><dt>Systèmes</dt><dd>Windows 10 et 11</dd></div>
    <div><dt>Codes retour définis</dt><dd>5</dd></div>
</dl>

<div class="cas-etape">
  <h2>Ce qui n'allait pas</h2>
  <p>Les pilotes, firmwares et BIOS se mettaient à jour poste par poste, à la main. C'était chronophage, impossible à suivre à l'échelle du parc, et cela n'arrivait en pratique que dans trois cas : un problème déjà survenu, une opération de prévention, ou le déploiement d'un poste neuf.</p>
  <p>Autrement dit : la maintenance préventive était une intention, pas une pratique.</p>
</div>

<div class="cas-etape">
  <h2>Ne pas remplacer les outils, les orchestrer</h2>
  <p>HP et Dell fournissent déjà leurs utilitaires — HP Image Assistant et Dell Command Update. Ils font correctement leur travail. Le problème n'était pas leur qualité, c'était qu'ils s'utilisent un poste à la fois.</p>
  <p>L'enjeu était donc de les <strong>piloter depuis un script centralisé</strong>, capable de détecter le constructeur et d'orienter le traitement, plutôt que de réinventer ce qui existait.</p>
</div>

<div class="cas-etape">
  <h2>Deux chemins possibles</h2>
  <p>Le premier coûtait moins cher à première vue.</p>
  <div class="solutions">
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Laisser les utilisateurs lancer l'outil eux-mêmes</h3>
    <ul>
      <li class="pour">Aucune infrastructure, peu de charge apparente pour le service</li>
      <li class="contre">Dépend entièrement de l'utilisateur</li>
      <li class="contre">Risque d'interruption en pleine mise à jour</li>
      <li class="contre">Aucune traçabilité, parc hétérogène</li>
    </ul>
  </div>
  <div class="solution est-retenue">
    <span class="solution-verdict">Retenue</span>
    <h3>Deux stratégies de groupe, dont une interface d'information</h3>
    <ul>
      <li class="pour">Traitement automatisé, compatible HP et Dell</li>
      <li class="pour">L'utilisateur est prévenu, et ne peut pas couper au mauvais moment</li>
      <li class="pour">Journalisation exploitable, sans besoin d'être sur site</li>
      <li class="contre">Développement et maintenance, et autant de cas que de constructeurs</li>
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
        <h3>On y va quand ça casse.</h3>
        <p>Chaque poste nécessitait une intervention manuelle. À l'échelle du parc, les mises à jour n'étaient déclenchées qu'après un incident, lors d'une opération de prévention, ou à l'installation d'un poste neuf.</p>
      </div>
      <div class="ba-apres">
        <span class="ba-tag">Après</span>
        <h3>Le parc se tient à jour tout seul.</h3>
        <p>Une stratégie de groupe dépose le script, une autre lance l'interface au bon moment. Le script détecte le constructeur, appelle l'outil correspondant et journalise tout. Si aucune mise à jour n'est disponible, l'utilisateur ne voit jamais rien.</p>
      </div>
      <div class="ba-ligne" aria-hidden="true"></div>
      <input class="ba-curseur" type="range" min="0" max="100" value="50"
             id="ba-maj" aria-label="Comparer la situation avant et après">
    </div>
    <p class="ba-aide">Faites glisser pour comparer</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que je referais autrement</h2>
  <div class="recul">
    <h3>L'interface a bloqué en production, et je le dis</h3>
    <p>Lors de la mise en production, des blocages ponctuels de l'interface graphique ont été observés sur certains postes. Le principe de la solution n'est pas remis en cause, mais la robustesse de la partie visible par l'utilisateur, si.</p>
    <p>Trois évolutions sont identifiées : une temporisation, une fermeture contrôlée en cas d'inactivité prolongée, et une centralisation des rapports d'exécution pour ne plus dépendre d'une lecture poste par poste.</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que cette mission démontre</h2>
  <p>Chaque étiquette renvoie à la grille de compétences.</p>
  <div class="preuves">
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Gérer le patrimoine informatique</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Automatiser des tâches d'administration</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Déployer un service</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Accompagner les utilisateurs</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Évaluer et améliorer la qualité d'un service</a>
  </div>
</div>

<div class="cas-etape">
  <div class="renvoi">
    <p><strong>Le détail est dans le dossier professionnel :</strong> l'orchestration des deux outils constructeurs, l'interface développée en C#, les codes retour et le tableau de recette.</p>
    <a class="btn btn--plein" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Autres réalisations</a>
  </div>
</div>
<!-- /wp:html -->
