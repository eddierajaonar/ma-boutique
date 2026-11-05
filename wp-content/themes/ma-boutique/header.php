<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Liens de la navbar (pages WooCommerce si l'extension est active, sinon l'accueil).
$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();

$menu = array(
	'Accueil'            => home_url( '/' ),
	'Shop'         => $shop_url,
	'Meilleures ventes'  => add_query_arg( 'orderby', 'popularity', $shop_url ),
);
?>

<header class="shadow-sm">
	<div class="bg-orange-400 py-2 text-center">
			<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum vero non excepturi sequi perferendis quas quis, </p>
		</div>
	<!-- Conteneur : largeur max + marges à gauche et à droite -->
	<div class="bg-gray-200 navbar max-w-8xl mx-auto px-4 sm:px-6">
		
		<!-- Gauche : menu mobile + logo + liens -->
		<div class="navbar-start gap-2">

			<!-- Menu mobile (bouton "hamburger"), caché sur grand écran -->
			<div class="dropdown lg:hidden">
				<div tabindex="0" role="button" class="btn btn-ghost btn-square" aria-label="Menu">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
				</div>
				<ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-10 mt-3 w-52 p-2 shadow">
					<?php foreach ( $menu as $label => $url ) : ?>
						<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Logo -->
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center">
				<?php if ( has_custom_logo() ) : ?>
					<?php $logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ); ?>
					<img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-12 w-auto">
				<?php else : ?>
					<span class="text-xl font-bold"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>

			<!-- Liens, visibles sur grand écran -->
			<ul class="menu menu-horizontal hidden lg:flex px-1">
				<?php foreach ( $menu as $label => $url ) : ?>
					<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<!-- Droite : bouton -->
		<div class="navbar-end">
			<a href="<?php echo esc_url( $account_url ); ?>" class="btn btn-primary">Stay connected</a>
		</div>

	</div>
</header>

<main class="max-w-8xl mx-auto px-4 sm:px-6">
