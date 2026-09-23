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
 * Le script porte les trois interactions du site : la typographie qui se
 * déforme au défilement, la révélation dans les lettres du nom, et la matrice
 * de compétences qui se lit dans les deux sens. Il est chargé en différé :
 * aucune de ces interactions n'est nécessaire au rendu initial.
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
