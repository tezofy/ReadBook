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

<a class="vl-sr" href="#content"><?php esc_html_e( 'Skip to content', 'nocturne' ); ?></a>

<header class="vl-header">
	<div class="vl-header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vl-brand" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — <?php esc_attr_e( 'home', 'nocturne' ); ?>">
			<svg class="vl-brand__mark" viewBox="0 0 48 48" fill="none" aria-hidden="true">
				<circle cx="24" cy="24" r="22.5" stroke="#C9A227" stroke-width="1" opacity=".55"/>
				<path d="M24 33c-4.5-2.6-7-6.2-7-9.9 0-3 2-5.3 4.6-5.3 1.7 0 3 1 3.7 2.4.7-1.4 2-2.4 3.7-2.4 2.6 0 4.6 2.3 4.6 5.3 0 3.7-2.5 7.3-7 9.9z" fill="#8A1F2E"/>
				<path d="M24 33V17" stroke="#C9A227" stroke-width="1.1" stroke-linecap="round"/>
				<path d="M24 24.5 20 21M24 21l4-3.5" stroke="#C9A227" stroke-width=".9" stroke-linecap="round"/>
			</svg>
			<span class="vl-brand__text">
				<span class="vl-brand__name"><?php bloginfo( 'name' ); ?></span>
				<span class="vl-brand__tag"><?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?></span>
			</span>
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
