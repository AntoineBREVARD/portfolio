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
