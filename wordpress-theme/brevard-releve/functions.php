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
const BREVARD_RELEVE_CONTENU = '2';

/**
 * Remplit le site au premier passage dans l'admin.
 *
 * Le thème est déployé par WP Pusher depuis GitHub : il doit arriver avec
 * tout le site, pas seulement avec son dessin. On crée donc les pages et les
 * quatre réalisations qui manquent. Ce qui existe déjà n'est jamais touché,
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

	$pages = array(
		'veilles'            => array( 'Ce que je surveille', 'Mes veilles technologiques, rédigées au fil de la formation. Chaque document se télécharge d\'un clic.', 'page-veilles' ),
		'grille-competences' => array( 'La grille de compétences', 'Le tableau de synthèse du référentiel BTS SIO option SISR, rempli à partir des réalisations.', 'page-grille' ),
		'profil'             => array( 'Une PME onze mois par an, une multinationale le douzième', '', 'page-profil' ),
		'jury'               => array( 'Accès direct', 'Si vous évaluez ce portfolio, voici les entrées utiles — sans avoir à parcourir le site.', 'page-jury' ),
	);
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

	$fiches = array(
		'contacts' => array( 'Microsoft allait fermer la porte', 'Tout le carnet d\'adresses de l\'entreprise reposait sur une fonctionnalité qu\'Outlook s\'apprêtait à abandonner. Aucun remplacement gratuit sur le marché.' ),
		'quotas'   => array( 'On l\'apprenait toujours trop tard', 'Une boîte pleine, c\'est un collaborateur qui ne reçoit plus rien. Et le service informatique qui le découvre quand il appelle.' ),
		'teams'    => array( 'Une image de marque, 300 écrans', 'La communication voulait le même arrière-plan pour tout le monde en visioconférence. Microsoft vendait la fonctionnalité. Nous ne l\'avons pas achetée.' ),
		'maj'      => array( 'Poste par poste, à la main', 'Deux constructeurs, deux outils, des dizaines de modèles — et des pilotes qu\'on ne mettait à jour qu\'une fois le problème arrivé.' ),
	);
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
				'post_content' => '<!-- wp:pattern {"slug":"brevard-releve/contenu-' . $slug . '"} /-->',
			)
		);
	}

	// L'ancienne page Compétences (la matrice) est remplacée par la grille.
	// Elle passe en brouillon plutôt qu'à la corbeille : rien n'est perdu.
	$ancienne = get_posts(
		array(
			'name'        => 'competences',
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
		)
	);
	if ( $ancienne ) {
		wp_update_post( array( 'ID' => $ancienne[0]->ID, 'post_status' => 'draft' ) );
	}

	// les adresses des réalisations n'existent qu'après ce recalcul :
	// c'était l'étape « Réglages → Permaliens → Enregistrer »
	flush_rewrite_rules();

	update_option( 'brevard_releve_contenu', BREVARD_RELEVE_CONTENU );
}
add_action( 'admin_init', 'brevard_releve_installer' );

