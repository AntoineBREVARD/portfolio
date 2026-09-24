<?php
/**
 * Title: Fiche de réalisation (structure complète)
 * Slug: brevard-releve/fiche-realisation
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * @package brevard-releve
 */
?>
<!-- wp:heading {"className":"cas-etape-titre"} -->
<h2 class="wp-block-heading">Ce qui n'allait pas</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Décrivez le problème du point de vue humain avant le point de vue technique. Ce qui coûtait du temps, ce qui cassait, ce que l'utilisateur subissait.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">La contrainte qui a tout décidé</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Le détail, souvent invisible dans l'énoncé du besoin, qui a déterminé la faisabilité de la solution retenue.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Les chemins possibles</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ce que j'ai écarté compte autant que ce que j'ai retenu.</p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<div class="solutions">
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Première option</h3>
    <ul>
      <li class="pour">Un avantage réel</li>
      <li class="contre">Le motif du rejet, explicite</li>
    </ul>
  </div>
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Deuxième option</h3>
    <ul>
      <li class="pour">Un avantage réel</li>
      <li class="contre">Le motif du rejet, explicite</li>
    </ul>
  </div>
  <div class="solution est-retenue">
    <span class="solution-verdict">Retenue</span>
    <h3>Option retenue</h3>
    <ul>
      <li class="pour">Ce qui l'emporte</li>
      <li class="pour">Et ce qui l'emporte aussi</li>
    </ul>
  </div>
</div>
<!-- /wp:html -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ce que ça change</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="ba" data-ba>
  <div class="ba-cadre">
    <div class="ba-avant">
      <span class="ba-tag">Avant</span>
      <h3>L'état de départ, en une phrase.</h3>
      <p>Ce que ça coûtait concrètement, et à qui.</p>
    </div>
    <div class="ba-apres">
      <span class="ba-tag">Après</span>
      <h3>Ce qui a remplacé.</h3>
      <p>Ce qui a disparu du quotidien, et ce qui est devenu possible.</p>
    </div>
    <div class="ba-ligne" aria-hidden="true"></div>
    <input class="ba-curseur" type="range" min="0" max="100" value="50"
           aria-label="Comparer la situation avant et après">
  </div>
  <p class="ba-aide">Faites glisser pour comparer</p>
</div>
<!-- /wp:html -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ce que je referais autrement</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="recul">
  <h3>La limite, nommée</h3>
  <p>Un jury retient le recul, et la plupart des candidats cachent leurs limites. Dites ce qui n'a pas marché, ou ce qui reste à prouver.</p>
  <p>Puis ce que vous feriez différemment en reprenant le projet depuis le début.</p>
</div>
<!-- /wp:html -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ce que cette mission démontre</h2>
<!-- /wp:heading -->

<!-- wp:html -->
<div class="preuves">
  <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Intitulé exact de la compétence</a>
  <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Autre compétence du référentiel</a>
</div>
<!-- /wp:html -->