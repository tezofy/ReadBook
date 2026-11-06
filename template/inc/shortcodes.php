<?php
/**
 * Nocturne shortcodes.
 *
 * The showcase components that carry the site's premium feel:
 * the split hero, the 3D product rail and the ticker.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Resolve a cover image URL for a product.
 * Prefers the featured image, falls back to the first attachment.
 * ---------------------------------------------------------------------- */
function nocturne_cover_url( $product_id = 0, $size = 'large' ) {
	if ( $product_id && function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $product_id ) ) {
		$url = get_the_post_thumbnail_url( $product_id, $size );
		if ( $url ) {
			return $url;
		}
	}

	// Fall back to the first image attached to the product.
	$attachments = get_posts(
		array(
			'post_parent'    => $product_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $attachments ) {
		$url = wp_get_attachment_image_url( $attachments[0], $size );
		if ( $url ) {
			return $url;
		}
	}

	return '';
}

/* -------------------------------------------------------------------------
 * The 3D book cover.
 *
 * Renders a real three-dimensional object: the cover turns towards the
 * reader on hover and the striped page block shows along the fore-edge.
 * ---------------------------------------------------------------------- */
function nocturne_3d_book( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'image'   => '',
			'alt'     => '',
			'link'    => '',
			'tilt'    => '6',
			'classes' => '',
		)
	);

	if ( ! $args['image'] ) {
		return '';
	}

	$tag  = $args['link'] ? 'a' : 'div';
	$href = $args['link'] ? ' href="' . esc_url( $args['link'] ) . '"' : '';

	ob_start();
	?>
	<<?php echo esc_attr( $tag ); ?> class="vl-3d-book <?php echo esc_attr( $args['classes'] ); ?>"
		<?php echo $href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( $args['tilt'] ) : ?> data-tilt="<?php echo esc_attr( $args['tilt'] ); ?>"<?php endif; ?>>
		<span class="vl-3d-book__shadow"></span>
		<span class="vl-3d-book__cover">
			<span class="vl-3d-book__pages"></span>
			<span class="vl-3d-book__face vl-3d-book__face--front">
				<img src="<?php echo esc_url( $args['image'] ); ?>" alt="<?php echo esc_attr( $args['alt'] ); ?>" loading="lazy" decoding="async">
			</span>
			<span class="vl-3d-book__face vl-3d-book__face--back"></span>
		</span>
	</<?php echo esc_attr( $tag ); ?>>
	<?php
	return ob_get_clean();
}

/* -------------------------------------------------------------------------
 * [nocturne_showcase]
 *
 * A horizontal rail of book covers rendered as physical objects.
 * The arrow buttons drive the rail; the cards snap to whole covers.
 *
 * Usage:
 *   [nocturne_showcase count="6" category="ebooks" title="Featured Titles"]
 * ---------------------------------------------------------------------- */
function nocturne_showcase_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count'    => 6,
			'category' => '',
			'title'    => '',
			'excerpt'  => '',
			'orderby'  => 'menu_order',
		),
		$atts,
		'nocturne_showcase'
	);

	// Prefer real products when WooCommerce is active.
	if ( function_exists( 'wc_get_products' ) ) {
		$query_args = array(
			'limit'   => (int) $atts['count'],
			'orderby' => sanitize_key( $atts['orderby'] ),
			'status'  => 'publish',
			'return'  => 'objects',
		);
		if ( $atts['category'] ) {
			$query_args['category_slug'] = array_map( 'sanitize_title', explode( ',', $atts['category'] ) );
		}
		$products = wc_get_products( $query_args );
	} else {
		$products = get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => (int) $atts['count'],
				'orderby'        => sanitize_key( $atts['orderby'] ),
				'post_status'    => 'publish',
			)
		);
	}

	if ( empty( $products ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="vl-showcase">
		<?php if ( $atts['title'] ) : ?>
		<div class="vl-sect-head vl-sect-head--center" data-animate="fade-up">
			<span class="vl-eyebrow vl-eyebrow--center"><?php esc_html_e( 'Featured Titles', 'nocturne' ); ?></span>
			<h2><?php echo esc_html( $atts['title'] ); ?></h2>
			<?php if ( $atts['excerpt'] ) : ?>
			<p class="vl-lede" style="margin-inline:auto"><?php echo esc_html( $atts['excerpt'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="vl-rail" data-animate="fade-up">
			<div class="vl-rail__track">
				<?php foreach ( $products as $product ) :
					$product_id = is_object( $product ) ? $product->get_id() : $product->ID;
					$title      = is_object( $product ) ? $product->get_name() : get_the_title( $product_id );
					$permalink  = is_object( $product ) ? $product->get_permalink() : get_permalink( $product_id );
					$cover      = nocturne_cover_url( $product_id );
					if ( ! $cover ) {
						continue;
					}
					$price = is_object( $product ) ? $product->get_price() : '';
					?>
					<article class="vl-rail__item vl-product">
						<div class="vl-product__stage">
							<div class="vl-product__glow"></div>
							<?php
							echo nocturne_3d_book( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								array(
									'image' => $cover,
									'alt'   => $title,
									'link'  => $permalink,
									'tilt'  => '6',
								)
							);
							?>
						</div>
						<div class="vl-book__body" style="text-align:center;padding-left:0;padding-right:0">
							<p class="vl-book__series"><?php echo esc_html( is_object( $product ) ? $product->get_type() : '' ); ?></p>
							<h3 class="vl-book__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
							<?php if ( $price ) : ?>
							<div class="vl-book__foot" style="justify-content:center">
								<span class="vl-book__price"><?php echo wp_kses_post( wc_price( $price ) ); ?></span>
							</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="vl-center" style="margin-top:clamp(30px,4vw,48px);display:flex;justify-content:center">
			<div class="vl-rail__nav">
				<button class="vl-rail__btn" data-rail-prev aria-label="<?php esc_attr_e( 'Previous titles', 'nocturne' ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18 9 12l6-6"/></svg>
				</button>
				<button class="vl-rail__btn" data-rail-next aria-label="<?php esc_attr_e( 'Next titles', 'nocturne' ); ?>">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
				</button>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_showcase', 'nocturne_showcase_shortcode' );

/* -------------------------------------------------------------------------
 * [nocturne_split_hero]
 *
 * A cover on the left, a full-bleed looping video on the right.
 * The cover swaps as the video cycles.
 *
 * Usage:
 *   [nocturne_split_hero slides="Image | Video | Title | Excerpt | Cover"]
 *   One slide per line, separated by a newline.
 * ---------------------------------------------------------------------- */
function nocturne_split_hero_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'slides' => '',
		),
		$atts,
		'nocturne_split_hero'
	);

	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $atts['slides'] ) ) );

	if ( empty( $lines ) ) {
		return '';
	}

	$parsed = array();
	foreach ( $lines as $line ) {
		$cols = array_map( 'trim', explode( '|', $line ) );
		$parsed[] = array(
			'image'   => isset( $cols[0] ) ? $cols[0] : '',
			'video'   => isset( $cols[1] ) ? $cols[1] : '',
			'title'   => isset( $cols[2] ) ? $cols[2] : '',
			'excerpt' => isset( $cols[3] ) ? $cols[3] : '',
			'cover'   => isset( $cols[4] ) ? $cols[4] : '',
		);
	}

	ob_start();
	?>
	<section class="vl-split-hero" aria-label="<?php esc_attr_e( 'Featured release', 'nocturne' ); ?>">
		<div class="vl-split-hero__grid" data-animate="fade-up">

			<div class="vl-split-hero__cover">
				<?php
				echo nocturne_3d_book( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'image'   => $parsed[0]['cover'] ? $parsed[0]['cover'] : $parsed[0]['image'],
						'alt'     => $parsed[0]['title'],
						'classes' => 'vl-3d-book--enter',
					)
				);
				?>
				<div style="position:absolute;bottom:22px;left:0;right:0;z-index:2;display:flex;justify-content:center;gap:10px">
					<button class="vl-rail__btn" data-split-prev aria-label="<?php esc_attr_e( 'Previous', 'nocturne' ); ?>">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18 9 12l6-6"/></svg>
					</button>
					<button class="vl-rail__btn" data-split-next aria-label="<?php esc_attr_e( 'Next', 'nocturne' ); ?>">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
					</button>
				</div>
			</div>

			<div class="vl-split-hero__media">
				<?php foreach ( $parsed as $i => $slide ) : ?>
				<div class="vl-split-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
					<?php echo $slide['cover'] ? ' data-cover="' . esc_url( $slide['cover'] ) . '"' : ''; ?>>
					<?php if ( $slide['video'] ) : ?>
						<video autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url( $slide['image'] ); ?>">
							<source src="<?php echo esc_url( $slide['video'] ); ?>" type="video/mp4">
						</video>
					<?php elseif ( $slide['image'] ) : ?>
						<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_split_hero', 'nocturne_split_hero_shortcode' );

/* -------------------------------------------------------------------------
 * [nocturne_ticker]
 *
 * A slow marquee of accolades. Items are separated by a pipe.
 * ---------------------------------------------------------------------- */
function nocturne_ticker_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'items' => '',
		),
		$atts,
		'nocturne_ticker'
	);

	$items = array_filter( array_map( 'trim', explode( '|', (string) $atts['items'] ) ) );
	if ( empty( $items ) ) {
		return '';
	}

	$row = '';
	foreach ( $items as $item ) {
		$row .= '<span class="vl-ticker__item">' . esc_html( $item ) . '</span>';
	}

	ob_start();
	?>
	<section style="padding-block:26px;border-block:1px solid var(--vl-line);background:var(--vl-ink-2);overflow:hidden">
		<div class="vl-ticker" aria-hidden="true">
			<div class="vl-ticker__row"><?php echo $row; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<div class="vl-ticker__row" aria-hidden="true"><?php echo $row; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_ticker', 'nocturne_ticker_shortcode' );
