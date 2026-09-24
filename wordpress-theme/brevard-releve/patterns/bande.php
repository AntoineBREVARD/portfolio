<?php
/**
 * Title: Bande photo pleine largeur
 * Slug: brevard-releve/bande
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * Un bloc HTML plutôt qu'un bloc Image : il faut un srcset. Servie en une
 * seule taille (3000 px), la photo était réduite par le navigateur en
 * passant par une version intermédiaire, et paraissait floue sur ordinateur.
 *
 * @package brevard-releve
 */

$brevard_releve_img = function ( $largeur ) {
	return esc_url( get_theme_file_uri( 'assets/images/musee-24h-' . $largeur . '.webp' ) );
};
?>
<!-- wp:html -->
<figure class="alignfull bande"><img src="<?php echo $brevard_releve_img( 1920 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé ci-dessus ?>" srcset="<?php echo $brevard_releve_img( 1280 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 1280w, <?php echo $brevard_releve_img( 1920 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 1920w, <?php echo $brevard_releve_img( 2560 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> 2560w" sizes="(min-width: 901px) calc(100vw - 76px), 100vw" width="1920" height="817" alt="Ferrari rouge n° 20 exposée au musée des 24 Heures du Mans, à côté d'un prototype hybride" loading="lazy" decoding="async"><figcaption>Musée des 24 Heures du Mans — <b>photo personnelle</b></figcaption></figure>
<!-- /wp:html -->

<!-- wp:separator {"className":"vibreur vibreur--fin"} -->
<hr class="wp-block-separator vibreur vibreur--fin"/>
<!-- /wp:separator -->
