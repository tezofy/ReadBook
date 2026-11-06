<?php
/**
 * Nocturne header.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php /* The gold gradient used by the inline brand mark. */ ?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
	<defs>
		<linearGradient id="vl-mark-gold" x1="0" y1="0" x2="0" y2="1">
			<stop offset="0" stop-color="#EBD3A0"/>
			<stop offset="0.5" stop-color="#C9A86A"/>
			<stop offset="1" stop-color="#9E7B41"/>
		</linearGradient>
	</defs>
</svg>

<a class="vl-sr" href="#content"><?php esc_html_e( 'Skip to content', 'nocturne' ); ?></a>

<header class="vl-header" id="header" data-sticky-start="10" data-sticky-height="70">
	<div class="vl-header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vl-brand" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — <?php esc_attr_e( 'home', 'nocturne' ); ?>">
			<span class="vl-brand__crest">
			<img src="<?php echo esc_url( NOCTURNE_URI . '/assets/img/client/logo-ci-transparent.png' ); ?>" alt="">
		</span>
			<span class="vl-brand__text">
				<span class="vl-brand__name"><?php bloginfo( 'name' ); ?></span>
				<span class="vl-brand__tag"><?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?></span>
			</span>
		</a>

		<!-- Trust / shipping badge -->
		<a class="vl-header__badge" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M1 3h13v13H1z"/><path d="M14 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="17.5" cy="18.5" r="2"/>
			</svg>
			<span><?php esc_html_e( 'Worldwide delivery', 'nocturne' ); ?></span>
		</a>

		<nav class="vl-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'nocturne' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 2,
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<a class="vl-nav__link" href="<?php echo esc_url( home_url( '/books/' ) ); ?>"><?php esc_html_e( 'Books', 'nocturne' ); ?></a>
				<a class="vl-nav__link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'nocturne' ); ?></a>
				<a class="vl-nav__link" href="<?php echo esc_url( home_url( '/journal/' ) ); ?>"><?php esc_html_e( 'Journal', 'nocturne' ); ?></a>
				<a class="vl-nav__link" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'nocturne' ); ?></a>
				<a class="vl-nav__link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'nocturne' ); ?></a>
				<?php
			}
			?>
		</nav>
		<div class="vl-nav__backdrop"></div>

		<div class="vl-header__actions">

			<form class="vl-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="vl-sr" for="vl-search-field"><?php esc_html_e( 'Search books', 'nocturne' ); ?></label>
				<svg class="vl-search__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
					<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
				</svg>
				<input id="vl-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search titles…', 'nocturne' ); ?>">
			</form>

			<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="vl-cart" aria-label="<?php esc_attr_e( 'View cart', 'nocturne' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4H6z"/>
					<path d="M3 6h18"/>
					<path d="M16 10a4 4 0 0 1-8 0"/>
				</svg>
				<span><?php esc_html_e( 'Cart', 'nocturne' ); ?></span>
				<span class="vl-cart__count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
			</a>
			<?php endif; ?>

			<button class="vl-burger" aria-label="<?php esc_attr_e( 'Menu', 'nocturne' ); ?>" aria-expanded="false" aria-controls="site-nav">
				<span></span><span></span><span></span>
			</button>
		</div>

	</div>
</header>



<main id="content" class="site-main">
