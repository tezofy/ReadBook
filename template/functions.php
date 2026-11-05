<?php
/**
 * Nocturne — Dark Romance Author Theme
 * Child theme for Kadence / GeneratePress.
 *
 * Design philosophy: a luxury editorial dark theme. Everything the author
 * needs to run the site lives in the WordPress admin — no code required
 * after handover. This child theme only supplies the design system, the
 * hero slider shortcode, and a handful of admin conveniences.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

define( 'NOCTURNE_VERSION', '1.0.0' );
define( 'NOCTURNE_DIR', get_stylesheet_directory() );
define( 'NOCTURNE_URI', get_stylesheet_directory_uri() );

/* -------------------------------------------------------------------------
 * 1. ENQUEUE
 * ---------------------------------------------------------------------- */
function nocturne_enqueue() {
	// Support either parent theme without editing anything.
	$parent     = get_template();
	$parent_css = in_array( $parent, array( 'generatepress', 'kadence', 'astra', 'oceanwp' ), true ) ? $parent : 'kadence';

	wp_enqueue_style( 'nocturne', NOCTURNE_URI . '/assets/css/nocturne.css', array( $parent_css ), NOCTURNE_VERSION );
	wp_enqueue_style( 'nocturne-modules', NOCTURNE_URI . '/assets/css/modules.css', array( 'nocturne' ), NOCTURNE_VERSION );

	wp_enqueue_script( 'nocturne', NOCTURNE_URI . '/assets/js/nocturne.js', array(), NOCTURNE_VERSION, true );
	wp_localize_script(
		'nocturne',
		'nocturneData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'nocturne_nonce' ),
		)
	);

	// Preconnect + preload the fonts so headings never flash.
	wp_head();
}
add_action( 'wp_enqueue_scripts', 'nocturne_enqueue' );

/**
 * Font loading — Playfair Display (display) + Inter (UI) + Cormorant (italic accents).
 */
function nocturne_fonts() {
	?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<?php
}
add_action( 'wp_head', 'nocturne_fonts', 5 );

/* -------------------------------------------------------------------------
 * 2. THEME SUPPORTS
 * ---------------------------------------------------------------------- */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 300, 'flex-width' => true, 'flex-height' => true ) );
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 500,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
);

/* -------------------------------------------------------------------------
 * 3. DESIGN SYSTEM → CSS CUSTOM PROPERTIES
 *    One place to change the whole palette. Also exposed in the Customizer
 *    so the author can tweak colours without touching code.
 * ---------------------------------------------------------------------- */
function nocturne_customizer( $wp_customize ) {

	$wp_customize->add_section(
		'nocturne_design',
		array(
			'title'       => __( 'Nocturne — Design System', 'nocturne' ),
			'priority'    => 25,
			'description' => __( 'Change the palette of the site. Every colour here is used across the whole theme.', 'nocturne' ),
		)
	);

	$colors = array(
		'vl_ink'      => array( '#0B0A0D', __( 'Base background (ink)', 'nocturne' ) ),
		'vl_charcoal' => array( '#17151B', __( 'Card surface (charcoal)', 'nocturne' ) ),
		'vl_ivory'    => array( '#F4EFE6', __( 'Primary text (ivory)', 'nocturne' ) ),
		'vl_muted'    => array( '#A79FB2', __( 'Secondary text (muted)', 'nocturne' ) ),
		'vl_oxblood'  => array( '#8A1F2E', __( 'Accent one (oxblood)', 'nocturne' ) ),
		'vl_gold'     => array( '#C9A227', __( 'Accent two (antique gold)', 'nocturne' ) ),
	);

	foreach ( $colors as $id => $data ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $data[0],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $data[1],
					'section' => 'nocturne_design',
				)
			)
		);
	}

	$wp_customize->add_setting(
		'vl_hero_slides',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'vl_hero_slides',
		array(
			'label'       => __( 'Hero slides', 'nocturne' ),
			'section'     => 'nocturne_design',
			'type'        => 'textarea',
			'description' => __( 'One slide per line, using the format:<br><code>Image URL | Video URL | Kicker | Title | Excerpt | Button label | Button URL</code><br>Leave the video URL blank for an image slide.', 'nocturne' ),
		)
	);
}
add_action( 'customize_register', 'nocturne_customizer' );

/**
 * Write the chosen colours into an inline <style> block.
 */
function nocturne_inline_css() {
	$ink      = get_theme_mod( 'vl_ink', '#0B0A0D' );
	$charcoal = get_theme_mod( 'vl_charcoal', '#17151B' );
	$ivory    = get_theme_mod( 'vl_ivory', '#F4EFE6' );
	$muted    = get_theme_mod( 'vl_muted', '#A79FB2' );
	$oxblood  = get_theme_mod( 'vl_oxblood', '#8A1F2E' );
	$gold     = get_theme_mod( 'vl_gold', '#C9A227' );
	?>
	<style id="nocturne-vars">
		:root{
			--vl-ink:<?php echo esc_attr( $ink ); ?>;
			--vl-charcoal:<?php echo esc_attr( $charcoal ); ?>;
			--vl-ivory:<?php echo esc_attr( $ivory ); ?>;
			--vl-muted:<?php echo esc_attr( $muted ); ?>;
			--vl-oxblood:<?php echo esc_attr( $oxblood ); ?>;
			--vl-gold:<?php echo esc_attr( $gold ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'nocturne_inline_css', 20 );

/* -------------------------------------------------------------------------
 * 4. HERO SLIDER SHORTCODE  ⭐ THE KEY DELIVERABLE
 *    [nocturne_hero]
 *    Editable from the Customizer (one line per slide) OR from any page.
 *    Video slides autoplay muted + playsinline so iOS will play them.
 * ---------------------------------------------------------------------- */
function nocturne_hero_shortcode( $atts ) {

	$atts = shortcode_atts(
		array(
			'slides' => '',
			'dots'   => 'yes',
			'arrows' => 'yes',
		),
		$atts,
		'nocturne_hero'
	);

	// Prefer the Customizer content; fall back to the shortcode attribute.
	$raw = $atts['slides'] ? $atts['slides'] : get_theme_mod( 'vl_hero_slides', '' );
	$raw = trim( (string) $raw );

	if ( '' === $raw ) {
		return '<div class="vl-hero__stage"><p style="padding:60px 24px;color:#A79FB2">'
			. esc_html__( 'Hero slider: add slides in Appearance → Customize → Nocturne — Design System.', 'nocturne' )
			. '</p></div>';
	}

	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $raw ) ) );
	$out   = array();

	foreach ( $lines as $line ) {
		$p = array_map( 'trim', explode( '|', $line ) );
		$p = array_pad( $p, 7, '' );

		list( $image, $video, $kicker, $title, $excerpt, $btn_label, $btn_url ) = $p;

		if ( '' === $image && '' === $video ) {
			continue;
		}

		$media = '';
		if ( $video ) {
			// Video slide: poster = image, muted + playsinline = iOS autoplay works.
			$media = sprintf(
				'<video autoplay muted loop playsinline preload="none" poster="%s"><source src="%s" type="video/mp4"></video>',
				esc_url( $image ),
				esc_url( $video )
			);
		} elseif ( $image ) {
			$media = sprintf( '<img src="%s" alt="%s" loading="lazy">', esc_url( $image ), esc_attr( wp_strip_all_tags( $title ) ) );
		}

		$out[] = sprintf(
			'<article class="vl-slide" data-duration="7">
				<div class="vl-slide__media">%s</div>
				<div class="vl-slide__scrim"></div>
				<div class="vl-slide__content"><div class="vl-slide__inner">
					%s
					<h2 class="vl-slide__title">%s</h2>
					<p class="vl-slide__excerpt">%s</p>
					%s
				</div></div>
			</article>',
			$media,
			$kicker ? '<span class="vl-slide__kicker"><i></i><span>' . esc_html( $kicker ) . '</span></span>' : '',
			wp_kses_post( $title ),
			esc_html( $excerpt ),
			( $btn_label && $btn_url )
				? sprintf( '<div class="vl-slide__cta"><a href="%s" class="vl-btn">%s</a></div>', esc_url( $btn_url ), esc_html( $btn_label ) )
				: ''
		);
	}

	if ( empty( $out ) ) {
		return '';
	}

	$dots = '';
	if ( 'yes' === $atts['dots'] ) {
		foreach ( $out as $i => $_ ) {
			$dots .= sprintf(
				'<button class="vl-hero__dot%s" aria-label="Slide %d"><span></span></button>',
				0 === $i ? ' is-active' : '',
				$i + 1
			);
		}
	}

	$arrows = '';
	if ( 'yes' === $atts['arrows'] ) {
		$arrows = '
			<button class="vl-hero__arrow vl-hero__arrow--prev" aria-label="' . esc_attr__( 'Previous slide', 'nocturne' ) . '">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18 9 12l6-6"/></svg>
			</button>
			<button class="vl-hero__arrow vl-hero__arrow--next" aria-label="' . esc_attr__( 'Next slide', 'nocturne' ) . '">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
			</button>';
	}

	return sprintf(
		'<section class="vl-hero" aria-label="%s">
			<div class="vl-hero__stage">
				<div class="vl-hero__track">%s</div>
			</div>
			%s
			<div class="vl-hero__nav"><div class="vl-hero__dots" role="tablist" aria-label="%s">%s</div></div>
			<button class="vl-hero__toggle" aria-label="%s">
				<svg class="vl-icon-pause" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
				<svg class="vl-icon-play" viewBox="0 0 24 24" fill="currentColor"><path d="M7 4.5v15l13-7.5z"/></svg>
			</button>
		</section>',
		esc_attr__( 'Featured', 'nocturne' ),
		implode( '', $out ),
		$arrows,
		esc_attr__( 'Slides', 'nocturne' ),
		$dots,
		esc_attr__( 'Pause slideshow', 'nocturne' )
	);
}
add_shortcode( 'nocturne_hero', 'nocturne_hero_shortcode' );

/* -------------------------------------------------------------------------
 * 5. WELCOME SCREEN — tells the author exactly what to do first.
 * ---------------------------------------------------------------------- */
function nocturne_welcome() {
	add_action(
		'admin_notices',
		function () {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			$screen = get_current_screen();
			if ( ! $screen || 'dashboard' !== $screen->id ) {
				return;
			}
			?>
			<div class="notice notice-info" style="border-left:4px solid #8A1F2E;">
				<h3 style="margin:.6em 0 .3em;">Welcome to Nocturne — your site is live.</h3>
				<p style="max-width:760px;line-height:1.7;">
					Here is your short list, in order:
					<strong>(1)</strong> Add your three books under <em>Products → Add New</em>.
					<strong>(2)</strong> Replace the sample cover images with your own.
					<strong>(3)</strong> Set up payment in <em>WooCommerce → Settings → Payments</em>.
					<strong>(4)</strong> Add your hero videos in <em>Appearance → Customize → Nocturne</em>.
					<strong>(5)</strong> Write your About page and contact details.
					The full guide is in <code>docs/HANDOFF-GUIDE.md</code>.
				</p>
			</div>
			<?php
		}
	);
}
add_action( 'admin_init', 'nocturne_welcome' );

/* -------------------------------------------------------------------------
 * 6. SPAM PROTECTION FOR CONTACT FORMS
 *    Honeypot field + minimum time-to-submit. Works with Contact Form 7,
 *    WPForms and any form that can add a hidden field.
 * ---------------------------------------------------------------------- */
function nocturne_check_honeypot() {
	if ( ! isset( $_POST['nocturne_spam'] ) ) {
		return true;
	}
	// Field was filled in by a bot.
	if ( '' !== trim( sanitize_text_field( wp_unslash( $_POST['nocturne_spam'] ) ) ) ) {
		return false;
	}
	// Submitted too fast (under 3 seconds) → bot.
	if ( isset( $_POST['vl_t'] ) && ( time() - (int) $_POST['vl_t'] ) < 3 ) {
		return false;
	}
	return true;
}
add_filter( 'wpcf7_validate_contact_form', 'nocturne_wpcf7_validate' );

function nocturne_wpcf7_validate( $result ) {
	if ( ! nocturne_check_honeypot() ) {
		$result->invalidate( 'nocturne', __( 'Your message could not be sent. Please try again.', 'nocturne' ) );
	}
	return $result;
}

/**
 * Stamp forms with a timestamp so the server can time-check them.
 */
function nocturne_form_timestamp() {
	echo '<input type="hidden" name="vl_t" value="' . esc_attr( time() ) . '">';
	echo '<input type="text" name="nocturne_spam" class="vl-sr" tabindex="-1" autocomplete="off" aria-hidden="true">';
}
add_action( 'wpcf7_form_elements', 'nocturne_form_timestamp' );

/* -------------------------------------------------------------------------
 * 7. WOOCOMMERCE TWEAKS
 * ---------------------------------------------------------------------- */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Compact, on-brand quantity + cart.
function nocturne_wc_quantity() {
	global $product;
	if ( ! $product || ! $product->is_purchasable() ) {
		return;
	}
	echo '<div class="vl-qty">';
	do_action( 'woocommerce_before_quantity_input_field' );
	?>
	<input type="number" class="input-text qty text" name="quantity" value="1" min="1" step="1">
	<?php
	do_action( 'woocommerce_after_quantity_input_field' );
	echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'nocturne_wc_quantity', 10 );
remove_action( 'woocommerce_before_add_to_cart_quantity', 'woocommerce_quantity_input', 10 );

// Round the price display and use the theme colour.
function nocturne_wc_price_html( $price, $product ) {
	if ( $product->is_type( 'variable' ) ) {
		return $price;
	}
	return $price;
}
add_filter( 'woocommerce_get_price_html', 'nocturne_wc_price_html', 20, 2 );

// Product loop: show the series line under the title.
function nocturne_wc_series() {
	global $product;
	$series = get_post_meta( $product->get_id(), '_nocturne_series', true );
	if ( $series ) {
		echo '<p class="vl-book__series">' . esc_html( $series ) . '</p>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'nocturne_wc_series', 5 );

// Wrap the loop item so our CSS can style it as a cover-forward card.
function nocturne_wc_loop_open( $classes ) {
	return $classes . ' vl-book';
}
add_filter( 'woocommerce_post_class', 'nocturne_wc_loop_open', 10 );

/* -------------------------------------------------------------------------
 * 8. PERFORMANCE
 * ---------------------------------------------------------------------- */
function nocturne_defer_scripts( $tag, $handle ) {
	if ( in_array( $handle, array( 'nocturne' ), true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'nocturne_defer_scripts', 10, 2 );

function nocturne_preload_hero( $tag, $handle ) {
	return $tag;
}
add_filter( 'script_loader_tag', 'nocturne_preload_hero', 10, 2 );

/* -------------------------------------------------------------------------
 * 9. EXCERPT LENGTH + FORMATTING
 * ---------------------------------------------------------------------- */
function nocturne_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'nocturne_excerpt_length' );

function nocturne_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'nocturne_excerpt_more' );

/* -------------------------------------------------------------------------
 * 10. GDPR / COOKIE NOTICE
 * ---------------------------------------------------------------------- */
function nocturne_cookie_notice() {
	?>
	<div class="vl-cookie" id="vl-cookie" role="dialog" aria-label="<?php esc_attr_e( 'Cookie preferences', 'nocturne' ); ?>">
		<h4><?php esc_html_e( 'A small note about cookies', 'nocturne' ); ?></h4>
		<p><?php esc_html_e( 'This site uses cookies to run the shop and remember your cart. No advertising trackers, no data sold on. You can accept or decline.', 'nocturne' ); ?></p>
		<div class="vl-cookie__actions">
			<button class="vl-btn vl-btn--sm" data-vl-cookie="accept"><?php esc_html_e( 'Accept', 'nocturne' ); ?></button>
			<button class="vl-btn vl-btn--sm vl-btn--ghost" data-vl-cookie="decline"><?php esc_html_e( 'Essential only', 'nocturne' ); ?></button>
		</div>
	</div>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
			var bar = document.getElementById('vl-cookie');
			if (!bar) return;
			if (!localStorage.getItem('vl_cookie_choice')) {
				setTimeout(function () { bar.classList.add('is-visible'); }, 1200);
			}
			bar.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-vl-cookie]');
				if (!btn) return;
				localStorage.setItem('vl_cookie_choice', btn.getAttribute('data-vl-cookie'));
				bar.classList.remove('is-visible');
			});
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'nocturne_cookie_notice', 20 );

/* -------------------------------------------------------------------------
 * 11. FOOTER CREDIT + WIDGETS
 * ---------------------------------------------------------------------- */
function nocturne_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'nocturne' ),
			'id'            => 'footer-1',
			'description'   => __( 'Appears in the footer. Leave empty to show the default four columns.', 'nocturne' ),
			'before_widget' => '<section id="%1$s" class="vl-footer__col %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="vl-footer__title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'nocturne_widgets_init' );

/* -------------------------------------------------------------------------
 * 12. HELPER: a nicer excerpt / meta for the blog cards
 * ---------------------------------------------------------------------- */
function nocturne_post_meta() {
	echo '<div class="vl-post__meta">';
	echo '<span>' . esc_html( get_the_category()[0]->name ) . '</span>';
	echo '<span>·</span>';
	echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	echo '</div>';
}
