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
<button class="rail-menu" type="button" aria-expanded="false" aria-controls="railNav"><span class="rail-menu-barres" aria-hidden="true"></span><span class="rail-menu-texte">Menu</span></button>
<div class="rail-nav" id="railNav">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Réalisations</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'veilles' ) ); ?>">Veilles</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Grille</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'profil' ) ); ?>">Profil</a>
  <a href="<?php echo esc_url( brevard_releve_lien( 'entreprise' ) ); ?>">Entreprise</a>
</div>
<!-- /wp:html -->
