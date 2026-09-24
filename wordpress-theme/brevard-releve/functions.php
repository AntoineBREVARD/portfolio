<?php
/**
 * Brévard — Le Relevé
 *
 * Thème de blocs. Aucun plugin requis : l'hébergement visé n'a pas d'accès
 * Internet sortant, donc rien ne peut être installé depuis la bibliothèque
 * WordPress. Tout ce dont le thème a besoin est ici ou dans theme.json.
 *
 * @package brevard-releve
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feuille de style et script du thème.
 *
 * Le script porte les interactions du site : la typographie qui se déforme
 * au défilement, la révélation dans les lettres du nom et le comparateur
 * avant / après des fiches. Il est chargé en différé : aucune de ces
 * interactions n'est nécessaire au rendu initial.
 */
function brevard_releve_assets() {
	$theme = wp_get_theme();
	$v     = $theme->get( 'Version' );

	wp_enqueue_style(
		'brevard-releve',
		get_stylesheet_uri(),
		array(),
		$v
	);

	wp_enqueue_script(
		'brevard-releve',
		get_theme_file_uri( 'assets/releve.js' ),
		array(),
		$v,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'brevard_releve_assets' );

/**
 * La même feuille de style dans l'éditeur, pour que ce qu'on voit en
 * rédigeant corresponde à ce que verra le visiteur.
 */
function brevard_releve_editeur() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'brevard_releve_editeur' );

/**
 * Type de contenu « Réalisation ».
 *
 * Chaque mission menée en production est une réalisation. On passe par un type
 * dédié plutôt que par des articles : le portfolio n'est pas un blog, et cela
 * donne une archive propre, une URL lisible et un menu séparé dans l'admin.
 */
function brevard_releve_type_realisation() {
	register_post_type(
		'realisation',
		array(
			'labels'        => array(
				'name'               => 'Réalisations',
				'singular_name'      => 'Réalisation',
				'add_new'            => 'Ajouter une réalisation',
				'add_new_item'       => 'Nouvelle réalisation',
				'edit_item'          => 'Modifier la réalisation',
				'all_items'          => 'Toutes les réalisations',
				'search_items'       => 'Rechercher une réalisation',
				'not_found'          => 'Aucune réalisation.',
				'menu_name'          => 'Réalisations',
			),
			'public'        => true,
			'show_in_rest'  => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-analytics',
			'has_archive'   => 'realisations',
			'rewrite'       => array( 'slug' => 'realisation', 'with_front' => false ),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
			'template'      => array(
				array( 'core/pattern', array( 'slug' => 'brevard-releve/fiche-realisation' ) ),
			),
		)
	);
}
add_action( 'init', 'brevard_releve_type_realisation' );

/**
 * Catégorie de compositions propre au thème, pour que les sections du site
 * soient regroupées au lieu d'être noyées dans la bibliothèque de WordPress.
 */
function brevard_releve_categorie_compositions() {
	register_block_pattern_category(
		'brevard-releve',
		array( 'label' => 'Le Relevé' )
	);
}
add_action( 'init', 'brevard_releve_categorie_compositions' );

/**
 * Réglages du thème.
 *
 * add_theme_support( 'wp-block-styles' ) charge les styles par défaut des
 * blocs : on ne le fait PAS, le thème habille déjà tout et ces règles
 * entreraient en conflit.
 */
function brevard_releve_reglages() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'style', 'script' ) );
	remove_theme_support( 'core-block-patterns' );
	// le gabarit de page affiche le résumé sous le titre : les pages
	// doivent donc pouvoir en avoir un
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'brevard_releve_reglages' );

/**
 * Le corps porte les classes attendues par la feuille de style héritée du
 * site statique, pour ne pas avoir à réécrire 900 lignes de CSS.
 */
function brevard_releve_classes_corps( $classes ) {
	$classes[] = 'releve';
	return $classes;
}
add_filter( 'body_class', 'brevard_releve_classes_corps' );

/**
 * URL d'une page du portfolio, quelle que soit la structure des permaliens.
 *
 * L'hebergement ne permet pas toujours la reecriture d'URL : WordPress bascule
 * alors sur des adresses prefixees par /index.php/. Ecrire « /realisations/ »
 * en dur donnerait donc une 404. On demande l'adresse reelle de la page, et a
 * defaut on reconstruit le prefixe.
 */
function brevard_releve_lien( $slug ) {
	if ( 'realisations' === $slug ) {
		$archive = get_post_type_archive_link( 'realisation' );
		if ( $archive ) {
			return $archive;
		}
	}

	$pages = get_posts(
		array(
			'name'        => $slug,
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
		)
	);

	if ( $pages ) {
		return get_permalink( $pages[0] );
	}

	$structure = (string) get_option( 'permalink_structure' );
	$prefixe   = ( 0 === strpos( $structure, '/index.php' ) ) ? '/index.php' : '';

	return home_url( $prefixe . '/' . $slug . '/' );
}

/**
 * Adresse d'une fiche de realisation, retrouvee par son identifiant.
 *
 * Si la fiche n'existe pas encore, on renvoie vers la liste plutot que vers
 * une 404 : le site reste navigable pendant qu'on le remplit.
 */
function brevard_releve_fiche( $slug ) {
	$fiches = get_posts(
		array(
			'name'        => $slug,
			'post_type'   => 'realisation',
			'post_status' => 'publish',
			'numberposts' => 1,
		)
	);

	if ( $fiches ) {
		return get_permalink( $fiches[0] );
	}

	return brevard_releve_lien( 'realisations' );
}

/**
 * Type de contenu « Veille ».
 *
 * Une veille, c'est un document à télécharger : un titre, un résumé et un
 * bloc Fichier. Chaque nouvelle veille s'ouvre avec ce bloc déjà en place,
 * il ne reste qu'à y téléverser le document. Pas de page par veille : la
 * page « Veilles » les liste et renvoie directement au fichier.
 */
function brevard_releve_type_veille() {
	register_post_type(
		'veille',
		array(
			'labels'              => array(
				'name'          => 'Veilles',
				'singular_name' => 'Veille',
				'add_new'       => 'Ajouter une veille',
				'add_new_item'  => 'Nouvelle veille',
				'edit_item'     => 'Modifier la veille',
				'all_items'     => 'Toutes les veilles',
				'not_found'     => 'Aucune veille.',
				'menu_name'     => 'Veilles',
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-media-document',
			'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
			'template'            => array(
				array( 'core/file', array( 'displayPreview' => false ) ),
			),
		)
	);
}
add_action( 'init', 'brevard_releve_type_veille' );

/**
 * Adresse du premier fichier d'un contenu : celle du premier bloc Fichier,
 * sinon celle du premier document joint depuis la médiathèque.
 */
function brevard_releve_fichier( $post ) {
	$pile = parse_blocks( $post->post_content );
	while ( $pile ) {
		$bloc = array_shift( $pile );
		if ( 'core/file' === $bloc['blockName'] && ! empty( $bloc['attrs']['href'] ) ) {
			return $bloc['attrs']['href'];
		}
		if ( ! empty( $bloc['innerBlocks'] ) ) {
			$pile = array_merge( $bloc['innerBlocks'], $pile );
		}
	}

	$joints = get_attached_media( '', $post->ID );
	if ( $joints ) {
		return wp_get_attachment_url( reset( $joints )->ID );
	}

	return '';
}

/**
 * Liste des veilles, la plus récente en tête.
 *
 * Un gabarit de page ne sait pas lire l'adresse d'un fichier enfoui dans le
 * contenu d'une veille ; un bloc dynamique, évalué à chaque affichage, le
 * peut. Pas de code court : son bloc passe la sortie à wpautop, qui insère
 * des paragraphes au milieu des lignes et casse leur grille.
 * Le rendu reprend le journal des réalisations.
 */
function brevard_releve_liste_veilles() {
	$veilles = get_posts(
		array(
			'post_type'   => 'veille',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);

	$lignes = array();
	foreach ( $veilles as $veille ) {
		$fichier = brevard_releve_fichier( $veille );
		if ( ! $fichier ) {
			continue;
		}
		$format = strtoupper( (string) pathinfo( (string) wp_parse_url( $fichier, PHP_URL_PATH ), PATHINFO_EXTENSION ) );

		$lignes[] = sprintf(
			'<a class="entree" href="%1$s" download><span class="entree-num">%2$s</span><h3 class="entree-titre">%3$s</h3><p class="entree-mot">%4$s</p><span class="entree-comp"><span class="puce">%5$s</span><span class="puce">%6$s</span></span><span class="entree-fleche" aria-hidden="true">↓</span></a>',
			esc_url( $fichier ),
			esc_html( str_pad( (string) ( count( $lignes ) + 1 ), 2, '0', STR_PAD_LEFT ) ),
			esc_html( get_the_title( $veille ) ),
			esc_html( has_excerpt( $veille ) ? get_the_excerpt( $veille ) : '' ),
			esc_html( $format ? $format : 'Fichier' ),
			esc_html( get_the_date( 'j F Y', $veille ) )
		);
	}

	if ( ! $lignes ) {
		return '<p class="vide">Aucune veille déposée pour l\'instant.</p>';
	}

	return '<div class="journal">' . implode( '', $lignes ) . '</div>';
}
function brevard_releve_bloc_veilles() {
	register_block_type(
		'brevard-releve/veilles',
		array(
			'title'           => 'Liste des veilles',
			'category'        => 'theme',
			'render_callback' => 'brevard_releve_liste_veilles',
		)
	);
}
add_action( 'init', 'brevard_releve_bloc_veilles' );

/**
 * Version du contenu livré avec le thème. L'augmenter relance
 * l'installation, qui ne crée que ce qui manque.
 */
const BREVARD_RELEVE_CONTENU = '5';

/**
 * Le contenu livré avec le thème : pour chaque identifiant, le titre, le
 * résumé et la composition qui en fournit le corps.
 */
function brevard_releve_noms() {
	return array(
		'veilles'            => 'Veilles',
		'grille-competences' => 'Grille de compétences',
		'profil'             => 'Profil',
		'entreprise'         => 'Entreprise',
		'a-propos'           => 'À propos',
	);
}

function brevard_releve_contenus( $type ) {
	$contenus = array(
		'veilles'            => array( 'Ce que je surveille', 'Mes veilles technologiques, rédigées au fil de la formation. Chaque document se télécharge d\'un clic.', 'page-veilles' ),
		'grille-competences' => array( 'La grille de compétences', 'Le tableau de synthèse du référentiel BTS SIO option SISR, rempli à partir des réalisations.', 'page-grille' ),
		'profil'             => array( 'Une PME onze mois par an, une multinationale le douzième', '', 'page-profil' ),
		'entreprise'         => array( 'L\'Automobile Club de l\'Ouest', 'L\'organisateur des 24 Heures du Mans, où j\'effectue mon alternance au service informatique depuis septembre 2025.', 'page-entreprise' ),
		'a-propos'           => array( 'Mentions légales', 'Qui édite ce site, qui l\'héberge, et ce qu\'il fait de vos données : rien.', 'page-apropos' ),
	);
	$realisations = array(
		'contacts' => array( 'Microsoft allait fermer la porte', 'Tout le carnet d\'adresses de l\'entreprise reposait sur une fonctionnalité qu\'Outlook s\'apprêtait à abandonner. Aucun remplacement gratuit sur le marché.' ),
		'quotas'   => array( 'On l\'apprenait toujours trop tard', 'Une boîte pleine, c\'est un collaborateur qui ne reçoit plus rien. Et le service informatique qui le découvre quand il appelle.' ),
		'teams'    => array( 'Une image de marque, 300 écrans', 'La communication voulait le même arrière-plan pour tout le monde en visioconférence. Microsoft vendait la fonctionnalité. Nous ne l\'avons pas achetée.' ),
		'maj'      => array( 'Poste par poste, à la main', 'Deux constructeurs, deux outils, des dizaines de modèles — et des pilotes qu\'on ne mettait à jour qu\'une fois le problème arrivé.' ),
	);

	// les veilles livrées avec le thème : titre, résumé, fichier dans
	// assets/veilles/ et date de rédaction
	$veilles = array(
		'reactiv' => array( 'REACTIV, la riposte de l\'État aux fuites de données', 'Le nouveau dispositif qui donne à l\'ANSSI un pouvoir directif sur les ministères touchés par une cyberattaque, après France Titres, l\'Éducation nationale et la DGFiP.', 'REACTIV_Antoine.pdf', '2026-09-18' ),
		'gpmi'    => array( 'Le câble GPMI, la réponse chinoise à HDMI et USB-C', 'Une connectique unique portée par SUCA : jusqu\'à 192 Gbit/s, 480 W d\'alimentation et le contrôle bidirectionnel de plusieurs périphériques.', 'Cable_GPMI_Antoine.pdf', '2025-11-20' ),
	);

	if ( 'veille' === $type ) {
		return $veilles;
	}
	if ( 'realisation' !== $type ) {
		return $contenus;
	}
	$fiches = array();
	foreach ( $realisations as $slug => $fiche ) {
		$fiches[ $slug ] = array( $fiche[0], $fiche[1], 'contenu-' . $slug );
	}
	return $fiches;
}

/**
 * Crée une veille livrée avec le thème : son PDF est copié dans la
 * médiathèque, puis la veille le propose dans un bloc Fichier, exactement
 * comme si on l'avait déposée à la main. Elle se modifie ou se supprime
 * ensuite comme n'importe quelle autre.
 */
function brevard_releve_importer_veille( $slug, $veille ) {
	$source = get_theme_file_path( 'assets/veilles/' . $veille[2] );
	if ( ! is_readable( $source ) ) {
		return;
	}

	$depot = wp_upload_bits( $veille[2], null, file_get_contents( $source ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $depot['error'] ) ) {
		return;
	}

	$date = $veille[3] . ' 12:00:00';
	$id   = wp_insert_post(
		array(
			'post_type'    => 'veille',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $veille[0],
			'post_excerpt' => $veille[1],
			'post_date'    => $date,
		)
	);
	if ( ! $id || is_wp_error( $id ) ) {
		return;
	}

	$piece = wp_insert_attachment(
		array(
			'post_title'     => $veille[0],
			'post_mime_type' => 'application/pdf',
			'post_status'    => 'inherit',
		),
		$depot['file'],
		$id
	);

	$url = $depot['url'];
	wp_update_post(
		array(
			'ID'           => $id,
			'post_content' => sprintf(
				'<!-- wp:file {"id":%1$d,"href":"%2$s"} --><div class="wp-block-file"><a href="%2$s">%3$s</a><a href="%2$s" class="wp-block-file__button wp-element-button" download>Télécharger</a></div><!-- /wp:file -->',
				(int) $piece,
				esc_url( $url ),
				esc_html( $veille[0] )
			),
		)
	);
}

/**
 * Remplit le site au premier passage dans l'admin.
 *
 * Le thème est déployé par WP Pusher depuis GitHub : il doit arriver avec
 * tout le site, pas seulement avec son dessin. On crée donc les pages, les
 * quatre réalisations et les veilles qui manquent. Ce qui existe déjà n'est jamais touché,
 * et une page mise à la corbeille n'est pas recréée : c'est un choix.
 *
 * Le contenu créé est une référence à une composition du thème, évaluée à
 * l'affichage : les liens restent justes quelle que soit l'adresse du site.
 * Dès qu'on modifie une page dans l'éditeur, elle devient un contenu
 * ordinaire.
 */
function brevard_releve_installer() {
	if ( get_option( 'brevard_releve_contenu' ) === BREVARD_RELEVE_CONTENU ) {
		return;
	}
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	$existe = function ( $slug, $type ) {
		return (bool) get_posts(
			array(
				'name'        => $slug,
				'post_type'   => $type,
				'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
	};

	$pages = brevard_releve_contenus( 'page' );
	foreach ( $pages as $slug => $page ) {
		if ( $existe( $slug, 'page' ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $page[0],
				'post_excerpt' => $page[1],
				'post_content' => '<!-- wp:pattern {"slug":"brevard-releve/' . $page[2] . '"} /-->',
			)
		);
	}

	$fiches = brevard_releve_contenus( 'realisation' );
	$ordre = 0;
	foreach ( $fiches as $slug => $fiche ) {
		++$ordre;
		if ( $existe( $slug, 'realisation' ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'realisation',
				'post_status'  => 'publish',
				'post_name'    => $slug,
				'post_title'   => $fiche[0],
				'post_excerpt' => $fiche[1],
				'menu_order'   => $ordre,
				'post_content' => '<!-- wp:pattern {"slug":"brevard-releve/' . $fiche[2] . '"} /-->',
			)
		);
	}

	foreach ( brevard_releve_contenus( 'veille' ) as $slug => $veille ) {
		if ( ! $existe( $slug, 'veille' ) ) {
			brevard_releve_importer_veille( $slug, $veille );
		}
	}

	// Pages retirées du portfolio : Compétences (remplacée par la grille) et
	// Jury (ses documents sont sur l'accueil, son contact en pied de page).
	// Elles passent en brouillon plutôt qu'à la corbeille : rien n'est perdu.
	foreach ( array( 'competences', 'jury' ) as $retiree ) {
		$ancienne = get_posts(
			array(
				'name'        => $retiree,
				'post_type'   => 'page',
				'post_status' => 'publish',
				'numberposts' => 1,
			)
		);
		if ( $ancienne ) {
			wp_update_post( array( 'ID' => $ancienne[0]->ID, 'post_status' => 'draft' ) );
		}
	}

	// les adresses des réalisations n'existent qu'après ce recalcul :
	// c'était l'étape « Réglages → Permaliens → Enregistrer »
	flush_rewrite_rules();

	update_option( 'brevard_releve_contenu', BREVARD_RELEVE_CONTENU );
}
add_action( 'admin_init', 'brevard_releve_installer' );


/**
 * Remise à neuf : Apparence → Remettre à neuf.
 *
 * Ce qu'on modifie dans l'éditeur de site n'est pas écrit dans le thème mais
 * dans la base, rattaché à l'identifiant du thème. Remplacer le thème par une
 * nouvelle version ne l'efface donc pas : les anciennes modifications
 * reviennent par-dessus. Cette page efface ces personnalisations et remet les
 * pages du portfolio dans leur état d'origine, sans avoir à chercher les
 * boutons « Réinitialiser » modèle par modèle.
 */
function brevard_releve_menu_neuf() {
	add_theme_page(
		'Portfolio : état du site et remise à neuf',
		'Portfolio',
		'edit_theme_options',
		'brevard-releve-neuf',
		'brevard_releve_page_neuf'
	);
}
add_action( 'admin_menu', 'brevard_releve_menu_neuf' );

function brevard_releve_page_neuf() {
	$fait = isset( $_GET['fait'] ) ? sanitize_key( wp_unslash( $_GET['fait'] ) ) : '';
	?>
	<div class="wrap">
		<h1>Portfolio : état du site et remise à neuf</h1>
		<?php if ( 'complete' === $fait ) : ?>
			<div class="notice notice-success"><p>Le site est complet. Rechargez-le avec Ctrl+F5.</p></div>
		<?php endif; ?>
		<?php if ( 'oui' === $fait ) : ?>
			<div class="notice notice-success"><p>C'est fait. Rechargez le site avec Ctrl+F5 pour voir le résultat.</p></div>
		<?php endif; ?>
		<h2>État du site</h2>
		<?php brevard_releve_etat(); ?>
		<h2>Remettre à neuf</h2>
		<p>Les modifications faites dans <strong>Apparence → Éditeur</strong> sont enregistrées à part du thème : elles restent appliquées même après avoir installé une nouvelle version. Cochez ce qu'il faut remettre dans l'état livré par le thème.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="brevard_releve_neuf">
			<?php wp_nonce_field( 'brevard_releve_neuf' ); ?>
			<p><label><input type="checkbox" name="neuf[]" value="modeles" checked> <strong>Modèles et parties de modèle</strong> — le menu, l'en-tête, le pied de page et la mise en page de chaque type de page.</label></p>
			<p><label><input type="checkbox" name="neuf[]" value="styles" checked> <strong>Styles</strong> — les couleurs et polices changées dans l'éditeur.</label></p>
			<p><label><input type="checkbox" name="neuf[]" value="pages" checked> <strong>Pages du portfolio</strong> — Veilles, Grille de compétences, Profil et Jury retrouvent leur texte d'origine. La grille déposée devra être redéposée.</label></p>
			<p><label><input type="checkbox" name="neuf[]" value="realisations"> <strong>Les quatre réalisations</strong> — leur texte est remplacé par celui du site statique. À ne cocher que si vous n'y avez rien écrit vous-même.</label></p>
			<p>Les veilles, la médiathèque et les autres pages ne sont jamais touchées.</p>
			<?php submit_button( 'Remettre à neuf', 'primary', 'submit', true, array( 'onclick' => "return confirm('Les modifications cochées seront effacées. Continuer ?');" ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Remet une page ou une réalisation dans son état d'origine, en la créant
 * ou en la sortant de la corbeille au besoin.
 */
function brevard_releve_remettre( $type, $slug, $contenu ) {
	$donnees = array(
		'post_type'    => $type,
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $contenu[0],
		'post_excerpt' => $contenu[1],
		'post_content' => '<!-- wp:pattern {"slug":"brevard-releve/' . $contenu[2] . '"} /-->',
	);

	$existant = get_posts(
		array(
			'name'        => $slug,
			'post_type'   => $type,
			'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ),
			'numberposts' => 1,
		)
	);
	// une page à la corbeille a vu son identifiant suffixé par WordPress
	if ( ! $existant ) {
		$existant = get_posts(
			array(
				'name'        => $slug . '__trashed',
				'post_type'   => $type,
				'post_status' => 'trash',
				'numberposts' => 1,
			)
		);
	}

	if ( $existant ) {
		$donnees['ID'] = $existant[0]->ID;
		wp_update_post( $donnees );
	} else {
		wp_insert_post( $donnees );
	}
}

function brevard_releve_faire_neuf() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( 'Action non autorisée.' );
	}
	check_admin_referer( 'brevard_releve_neuf' );

	$choix = isset( $_POST['neuf'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['neuf'] ) ) : array();

	if ( in_array( 'modeles', $choix, true ) ) {
		$personnalisations = get_posts(
			array(
				'post_type'   => array( 'wp_template', 'wp_template_part' ),
				'post_status' => 'any',
				'numberposts' => -1,
				'fields'      => 'ids',
				'tax_query'   => array(
					array(
						'taxonomy' => 'wp_theme',
						'field'    => 'name',
						'terms'    => get_stylesheet(),
					),
				),
			)
		);
		foreach ( $personnalisations as $id ) {
			wp_delete_post( $id, true );
		}
	}

	if ( in_array( 'styles', $choix, true ) && class_exists( 'WP_Theme_JSON_Resolver' ) ) {
		$styles = WP_Theme_JSON_Resolver::get_user_global_styles_post_id();
		if ( $styles ) {
			wp_delete_post( $styles, true );
		}
	}

	if ( in_array( 'pages', $choix, true ) ) {
		foreach ( brevard_releve_contenus( 'page' ) as $slug => $contenu ) {
			brevard_releve_remettre( 'page', $slug, $contenu );
		}
	}

	if ( in_array( 'realisations', $choix, true ) ) {
		$ordre = 0;
		foreach ( brevard_releve_contenus( 'realisation' ) as $slug => $contenu ) {
			brevard_releve_remettre( 'realisation', $slug, $contenu );
			$fiche = get_posts( array( 'name' => $slug, 'post_type' => 'realisation', 'numberposts' => 1, 'fields' => 'ids' ) );
			if ( $fiche ) {
				wp_update_post( array( 'ID' => $fiche[0], 'menu_order' => ++$ordre ) );
			}
		}
	}

	update_option( 'brevard_releve_neuf_fait', 1 );
	flush_rewrite_rules();

	wp_safe_redirect( admin_url( 'themes.php?page=brevard-releve-neuf&fait=oui' ) );
	exit;
}
add_action( 'admin_post_brevard_releve_neuf', 'brevard_releve_faire_neuf' );

/**
 * Tant que la remise à neuf n'a jamais servi et qu'il reste d'anciennes
 * personnalisations, un bandeau la signale : c'est justement quand on ne
 * sait pas où chercher qu'on en a besoin.
 */
function brevard_releve_bandeau_neuf() {
	if ( get_option( 'brevard_releve_neuf_fait' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$ecran = get_current_screen();
	if ( $ecran && 'appearance_page_brevard-releve-neuf' === $ecran->id ) {
		return;
	}
	$reste = get_posts(
		array(
			'post_type'   => array( 'wp_template', 'wp_template_part' ),
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
			'tax_query'   => array(
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'name',
					'terms'    => get_stylesheet(),
				),
			),
		)
	);
	if ( ! $reste ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p><strong>Brévard — Le Relevé :</strong> d\'anciennes modifications de l\'éditeur masquent la nouvelle version du thème. <a href="%s">Apparence → Portfolio</a></p></div>',
		esc_url( admin_url( 'themes.php?page=brevard-releve-neuf' ) )
	);
}
add_action( 'admin_notices', 'brevard_releve_bandeau_neuf' );

/**
 * Dans la liste des pages, chaque page du portfolio porte le nom de sa
 * rubrique : son titre est la phrase affichée en grand (« Ce que je
 * surveille », « Mentions légales »), où l'on ne reconnaît pas la rubrique.
 */
function brevard_releve_etiquette_page( $etats, $post ) {
	if ( 'page' !== $post->post_type ) {
		return $etats;
	}
	$noms = brevard_releve_noms();
	$slug = preg_replace( '/__trashed$/', '', $post->post_name );
	if ( isset( $noms[ $slug ] ) ) {
		$etats[ 'brevard-releve-' . $slug ] = 'Portfolio — ' . $noms[ $slug ];
	}
	return $etats;
}
add_filter( 'display_post_states', 'brevard_releve_etiquette_page', 10, 2 );

/**
 * Tableau de l'état du site : ce que le thème attend, ce qui existe, et le
 * lien pour le voir. Accueil et Réalisations y figurent aussi, précisément
 * parce qu'on les cherche en vain dans la liste des pages.
 */
function brevard_releve_etat() {
	$lignes = array(
		array( 'Accueil', 'Automatique : gabarit du thème, pas de page à créer.', 'ok', home_url( '/' ) ),
	);

	$fiches = get_posts( array( 'post_type' => 'realisation', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
	$lignes[] = array(
		'Réalisations',
		sprintf( 'Automatique : liste des fiches publiées (%d sur 4 attendues). Menu Réalisations.', count( $fiches ) ),
		count( $fiches ) >= 4 ? 'ok' : 'manque',
		brevard_releve_lien( 'realisations' ),
	);

	foreach ( brevard_releve_noms() as $slug => $nom ) {
		$page = get_posts( array( 'name' => $slug, 'post_type' => 'page', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'numberposts' => 1 ) );
		if ( $page && 'publish' === $page[0]->post_status ) {
			$lignes[] = array( $nom, sprintf( 'Page « %s ».', get_the_title( $page[0] ) ), 'ok', get_permalink( $page[0] ) );
		} elseif ( $page ) {
			$lignes[] = array( $nom, 'La page existe mais n\'est pas publiée.', 'manque', get_edit_post_link( $page[0]->ID ) );
		} else {
			$corbeille = get_posts( array( 'name' => $slug . '__trashed', 'post_type' => 'page', 'post_status' => 'trash', 'numberposts' => 1 ) );
			$lignes[] = array( $nom, $corbeille ? 'À la corbeille.' : 'Absente.', 'manque', '' );
		}
	}

	$veilles  = get_posts( array( 'post_type' => 'veille', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) );
	$lignes[] = array( 'Veilles déposées', sprintf( '%d publiée(s). Menu Veilles.', count( $veilles ) ), $veilles ? 'ok' : 'manque', admin_url( 'edit.php?post_type=veille' ) );

	$manque = false;
	echo '<table class="widefat striped" style="max-width:900px"><tbody>';
	foreach ( $lignes as $l ) {
		$manque = $manque || 'manque' === $l[2];
		printf(
			'<tr><td style="width:24px">%1$s</td><td><strong>%2$s</strong></td><td>%3$s</td><td>%4$s</td></tr>',
			'ok' === $l[2] ? '✅' : '⚠️',
			esc_html( $l[0] ),
			esc_html( $l[1] ),
			$l[3] ? '<a href="' . esc_url( $l[3] ) . '">Voir</a>' : ''
		);
	}
	echo '</tbody></table>';

	if ( $manque ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		echo '<input type="hidden" name="action" value="brevard_releve_completer">';
		wp_nonce_field( 'brevard_releve_completer' );
		echo '<p><strong>Il manque quelque chose.</strong> Ce bouton crée ce qui est absent et restaure ce qui est à la corbeille, sans toucher à ce qui existe déjà.</p>';
		submit_button( 'Créer ce qui manque', 'primary', 'submit', false );
		echo '</form>';
	}
}

/**
 * « Créer ce qui manque » : pages, réalisations et veilles absentes ou à la
 * corbeille. L'installation automatique, elle, laisse la corbeille en paix :
 * ici, c'est une demande explicite.
 */
function brevard_releve_completer() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( 'Action non autorisée.' );
	}
	check_admin_referer( 'brevard_releve_completer' );

	$present = function ( $slug, $type ) {
		return (bool) get_posts(
			array(
				'name'        => $slug,
				'post_type'   => $type,
				'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
	};

	foreach ( array( 'page', 'realisation' ) as $type ) {
		$ordre = 0;
		foreach ( brevard_releve_contenus( $type ) as $slug => $contenu ) {
			++$ordre;
			if ( $present( $slug, $type ) ) {
				continue;
			}
			brevard_releve_remettre( $type, $slug, $contenu );
			if ( 'realisation' === $type ) {
				$fiche = get_posts( array( 'name' => $slug, 'post_type' => 'realisation', 'numberposts' => 1, 'fields' => 'ids' ) );
				if ( $fiche ) {
					wp_update_post( array( 'ID' => $fiche[0], 'menu_order' => $ordre ) );
				}
			}
		}
	}

	foreach ( brevard_releve_contenus( 'veille' ) as $slug => $veille ) {
		if ( ! $present( $slug, 'veille' ) ) {
			brevard_releve_importer_veille( $slug, $veille );
		}
	}

	flush_rewrite_rules();
	wp_safe_redirect( admin_url( 'themes.php?page=brevard-releve-neuf&fait=complete' ) );
	exit;
}
add_action( 'admin_post_brevard_releve_completer', 'brevard_releve_completer' );
