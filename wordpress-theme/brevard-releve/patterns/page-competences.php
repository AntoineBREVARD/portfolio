<?php
/**
 * Title: Page — Compétences
 * Slug: brevard-releve/page-competences
 * Categories: brevard-releve
 * Viewport Width: 1320
 *
 * @package brevard-releve
 */
?>
<!-- wp:group {"className":"bloc bloc--vert","layout":{"type":"default"}} -->
<div class="wp-block-group bloc bloc--vert">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<div class="matrice">
        <div class="matrice-bloc">
          <div class="matrice-tete">
            <span class="matrice-code">Bloc 1</span>
            <span class="matrice-nom">Support et mise à disposition de services informatiques</span>
            <span class="matrice-epreuve">Épreuve E4</span>
          </div>
          <div class="ligne est-partiel" data-preuves="p1 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Gérer le patrimoine informatique</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Partiel</span>
          </div>
          <div class="ligne est-absent" data-preuves="">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Répondre aux incidents et aux demandes d'assistance</p>
              <div class="ligne-preuves">
                <span class="preuve" style="border-style:dashed">Situation à documenter</span>
              </div>
            </div>
            <span class="ligne-statut">Absent</span>
          </div>
          <div class="ligne est-absent" data-preuves="">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Développer la présence en ligne de l'organisation</p>
            </div>
            <span class="ligne-statut">Absent</span>
          </div>
          <div class="ligne est-partiel" data-preuves="p1 p2 p3 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Travailler en mode projet</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'quotas' ) ); ?>" data-ref="p2">02</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'teams' ) ); ?>" data-ref="p3">03</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Partiel</span>
          </div>
          <div class="ligne est-prouve" data-preuves="p1 p2 p3 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Mettre à disposition des utilisateurs un service informatique</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'quotas' ) ); ?>" data-ref="p2">02</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'teams' ) ); ?>" data-ref="p3">03</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Prouvé</span>
          </div>
          <div class="ligne est-absent" data-preuves="">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Organiser son développement professionnel</p>
              <div class="ligne-preuves">
                <span class="preuve" style="border-style:dashed">Veille à ouvrir</span>
              </div>
            </div>
            <span class="ligne-statut">Absent</span>
          </div>
        </div>
        <div class="matrice-bloc">
          <div class="matrice-tete">
            <span class="matrice-code">Bloc 2</span>
            <span class="matrice-nom">Administration des systèmes et des réseaux</span>
            <span class="matrice-epreuve">Épreuve E5 · SISR</span>
          </div>
          <div class="ligne est-prouve" data-preuves="p1 p2 p3 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Concevoir une solution d'infrastructure réseau</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'quotas' ) ); ?>" data-ref="p2">02</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'teams' ) ); ?>" data-ref="p3">03</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Prouvé</span>
          </div>
          <div class="ligne est-partiel" data-preuves="p1 p3 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Installer, tester et déployer une solution d'infrastructure</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'teams' ) ); ?>" data-ref="p3">03</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Partiel</span>
          </div>
          <div class="ligne est-prouve" data-preuves="p1 p2 p4">
            <span class="ligne-barre"></span>
            <div>
              <p class="ligne-intitule">Exploiter, dépanner et superviser une solution d'infrastructure</p>
              <div class="ligne-preuves">
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'contacts' ) ); ?>" data-ref="p1">01</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'quotas' ) ); ?>" data-ref="p2">02</a>
                <a class="preuve" href="<?php echo esc_url( brevard_releve_fiche( 'maj' ) ); ?>" data-ref="p4">04</a>
              </div>
            </div>
            <span class="ligne-statut">Prouvé</span>
          </div>
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

<!-- wp:group {"className":"bloc bloc--serre bloc--sable","layout":{"type":"default"}} -->
<div class="wp-block-group bloc bloc--serre bloc--sable">
<!-- wp:group {"className":"coque","layout":{"type":"default"}} -->
<div class="wp-block-group coque">
<!-- wp:html -->
<div class="renvoi">
  <p><strong>Les lignes « Absent » sont affichées telles quelles.</strong> Une situation d'incident et un journal de veille restent à produire : mieux vaut savoir où j'en suis que masquer les trous.</p>
  <a class="btn btn--plein" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Voir les réalisations</a>
</div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
