<?php
/**
 * Title: Page — Jury
 * Slug: brevard-releve/page-jury
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * @package brevard-releve
 */
?>
<!-- wp:group {"className":"bloc","layout":{"type":"default"}} -->
<div class="wp-block-group bloc">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<dl class="jury">
  <div class="jury-case">
    <dt>Situations professionnelles</dt>
    <dd>Quatre réalisations menées en production, du contexte au résultat, avec les solutions écartées et le motif du rejet.</dd>
    <a href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Les quatre fiches</a>
  </div>
  <div class="jury-case">
    <dt>Grille de compétences</dt>
    <dd>Le tableau de synthèse du référentiel, à consulter en ligne ou à télécharger.</dd>
    <a href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">La grille</a>
  </div>
  <div class="jury-case">
    <dt>Veilles technologiques</dt>
    <dd>Les documents de veille produits pendant la formation.</dd>
    <a href="<?php echo esc_url( brevard_releve_lien( 'veilles' ) ); ?>">Les veilles</a>
  </div>
  <div class="jury-case">
    <dt>Dossier professionnel et CV</dt>
    <dd>Le détail des quatre réalisations, et le CV en une page. Au format PDF.</dd>
    <a href="<?php echo esc_url( get_theme_file_uri( 'assets/documents/Dossier_Professionnel_Antoine_Brevard.pdf' ) ); ?>" download>Le dossier</a>
    <a href="<?php echo esc_url( get_theme_file_uri( 'assets/documents/CV_Antoine_Brevard.pdf' ) ); ?>" download>Le CV</a>
  </div>
</dl>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:separator {"className":"vibreur vibreur--fin"} -->
<hr class="wp-block-separator vibreur vibreur--fin"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"bloc bloc--serre bloc--sable","layout":{"type":"default"}} -->
<div class="wp-block-group bloc bloc--serre bloc--sable">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<div class="tete">
  <p class="etiquette"><span>Me joindre</span></p>
  <h2 class="geant" style="font-size:clamp(30px,4.6vw,62px)">Une question ?</h2>
</div>
<dl class="jury">
  <div class="jury-case">
    <dt>Courriel</dt>
    <dd>antoinebrevard8@gmail.com</dd>
    <a href="mailto:antoinebrevard8@gmail.com">Écrire</a>
  </div>
  <div class="jury-case">
    <dt>LinkedIn</dt>
    <dd>Parcours et publications.</dd>
    <a href="https://www.linkedin.com/in/antoine-br%C3%A9vard-20b483303/">Voir le profil</a>
  </div>
  <div class="jury-case">
    <dt>GitHub</dt>
    <dd>Le code de ce site, et mes dépôts.</dd>
    <a href="https://github.com/AntoineBREVARD">Voir les dépôts</a>
  </div>
  <div class="jury-case">
    <dt>Lieu</dt>
    <dd>Le Mans, Sarthe.</dd>
  </div>
</dl>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
