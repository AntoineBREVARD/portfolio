# Brévard — Le Relevé

Thème de blocs WordPress pour le portfolio BTS SIO option SISR d'Antoine Brévard.

## Installation avec WP Pusher (recommandé)

Le thème vit dans le dépôt GitHub du portfolio, dans un sous-dossier. WP
Pusher sait l'y chercher :

1. **WP Pusher → Install Theme**.
2. **Theme repository** : `AntoineBREVARD/portfolio`
3. **Repository branch** : `main`
4. **Repository subdirectory** : `wordpress-theme/brevard-releve`
   — sans ce réglage, WP Pusher prend la racine du dépôt, qui est le site
   statique, et refuse d'installer : il n'y trouve pas de `style.css` de thème.
5. Cocher **Push-to-Deploy** pour que chaque modification poussée sur GitHub
   mette le thème à jour toute seule.
6. **Install theme**, puis **Apparence → Thèmes → Activer**.

Si le dépôt est privé, WP Pusher demande un jeton GitHub (et sa version
payante). Un dépôt public fonctionne avec la version gratuite.

Sans WP Pusher : compresser le dossier `brevard-releve` en `.zip`, puis
**Apparence → Thèmes → Ajouter → Téléverser un thème**.

## Le site se remplit tout seul

Au premier passage dans l'admin après l'activation, le thème crée ce qui
manque :

| Contenu                     | Adresse                  |
|-----------------------------|--------------------------|
| Page Veilles                | `/veilles/`              |
| Page Grille de compétences  | `/grille-competences/`   |
| Page Profil                 | `/profil/`               |
| Page Jury                   | `/jury/`                 |
| Les quatre réalisations     | `/realisation/contacts/`, `quotas`, `teams`, `maj` |

Il recalcule aussi les permaliens : plus besoin de passer par **Réglages →
Permaliens**. Ce qui existe déjà n'est jamais écrasé, et une page mise à la
corbeille n'est pas recréée. L'ancienne page « Compétences », si elle existe,
passe en brouillon : la grille la remplace.

Les identifiants (le « slug ») ne doivent pas changer : les liens du site les
cherchent. Le titre et le résumé (panneau de droite, « Extrait ») se
modifient librement.

Reste à faire à la main : supprimer « Sample Page » et « Hello world! », et
régler le titre du site dans **Réglages → Général**.

## Déposer une veille

Menu **Veilles → Ajouter une veille** :

1. le titre ;
2. le fichier : la veille s'ouvre avec un bloc **Fichier** déjà en place,
   cliquer sur **Téléverser** et choisir le PDF, le Word ou le PowerPoint ;
3. le résumé (panneau de droite, « Extrait ») : une phrase, affichée sous le
   titre dans la liste ;
4. **Publier**. La date de publication sert de date de la veille : la modifier
   dans le panneau de droite pour une veille plus ancienne.

La page Veilles les liste de la plus récente à la plus ancienne ; un clic
télécharge le document.

## Déposer la grille de compétences

**Pages → La grille de compétences → Modifier**. Le bloc **Fichier** est déjà
dans la page : **Téléverser**, choisir la grille, **Mettre à jour**. Un PDF
s'affiche directement dans la page avec un bouton de téléchargement ; un
fichier Excel est proposé au téléchargement. Pour une nouvelle version,
remplacer le fichier dans le même bloc.

## Ce qui s'édite sans code

Depuis **Apparence → Éditeur** :

- les couleurs et les polices (Styles) ;
- l'ordre et le contenu des sections (Modèles) ;
- le rail de navigation (Parties de modèle → Rail de navigation) ;
- le pied de page.

Depuis l'admin classique :

- les **Réalisations** : un menu dédié. Chaque nouvelle réalisation est
  pré-remplie avec la structure complète d'une fiche — il ne reste qu'à
  remplacer les textes.
- les pages, les images, les menus.

## Ce qui reste dans le code

Trois interactions vivent dans `assets/releve.js` :

- la typographie qui se comprime au défilement ;
- la révélation de la trame technique dans les lettres du nom ;
- le comparateur avant / après des fiches.

La liste des veilles et l'installation du contenu sont dans `functions.php`.

Elles s'accrochent à des classes CSS. Tant que les compositions gardent ces
classes, elles fonctionnent. Les désactiver se fait en retirant le script ;
les modifier demande d'éditer le fichier.

## Polices

Les trois polices variables (Bricolage Grotesque, Instrument Sans, Martian
Mono, toutes sous licence SIL OFL) sont **embarquées dans le thème**, sous-
ensembles latin et latin-ext.

Ce n'est pas un détail technique : charger une police depuis un CDN tiers
transmet l'adresse IP du visiteur à ce tiers, ce qui constitue un traitement
de données à caractère personnel. Le thème n'émet aucune requête vers un
service externe.
