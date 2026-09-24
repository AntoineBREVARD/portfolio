<?php
/**
 * Title: Rail — liens de navigation
 * Slug: brevard-releve/rail-nav
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<div class="rail-nav">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Réalisations</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'veilles' ) ); ?>">Veilles</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Grille</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'profil' ) ); ?>">Profil</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'jury' ) ); ?>">Jury</a>
</div>
<!-- /wp:html -->
