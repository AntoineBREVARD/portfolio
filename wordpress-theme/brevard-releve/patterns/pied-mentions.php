<?php
/**
 * Title: Pied — mentions légales
 * Slug: brevard-releve/pied-mentions
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * Le pied de page est un fragment HTML : il ne sait pas calculer l'adresse
 * de la page À propos. Cette composition, en PHP, la lui donne.
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<p>Site sans traceur ni cookie · <a href="<?php echo esc_url( brevard_releve_lien( 'a-propos' ) ); ?>">À propos &amp; mentions légales</a></p>
<!-- /wp:html -->
