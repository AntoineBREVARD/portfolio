<?php
/**
 * Title: Page — Grille de compétences
 * Slug: brevard-releve/page-grille
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * Le bloc Fichier est vide : il suffit d'y téléverser la grille. Un PDF
 * s'affiche dans la page avec son bouton de téléchargement ; un tableur est
 * seulement proposé au téléchargement. Le message d'attente disparaît tout
 * seul dès qu'un fichier est en place.
 *
 * @package brevard-releve
 */
?>
<!-- wp:group {"className":"bloc","layout":{"type":"default"}} -->
<div class="wp-block-group bloc">
<!-- wp:group {"className":"coque grille","layout":{"type":"default"}} -->
<div class="wp-block-group coque grille">
<!-- wp:file {"displayPreview":true} /-->

<!-- wp:paragraph {"className":"vide grille-attente"} -->
<p class="vide grille-attente">La grille de compétences n'a pas encore été déposée.</p>
<!-- /wp:paragraph -->
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
<div class="renvoi">
  <p><strong>Chaque ligne de la grille renvoie à une réalisation</strong> documentée, avec les solutions écartées et la raison du rejet.</p>
  <a class="btn btn--plein" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Voir les réalisations</a>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
