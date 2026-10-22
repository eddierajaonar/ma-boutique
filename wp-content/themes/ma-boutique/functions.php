<?php
/**
 * Fonctions du thème Ma Boutique.
 */

// Réglages du thème.
function ma_boutique_setup() {
	add_theme_support( 'title-tag' );       // WordPress gère la balise <title>.
	add_theme_support( 'post-thumbnails' ); // Images à la une (et images produits).
	add_theme_support( 'woocommerce' );     // Déclare que le thème supporte WooCommerce.
	add_theme_support( 'custom-header' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'custom-logo', array(
                'height'               => 200,
                'width'                => 200,
                'flex-height'          => true,
                'flex-width'           => true,
                'header-text'          => array( 'site-title', 'site-description' ),
                'unlink-homepage-logo' => true,
                ) );
}
add_action( 'after_setup_theme', 'ma_boutique_setup' );

/**
 * Version d'un fichier du thème = sa date de modification.
 * La version change à chaque modification/compilation, donc le navigateur ne garde pas l'ancien fichier en cache.
 * Si le fichier n'existe pas (npm install pas encore lancé), on évite une erreur PHP.
 */
function ma_boutique_version( $file ) {
	$path = get_template_directory() . '/' . $file;
	return file_exists( $path ) ? filemtime( $path ) : null;
}

// Chargement du CSS et du JS.
function ma_boutique_scripts() {
	$uri = get_template_directory_uri();

	// --- CSS ---

	// Tailwind compilé (npm run build / npm run watch).
	wp_enqueue_style( 'ma-boutique-tailwind', $uri . '/assets/css/main.css', array(), ma_boutique_version( 'assets/css/main.css' ) );

	// style.css chargé après Tailwind : mes règles perso gagnent à spécificité égale.
	wp_enqueue_style( 'ma-boutique-style', get_stylesheet_uri(), array( 'ma-boutique-tailwind' ), wp_get_theme()->get( 'Version' ) );

	// --- JS : librairies (copiées depuis node_modules par npm run vendor) ---
	// wp_register_script = "je déclare le fichier, mais je ne le charge pas encore".
	// Il sera chargé automatiquement si un autre script en a besoin (dépendance).
	$footer = array( 'in_footer' => true );

	wp_register_script( 'gsap', $uri . '/assets/js/vendor/gsap.min.js', array(), ma_boutique_version( 'assets/js/vendor/gsap.min.js' ), $footer );
	wp_register_script( 'gsap-scrolltrigger', $uri . '/assets/js/vendor/ScrollTrigger.min.js', array( 'gsap' ), ma_boutique_version( 'assets/js/vendor/ScrollTrigger.min.js' ), $footer );

	// --- JS : mes animations, une par type de page ---

	// Accueil (front-page.php).
	if ( is_front_page() ) {
		wp_enqueue_script( 'ma-boutique-anim-home', $uri . '/assets/js/animations-home.js', array( 'gsap' ), ma_boutique_version( 'assets/js/animations-home.js' ), $footer );
	}

	// Boutique (/shop/) et pages de catégories / étiquettes de produits.
	// function_exists : is_shop() vient de WooCommerce, sans lui le site planterait.
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script( 'ma-boutique-anim-shop', $uri . '/assets/js/animations-shop.js', array( 'gsap', 'gsap-scrolltrigger' ), ma_boutique_version( 'assets/js/animations-shop.js' ), $footer );
	}
}
add_action( 'wp_enqueue_scripts', 'ma_boutique_scripts' );
