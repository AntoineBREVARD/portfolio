<?php
/**
 * Title: Page — Veilles
 * Slug: brevard-releve/page-veilles
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * La liste elle-même vient du gabarit de la page (bloc
 * brevard-releve/veilles) : elle se met à jour à chaque veille publiée. Cette
 * composition n'en porte que le renvoi vers la grille.
 *
 * @package brevard-releve
 */
?>
<!-- wp:group {"className":"bloc bloc--serre bloc--sable","layout":{"type":"default"}} -->
<div class="wp-block-group bloc bloc--serre bloc--sable">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<div class="renvoi">
  <p><strong>La veille s'inscrit dans le référentiel :</strong> elle démontre la compétence « Organiser son développement professionnel », reprise dans la grille.</p>
  <a class="btn btn--plein" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Voir la grille</a>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
