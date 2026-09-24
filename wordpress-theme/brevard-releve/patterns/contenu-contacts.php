<?php
/**
 * Title: Réalisation — Microsoft allait fermer la porte
 * Slug: brevard-releve/contenu-contacts
 * Categories: brevard-releve
 * Viewport Width: 1320
 * Inserter: no
 *
 * Contenu de la fiche « contacts », repris du site statique. Il sert au premier
 * remplissage : une fois la fiche modifiée dans WordPress, c'est la version
 * enregistrée qui fait foi.
 *
 * @package brevard-releve
 */
?>
<!-- wp:html -->
<dl class="cas-meta">
    <div><dt>Structure</dt><dd>ACO</dd></div>
    <div><dt>Environnement</dt><dd>Microsoft 365 hybride</dd></div>
    <div><dt>Boîtes concernées</dt><dd>300+</dd></div>
</dl>

<div class="cas-etape">
  <h2>Ce qui allait casser</h2>
  <p>Beaucoup de collaborateurs synchronisent le carnet d'adresses de l'entreprise sur leur téléphone professionnel. Pendant une épreuve, c'est ce qui permet d'appeler la bonne personne en quelques secondes.</p>
  <p>Ce carnet reposait sur les <strong>dossiers publics</strong> d'Outlook Classic. Le nouvel Outlook ne les gère pas, et le basculement était engagé. À cela s'ajoutait une corvée : à chaque mise à jour des ressources humaines, il fallait tout supprimer et tout recopier à la main.</p>
</div>

<div class="cas-etape">
  <h2>Le piège</h2>
  <p>Sur ces fiches de contact, des collaborateurs avaient ajouté leurs propres informations — numéros personnels, notes, adresses. Elles ne venaient pas de l'entreprise et n'étaient nulle part ailleurs.</p>
  <p><strong>Une synchronisation brutale les effaçait.</strong> C'est cette contrainte, plus que la technique, qui a dicté toute l'architecture.</p>
</div>

<div class="cas-etape">
  <h2>Trois chemins possibles</h2>
  <p>Ce que j'ai écarté compte autant que ce que j'ai retenu.</p>
  <div class="solutions">
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Un script sur chaque poste, déployé par stratégie de groupe</h3>
    <ul>
      <li class="pour">L'utilisateur décide lui-même</li>
      <li class="contre">Chaque poste aurait détenu les droits d'une application d'entreprise</li>
      <li class="contre">Faille inacceptable : c'est ce qui a tué l'option</li>
    </ul>
  </div>
  <div class="solution">
    <span class="solution-verdict">Écartée</span>
    <h3>Un script sur serveur, déclenché par l'utilisateur</h3>
    <ul>
      <li class="pour">Plus aucune trace sensible sur les postes</li>
      <li class="contre">Impose le VPN aux collaborateurs en déplacement</li>
      <li class="contre">Demande encore une action de l'utilisateur</li>
    </ul>
  </div>
  <div class="solution est-retenue">
    <span class="solution-verdict">Retenue</span>
    <h3>Une tâche planifiée côté serveur, sans aucune interaction</h3>
    <ul>
      <li class="pour">Identique pour tout le monde, sans exception</li>
      <li class="pour">Aucune obligation d'être sur site</li>
      <li class="pour">Rien de sensible ne quitte le serveur</li>
    </ul>
  </div>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que ça change</h2>
  <div class="ba" data-ba>
    <div class="ba-cadre">
      <div class="ba-avant">
        <span class="ba-tag">Avant</span>
        <h3>Supprimer, recopier, recommencer.</h3>
        <p>À chaque mise à jour des ressources humaines, chaque collaborateur devait vider son dossier de contacts et le recréer à la main. Ceux qui ne le faisaient pas travaillaient avec un annuaire périmé — et personne ne savait lesquels.</p>
      </div>
      <div class="ba-apres">
        <span class="ba-tag">Après</span>
        <h3>Plus personne ne fait rien.</h3>
        <p>La synchronisation tourne seule, pour tout le monde, où que soit le poste. Et ce que le collaborateur a saisi lui-même reste en place : le moteur compare avant d'écrire, et ne remplace jamais sans avoir sauvegardé.</p>
      </div>
      <div class="ba-ligne" aria-hidden="true"></div>
      <input class="ba-curseur" type="range" min="0" max="100" value="50"
             id="ba-contacts" aria-label="Comparer la situation avant et après">
    </div>
    <p class="ba-aide">Faites glisser pour comparer</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que je referais autrement</h2>
  <div class="recul">
    <h3>Une recette n'est pas une preuve à l'échelle</h3>
    <p>La solution a été validée sur une boîte de test, à travers huit familles de scénarios : création, mise à jour, conservation des données personnelles, doublons, reprise après erreur, départ et retour d'un collaborateur.</p>
    <p>C'est une validation sérieuse. Ce n'est pas une preuve sur 300 boîtes réelles, avec leurs historiques et leurs cas particuliers. Si je reprenais le projet, je déroulerais un <strong>pilote progressif sur un vrai service</strong> avant la généralisation.</p>
  </div>
</div>

<div class="cas-etape">
  <h2>Ce que cette mission démontre</h2>
  <p>Chaque étiquette renvoie à la grille de compétences.</p>
  <div class="preuves">
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Élaborer un dossier de choix</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Étudier l'impact d'une évolution</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Gérer des sauvegardes</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Automatiser des tâches d'administration</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Tests d'intégration et d'acceptation</a>
    <a class="preuve-lien" href="<?php echo esc_url( brevard_releve_lien( 'grille-competences' ) ); ?>">Protéger les données personnelles</a>
  </div>
</div>

<div class="cas-etape">
  <div class="renvoi">
    <p><strong>Le détail est dans le dossier professionnel :</strong> le découpage MASTER / CORE, les six phases de traitement, les mécanismes de sécurisation et la campagne de recette complète.</p>
    <div class="renvoi-actions">
      <a class="btn btn--plein" href="<?php echo esc_url( get_theme_file_uri( 'assets/documents/Dossier_Professionnel_Antoine_Brevard.pdf' ) ); ?>" download>Le dossier (PDF)</a>
      <a class="btn" href="<?php echo esc_url( brevard_releve_lien( 'realisations' ) ); ?>">Autres réalisations</a>
    </div>
  </div>
</div>
<!-- /wp:html -->
