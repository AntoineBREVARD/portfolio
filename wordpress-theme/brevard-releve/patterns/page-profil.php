<?php
/**
 * Title: Page — Profil
 * Slug: brevard-releve/page-profil
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
<div class="parcours-grille">
  <figure class="parcours-portrait">
    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/antoine-brevard.webp' ) ); ?>" width="800" height="1200" alt="Portrait d'Antoine Brévard" loading="lazy" decoding="async">
    <figcaption>En poste</figcaption>
  </figure>
  <div>
    <p class="chapeau" style="margin-bottom:22px">Alternant technicien systèmes &amp; réseaux à l'Automobile Club de l'Ouest, en BTS SIO option SISR à la FAB'Academy du Mans.</p>
    <p style="color:var(--encre-2);max-width:var(--mesure)">J'y interviens sur le support utilisateurs, des projets d'infrastructure et l'administration d'un environnement Microsoft 365 hybride, aux côtés de serveurs Linux et de l'administration réseau.</p>
    <p style="color:var(--encre-2);max-width:var(--mesure)">Le temps d'une épreuve, le service se transforme en centre de commandement : l'infrastructure doit encaisser une charge sans commune mesure avec le reste de l'année, <strong>sans fenêtre de reprise</strong>, puisque la course ne s'interrompt pas.</p>
    <dl class="hero-releves" style="max-width:none">
      <div class="hero-releve"><dt>Formation</dt><dd>BTS SIO</dd></div>
      <div class="hero-releve"><dt>Option</dt><dd>SISR</dd></div>
      <div class="hero-releve"><dt>Certification</dt><dd>ANSSI</dd></div>
      <div class="hero-releve"><dt>Promotion</dt><dd>25—27</dd></div>
    </dl>
  </div>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:separator {"className":"vibreur vibreur--fin"} -->
<hr class="wp-block-separator vibreur vibreur--fin"/>
<!-- /wp:separator -->

<!-- wp:image {"align":"full","className":"bande bande--haute","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image alignfull bande bande--haute"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/aco-batiment.webp' ) ); ?>" alt="Le bâtiment de l'Automobile Club de l'Ouest sur le circuit, au soleil couchant"/><figcaption class="wp-element-caption">Automobile Club de l'Ouest — <b>photo personnelle</b></figcaption></figure>
<!-- /wp:image -->

<!-- wp:separator {"className":"vibreur vibreur--fin"} -->
<hr class="wp-block-separator vibreur vibreur--fin"/>
<!-- /wp:separator -->

<!-- wp:group {"className":"bloc bloc--serre bloc--sable","layout":{"type":"default"}} -->
<div class="wp-block-group bloc bloc--serre bloc--sable">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<div class="renvoi">
  <p><strong>Le contexte donne la mesure, les réalisations montrent les décisions.</strong> Quatre missions menées en production, avec ce que j'ai choisi de ne pas faire.</p>
  <a class="btn btn--plein" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Voir les réalisations</a>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
