/*
 * Petit serveur d'authentification GitHub pour Decap CMS (admin/config.yml).
 *
 * Decap CMS ne peut pas contacter GitHub tout seul depuis le navigateur :
 * l'échange du "code" contre un vrai jeton d'accès doit se faire côté
 * serveur, car il exige le CLIENT_SECRET de l'app OAuth (qui ne doit
 * jamais être visible depuis le navigateur/le repo). Ce worker fait
 * exactement ça, et rien d'autre.
 *
 * Déploiement : voir SETUP-CMS.md à la racine du repo.
 * Variables d'environnement à définir dans Cloudflare (Settings → Variables) :
 *   - GITHUB_CLIENT_ID      (visible, pas besoin de chiffrer)
 *   - GITHUB_CLIENT_SECRET  (à chiffrer / "Encrypt")
 */

const GITHUB_AUTHORIZE_URL = "https://github.com/login/oauth/authorize";
const GITHUB_TOKEN_URL = "https://github.com/login/oauth/access_token";

function html(body, status = 200){
  return new Response(body, {
    status,
    headers: { "Content-Type": "text/html; charset=utf-8" }
  });
}

export default {
  async fetch(request, env){
    const url = new URL(request.url);

    // Étape 1 : le CMS ouvre une popup sur /auth, on redirige vers GitHub.
    if (url.pathname === "/auth"){
      const redirectUri = `${url.origin}/callback`;
      const authorizeUrl = new URL(GITHUB_AUTHORIZE_URL);
      authorizeUrl.searchParams.set("client_id", env.GITHUB_CLIENT_ID);
      authorizeUrl.searchParams.set("redirect_uri", redirectUri);
      authorizeUrl.searchParams.set("scope", "repo,user");
      authorizeUrl.searchParams.set("state", "github");
      return Response.redirect(authorizeUrl.toString(), 302);
    }

    // Étape 2 : GitHub redirige ici avec un "code" à usage unique.
    if (url.pathname === "/callback"){
      const code = url.searchParams.get("code");
      const error = url.searchParams.get("error");

      if (error){
        return html(`<p>Connexion refusée par GitHub : ${error}</p>`, 400);
      }
      if (!code){
        return html("<p>Paramètre 'code' manquant.</p>", 400);
      }

      const tokenRes = await fetch(GITHUB_TOKEN_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          client_id: env.GITHUB_CLIENT_ID,
          client_secret: env.GITHUB_CLIENT_SECRET,
          code
        })
      });
      const tokenData = await tokenRes.json();

      if (!tokenRes.ok || tokenData.error){
        return html(`<p>Échec de l'échange du code : ${tokenData.error_description || tokenData.error || tokenRes.status}</p>`, 400);
      }

      const token = tokenData.access_token;

      // Poignée de main attendue par Decap CMS : la popup prévient d'abord
      // la fenêtre d'origine qu'elle est prête, puis, quand celle-ci répond,
      // lui envoie le jeton. C'est ce protocole précis (et pas un simple
      // postMessage immédiat) que Decap écoute côté client.
      return html(`<!DOCTYPE html><html><body>
<script>
(function(){
  function receiveMessage(e){
    window.opener.postMessage(
      'authorization:github:success:' + JSON.stringify({ token: '${token}', provider: 'github' }),
      e.origin
    );
    window.removeEventListener('message', receiveMessage, false);
  }
  window.addEventListener('message', receiveMessage, false);
  window.opener.postMessage('authorizing:github', '*');
})();
</script>
</body></html>`);
    }

    return html("<p>Serveur d'authentification Decap CMS. Rien à voir ici directement — passe par /auth depuis l'admin du site.</p>");
  }
};
