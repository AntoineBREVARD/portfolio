# Mettre en route l'admin du site (édition sans code)

Le site a maintenant un panneau d'administration à l'adresse `/admin/` (par
exemple `https://tonsite.github.io/portfolio/admin/` une fois en ligne),
propulsé par [Decap CMS](https://decapcms.org/). Une fois connecté avec ton
compte GitHub, tu peux modifier tous les textes, photos et listes (missions,
compétences, photos du terrain...) depuis des formulaires, sans toucher au
code — un bouton "Publier" fait le commit + push à ta place.

Il manque une seule pièce pour que ça marche en ligne : un petit serveur qui
échange ton autorisation GitHub contre un jeton d'accès (c'est GitHub qui
l'impose — le "secret" de cette étape ne doit jamais être visible dans le
navigateur ni dans le repo). Ça se déploie gratuitement en 10 minutes sur
Cloudflare. Voici les étapes, dans l'ordre :

## 1. Créer une app OAuth GitHub

1. Va sur [github.com/settings/developers](https://github.com/settings/developers) → **OAuth Apps** → **New OAuth App**.
2. Remplis :
   - **Application name** : `Portfolio CMS` (ou ce que tu veux)
   - **Homepage URL** : l'URL de ton site (ex. `https://antoinebrevard.github.io/portfolio`)
   - **Authorization callback URL** : laisse `https://exemple.workers.dev/callback` pour l'instant, tu la corrigeras à l'étape 3 une fois que tu connaîtras l'URL réelle du worker.
3. Clique **Register application**.
4. Note le **Client ID** affiché.
5. Clique **Generate a new client secret**, et copie-le tout de suite (il ne sera plus jamais réaffiché en clair).

## 2. Déployer le serveur d'authentification (Cloudflare Workers, gratuit)

1. Crée un compte sur [dash.cloudflare.com](https://dash.cloudflare.com) si tu n'en as pas.
2. Dans le menu, va sur **Workers & Pages** → **Create** → **Create Worker**. Donne-lui un nom, par ex. `portfolio-cms-auth`, puis **Deploy** (ça déploie un worker vide, normal).
3. Clique **Edit code**. Supprime tout le code d'exemple, et colle à la place le contenu du fichier [`oauth-worker/worker.js`](oauth-worker/worker.js) de ce repo. **Save and deploy**.
4. Note l'URL du worker affichée en haut (quelque chose comme `https://portfolio-cms-auth.TON-PSEUDO.workers.dev`).
5. Retourne sur la page du worker → **Settings** → **Variables and Secrets** → **Add variable**, et ajoute :
   - `GITHUB_CLIENT_ID` = le Client ID noté à l'étape 1 (type "Text")
   - `GITHUB_CLIENT_SECRET` = le secret noté à l'étape 1 (type **"Secret"**, pour qu'il soit chiffré)
6. Sauvegarde et redéploie si demandé.

## 3. Relier les deux

1. Retourne sur ton **app OAuth GitHub** (étape 1) → **Edit** → mets à jour l'**Authorization callback URL** avec l'URL réelle de ton worker + `/callback`, par ex. :
   `https://portfolio-cms-auth.TON-PSEUDO.workers.dev/callback`
2. Dans le repo, ouvre [`admin/config.yml`](admin/config.yml) et remplace la ligne
   ```yaml
   base_url: https://REMPLACE-MOI.workers.dev
   ```
   par l'URL réelle de ton worker (sans `/callback` cette fois) :
   ```yaml
   base_url: https://portfolio-cms-auth.TON-PSEUDO.workers.dev
   ```
   Tu peux aussi me redonner cette URL et je fais la modification + le commit/push pour toi.

## 4. Vérifier que GitHub Pages est bien activé

Sur GitHub → ton repo `portfolio` → **Settings** → **Pages** → **Source** doit être réglé sur la branche `main`, dossier `/ (root)`.

## 5. Se connecter

Une fois tout ça publié, va sur `https://tonsite/admin/`, clique **Login with GitHub**, autorise l'app la première fois — te voilà dans l'éditeur.

**Note sécurité** : n'importe qui peut *voir* l'écran de connexion de `/admin/`,
mais seul un compte GitHub ayant un accès en écriture sur ce repo (donc toi,
ou quelqu'un que tu ajoutes comme collaborateur) peut réellement publier une
modification — GitHub lui-même applique cette restriction, pas seulement le
CMS.

## Tester en local avant de tout déployer (optionnel)

Sans rien de ce qui précède, tu peux déjà prévisualiser l'admin en local :

```bash
npx decap-server
```

puis, dans un autre terminal, sers le site (`python -m http.server 8000`
depuis la racine du repo) et ouvre `http://localhost:8000/admin/` — Decap
détecte automatiquement `localhost` et utilise ce petit serveur local au lieu
de GitHub, donc aucune des étapes ci-dessus n'est nécessaire juste pour
regarder à quoi ressemble l'éditeur.
