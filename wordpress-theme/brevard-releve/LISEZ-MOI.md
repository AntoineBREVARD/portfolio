# Brévard — Le Relevé

Thème de blocs WordPress pour le portfolio BTS SIO option SISR d'Antoine Brévard.

## Installation

1. Créer l'archive : compresser le dossier `brevard-releve` en `.zip`.
2. Dans WordPress : **Apparence → Thèmes → Ajouter → Téléverser un thème**.
3. Activer.
4. **Réglages → Permaliens → Enregistrer** (sans rien changer). Cette étape est
   obligatoire : elle régénère les règles de réécriture pour le type de contenu
   « Réalisation », sinon ses pages renvoient une erreur 404.

Aucun plugin n'est nécessaire, et c'est voulu : l'hébergement visé n'a pas
d'accès Internet sortant, donc rien ne peut être installé depuis la
bibliothèque WordPress.

## Remplir le site

Le thème apporte le dessin et les gabarits ; le contenu se crée dans
WordPress. Dans l'ordre :

**1. Les trois pages.** Pour chacune : **Pages → Ajouter**, donner le titre et
l'identifiant (le « slug ») exactement comme ci-dessous, puis dans le contenu
insérer la composition du même nom (bouton **+** → onglet **Compositions** →
catégorie **Le Relevé**).

| Titre       | Identifiant   | Composition à insérer |
|-------------|---------------|-----------------------|
| Compétences | `competences` | Page — Compétences    |
| Profil      | `profil`      | Page — Profil         |
| Jury        | `jury`        | Page — Jury           |

Le titre s'affiche en très gros caractères en haut de la page, et le
**résumé** (panneau de droite, « Extrait ») sert de phrase d'accroche sous
le titre. Les deux se modifient librement ; l'identifiant, lui, ne doit pas
changer : les liens du site le cherchent.

**2. Les réalisations.** Menu **Réalisations → Ajouter**. Chaque fiche s'ouvre
déjà structurée. Pour que la matrice de compétences pointe vers les bonnes
fiches, garder ces identifiants : `contacts`, `quotas`, `teams`, `maj`.

Il n'y a pas de page « Réalisations » à créer : la liste est produite
automatiquement à partir des fiches publiées.

**3. Le ménage.** Supprimer la page « Sample Page » et l'article « Hello
world! » créés par WordPress à l'installation.

**4. Le titre du site.** **Réglages → Général** : il s'affiche dans l'onglet
du navigateur et dans les résultats de recherche.

Tant qu'une page n'existe pas, les liens qui la visent renvoient vers
l'accueil plutôt que vers une erreur : le site reste navigable pendant qu'on
le remplit.

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
- la matrice de compétences qui se lit dans les deux sens.

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
