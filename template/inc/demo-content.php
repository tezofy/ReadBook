<?php
/**
 * Nocturne — Demo Content Importer
 * ---------------------------------------------------------------------------
 * One-click setup: creates 3 books (each with E-book + Paperback variations),
 * 1 full blog post, and the About / Contact / FAQ pages with real copy.
 *
 * INSTALL: copy this file into the child theme's inc/ folder (already done),
 * then visit  Appearance → Nocturne → Import Demo Content  (or run the
 * importer from WP-CLI:  wp eval-file inc/demo-content.php)
 *
 * Safe to run more than once — it will not create duplicates.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create the demo content.
 */
function nocturne_import_demo_content() {

	if ( ! function_exists( 'wc_get_product' ) ) {
		return new WP_Error( 'nocturne_no_woo', 'WooCommerce must be active first.' );
	}

	$created = array();

	/* ---------------------------------------------------------------
	 * 1. PRODUCT CATEGORIES
	 * ------------------------------------------------------------ */
	$categories = array(
		'ebooks'          => __( 'E-books', 'nocturne' ),
		'paperbacks'      => __( 'Paperbacks', 'nocturne' ),
		'limited-editions'=> __( 'Limited Editions', 'nocturne' ),
	);
	$cat_ids = array();
	foreach ( $categories as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term ) {
			$new    = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
			$cat_ids[ $slug ] = is_wp_error( $new ) ? 0 : $new['term_id'];
		} else {
			$cat_ids[ $slug ] = $term->term_id;
		}
	}

	/* ---------------------------------------------------------------
	 * 2. BOOKS — variable products with two format variations each
	 * ------------------------------------------------------------ */
	$books = array(
		array(
			'title'   => 'Velvet Vow',
			'series'  => 'Ashgate Series · Book One',
			'blurb'   => 'Elias March buys the Dalloway estate at auction and everything inside it — including the woman standing in the empty gallery at two in the morning. She was never part of the sale, and he has no intention of explaining why he is keeping her.',
			'excerpt' => 'The bell only rang for the dead. On the seventh night, it rang for her.',
			'price'   => '14.99',
			'paper'   => '19.99',
			'cat'     => 'paperbacks',
			'cover'   => 'assets/img/cover-velvet-vow.jpg',
			'featured'=> true,
		),
		array(
			'title'   => 'The Hollow Church',
			'series'  => 'Ashgate Series · Book Two',
			'blurb'   => 'Sister Colette left the order nine years ago. The belltower left her address. In Ashgate, the bell only rings for the dead — and on the seventh night, it rang for her.',
			'excerpt' => 'She came back to close a chapter. The village had other plans.',
			'price'   => '12.99',
			'paper'   => '17.99',
			'cat'     => 'paperbacks',
			'cover'   => 'assets/img/cover-hollow-church.jpg',
			'featured'=> false,
		),
		array(
			'title'   => 'Obsession in Ink',
			'series'  => 'Standalone',
			'blurb'   => 'A novelist and the editor who ruined her first manuscript. Ten years later he returns with an offer she cannot refuse — and a confession she cannot forgive.',
			'excerpt' => 'He wanted her words. He got something else entirely.',
			'price'   => '24.99',
			'paper'   => '39.99',
			'cat'     => 'limited-editions',
			'cover'   => 'assets/img/cover-obsession.jpg',
			'featured'=> false,
		),
	);

	foreach ( $books as $b ) {

		if ( get_page_by_title( $b['title'], OBJECT, 'product' ) ) {
			continue; // Already imported.
		}

		$product = new WC_Product_Variable();
		$product->set_name( $b['title'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description(
			'<p>' . esc_html( $b['blurb'] ) . '</p>'
			. '<p>Every novel in the collection opens with a full list of content warnings, so you always know what you are choosing before you turn the page.</p>'
		);
		$product->set_short_description( esc_html( $b['excerpt'] ) );
		$product->set_sku( 'vv-' . sanitize_title( $b['title'] ) );
		$product->set_featured( ! empty( $b['featured'] ) );
		$product->set_category_ids( array_filter( array( $cat_ids[ $b['cat'] ], $cat_ids['ebooks'] ) ) );

		// Book meta
		update_post_meta( $product->get_id(), '_nocturne_series', $b['series'] );
		update_post_meta( $product->get_id(), '_nocturne_excerpt', $b['excerpt'] );

		// Attribute: Format
		$attribute = new WC_Product_Attribute();
		$attribute->set_name( 'Format' );
		$attribute->set_options( array( 'E-book', 'Paperback' ) );
		$attribute->set_visible( true );
		$attribute->set_variation( true );
		$product->set_attributes( array( $attribute ) );

		$product_id = $product->save();

		// Variation: E-book (downloadable)
		$v_ebook = new WC_Product_Variation();
		$v_ebook->set_parent_id( $product_id );
		$v_ebook->set_attributes( array( 'attribute_format' => 'E-book' ) );
		$v_ebook->set_regular_price( $b['price'] );
		$v_ebook->set_price( $b['price'] );
		$v_ebook->set_status( 'publish' );
		$v_ebook->set_virtual( true );
		$v_ebook->set_downloadable( true );
		$v_ebook->set_downloads( 1 );
		$v_ebook->set_download_duration( 30 );
		$v_ebook->set_download_permission( 'order' );
		$v_ebook->set_manage_stock( false );
		$v_ebook->save();

		// Variation: Paperback (physical)
		$v_paper = new WC_Product_Variation();
		$v_paper->set_parent_id( $product_id );
		$v_paper->set_attributes( array( 'attribute_format' => 'Paperback' ) );
		$v_paper->set_regular_price( $b['paper'] );
		$v_paper->set_price( $b['paper'] );
		$v_paper->set_status( 'publish' );
		$v_paper->set_virtual( false );
		$v_paper->set_manage_stock( false );
		$v_paper->set_weight( '0.35' );
		$v_paper->set_length( '0.20' );
		$v_paper->set_width( '0.13' );
		$v_paper->set_height( '0.02' );
		$v_paper->save();

		// Featured image
		if ( isset( $b['cover'] ) && file_exists( NOCTURNE_DIR . '/' . $b['cover'] ) ) {
			$upload = nocturne_import_image( NOCTURNE_DIR . '/' . $b['cover'] );
			if ( $upload ) {
				set_post_thumbnail( $product_id, $upload );
			}
		}

		$created[] = $b['title'];
	}

	/* ---------------------------------------------------------------
	 * 3. BLOG POST
	 * ------------------------------------------------------------ */
	if ( ! get_page_by_title( 'How I write a villain I actually want you to root for', OBJECT, 'post' ) ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => 'How I write a villain I actually want you to root for',
				'post_name'    => 'how-i-write-a-villain-i-want-you-to-root-for',
				'post_status'  => 'publish',
				'post_type'    => 'post',
				'post_content' => '<!-- wp:paragraph --><p>There is a moment, roughly two thirds of the way through a book, where the reader stops being able to claim they did not know. They knew. You showed them the first page and they kept reading anyway. Writing that moment is the whole job.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>People ask me how I make a villain sympathetic, and the answer is slightly embarrassing: I do not. Sympathy is a blunt instrument and it wears out fast. What I build instead is <em>precision</em>. I make their reasons so specific, so internally consistent, so quietly reasonable that the reader argues with them out loud. That argument is the engine of the book.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Rule one: give the villain a real wound, and make it theirs, not theirs</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>The single most common mistake is borrowing a trauma for your antagonist — a bad childhood, a dead parent, a difficult childhood — and then treating it as an explanation. An explanation is not a reason. In <em>Velvet Vow</em>, Elias does not explain himself once. He simply behaves consistently, and the reader slowly realises that if he had been in his position, he might have done the same. That is far more frightening than a bad childhood.</p><!-- /wp:paragraph -->
<!-- wp:blockquote --><blockquote class="wp-block-quote"><p>Make them the hero of their own story. If you have not done that, no amount of description will save the scene.</p></blockquote><!-- /wp:blockquote -->
<!-- wp:heading {"level":3} --><h3>Rule two: never let them apologise</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>An apology is a door out of the book. The moment your antagonist says <em>I am sorry</em>, the reader is permitted to forgive them and stop worrying. I do not let my antagonists apologise. I let them explain, which is a different thing entirely, and far more dangerous.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Rule three: give the reader one scene they are not allowed to enjoy</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Every book needs a chapter where the reader is made to feel something they did not consent to feeling. It is the price of admission. Without it, the book is merely atmospheric and slightly incestuous, and I have no interest in that.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>And the rule I broke</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>For <em>The Hollow Church</em> I broke rule three, and the book became something else. There is a chapter where Colette does something unforgivable and the narration does not comment on it. No music sting, no careful distancing. She does it, the weather changes, and we keep walking. It is the chapter readers write to me about — and the one I am most proud of.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Next time I will write about what happens in the seventh night.</p><!-- /wp:paragraph -->',
				'post_date'   => current_time( 'mysql' ),
			)
		);

		$cover = NOCTURNE_DIR . '/assets/img/blog-cover.jpg';
		if ( file_exists( $cover ) ) {
			$upload = nocturne_import_image( $cover );
			if ( $upload ) {
				set_post_thumbnail( $post_id, $upload );
			}
		}
		$created[] = 'Blog post';
	}

	/* ---------------------------------------------------------------
	 * 4. PAGES
	 * ------------------------------------------------------------ */
	$pages = array(
		'books'   => array(
			'title'   => 'Books',
			'content' => '<!-- wp:shortcode -->[woocommerce_products]<!-- /wp:shortcode -->',
		),
		'about'   => array(
			'title'   => 'About',
			'content' => '<!-- wp:paragraph --><p>Dark romance for readers who like their love story with teeth.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>I write about the kind of love that should not be survivable — the ones where somebody is running and somebody is waiting, and they meet in the worst possible room. My characters are not victims and they are not villains. They are people who did a terrible thing for a good reason and then had to live inside it.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Before I wrote full-time I spent eleven years as a hospital night-shift administrator, which is where I learned that the most frightening thing in the world is not a locked door. It is someone sitting quietly beside you, refusing to leave. My first novel, <em>Velvet Vow</em>, was written on my phone during four hundred overnight shifts.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>I live in the rain with two cats, an unreasonable number of candles, and a corkboard covered in red string. I answer every reader email myself. If you write to me, I will write back.</p><!-- /wp:paragraph -->',
		),
		'contact' => array(
			'title'   => 'Contact',
			'content' => '<!-- wp:paragraph --><p>Press, rights, reviews, or just to tell me which chapter broke you.</p><!-- /wp:paragraph -->
<!-- wp:contact-form-7 id="101" title="Contact form 1" /-->',
		),
		'faq'     => array(
			'title'   => 'FAQ',
			'content' => '<!-- wp:heading {"level":3} --><h3>Do you ship internationally?</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Yes — tracked shipping to most countries. E-books are delivered instantly to your inbox the moment your payment clears, and the download link stays live for 30 days.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Which format should I buy?</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>E-book for the fastest read, paperback if you want it on your shelf. The limited hardcover editions are hand-bound, numbered to three hundred, gilded on the edge and signed.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Are your books suitable for younger readers?</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>No. Every book is written for adults only — 18+ — and deals with consent, coercion, and difficult emotional terrain. Each book opens with a full list of content warnings.</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3} --><h3>Do you run discounts?</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>Occasionally — launch weeks and reader birthdays. If you are on the list you will hear about them first.</p><!-- /wp:paragraph -->',
		),
		'journal' => array(
			'title'   => 'Journal',
			'content' => '<!-- wp:paragraph --><p>Behind the manuscripts, late-night research, and whatever is currently keeping me awake.</p><!-- /wp:paragraph -->',
		),
	);

	foreach ( $pages as $slug => $p ) {
		if ( get_page_by_title( $p['title'], OBJECT, 'page' ) ) {
			continue;
		}
		$page_id = wp_insert_post(
			array(
				'post_title'   => $p['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => $p['content'],
			)
		);
		$created[] = 'Page: ' . $p['title'];
	}

	/* ---------------------------------------------------------------
	 * 5. MENUS
	 * ------------------------------------------------------------ */
	$menu_name = 'Primary';
	$menu      = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		foreach ( array(
			'books'   => 'Books',
			'about'   => 'About',
			'journal' => 'Journal',
			'faq'     => 'FAQ',
			'contact' => 'Contact',
		) as $slug => $label ) {
			$page = get_page_by_path( $slug );
			if ( $page ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => $label,
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $page->ID,
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				) );
			}
		}
		$created[] = 'Menu: ' . $menu_name;
	}

	/* ---------------------------------------------------------------
	 * 6. SAMPLE COUPON
	 * ------------------------------------------------------------ */
	if ( ! wc_get_coupon_id_by_code( 'WELCOME10' ) ) {
		$coupon = new WC_Coupon();
		$coupon->set_code( 'WELCOME10' );
		$coupon->set_discount_type( 'percent' );
		$coupon->set_amount( 10 );
		$coupon->set_description( __( 'Welcome offer for new readers', 'nocturne' ) );
		$coupon->save();
		$created[] = 'Coupon: WELCOME10';
	}

	return $created;
}

/**
 * Sideload a local image into the Media Library and return its attachment ID.
 *
 * @param string $path Absolute path to the image file.
 * @return int Attachment ID, or 0 on failure.
 */
function nocturne_import_image( $path ) {
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$upload = wp_upload_bits( basename( $path ), null, file_get_contents( $path ) );
	if ( $upload['error'] ) {
		return 0;
	}

	$filetype = wp_check_filetype( $upload['file'], null );
	$args     = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $path, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$id = wp_insert_attachment( $args, $upload['file'] );
	if ( ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	return $id;
}

/* ---------------------------------------------------------------------------
 * ADMIN PAGE — "Nocturne" menu with a one-click importer button.
 * ------------------------------------------------------------------------- */
function nocturne_admin_menu() {
	add_theme_page(
		__( 'Nocturne Setup', 'nocturne' ),
		__( 'Nocturne Setup', 'nocturne' ),
		'manage_options',
		'nocturne-setup',
		'nocturne_setup_page'
	);
}
add_action( 'admin_menu', 'nocturne_admin_menu' );

function nocturne_setup_page() {
	$done = false;
	$list = array();
	if ( isset( $_POST['nocturne_import'] ) && check_admin_referer( 'nocturne_import' ) ) {
		$res = nocturne_import_demo_content();
		if ( is_wp_error( $res ) ) {
			$list[] = 'ERROR: ' . $res->get_error_message();
		} else {
			$done = true;
			$list = $res;
		}
	}
	?>
	<div class="wrap">
		<h1 style="margin-top:24px"><?php esc_html_e( 'Nocturne — Setup', 'nocturne' ); ?></h1>

		<div class="card" style="max-width:760px;padding:22px 24px;margin-top:18px">
			<h2 style="margin-top:0"><?php esc_html_e( 'Import demo content', 'nocturne' ); ?></h2>
			<p><?php esc_html_e( 'This creates three sample books (each with an E-book and a Paperback variation), one full blog post, the About / Contact / FAQ / Journal pages, a primary menu and a sample coupon. You can delete any of it later. Running this twice will not create duplicates.', 'nocturne' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'nocturne_import' ); ?>
				<p><button class="button button-primary button-hero" name="nocturne_import" value="1"><?php esc_html_e( 'Import demo content', 'nocturne' ); ?></button></p>
			</form>
			<?php if ( $done ) : ?>
				<div style="margin-top:18px;padding:14px 16px;background:#edfaef;border-left:4px solid #00a32a">
					<strong><?php esc_html_e( 'Done. Imported:', 'nocturne' ); ?></strong>
					<?php echo esc_html( implode( ', ', $list ) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $list ) && ! $done ) : ?>
				<div style="margin-top:18px;padding:14px 16px;background:#fcf0f1;border-left:4px solid #d63638">
					<?php echo esc_html( implode( ', ', $list ) ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="card" style="max-width:760px;padding:22px 24px;margin-top:18px">
			<h2 style="margin-top:0"><?php esc_html_e( 'Next steps', 'nocturne' ); ?></h2>
			<ol style="line-height:1.9">
				<li><?php esc_html_e( 'Appearance → Customize → Nocturne — Design System: add your hero images and videos.', 'nocturne' ); ?></li>
				<li><?php esc_html_e( 'Products → each sample book → replace the cover image with your real artwork.', 'nocturne' ); ?></li>
				<li><?php esc_html_e( 'WooCommerce → Settings → Payments: connect Stripe or PayPal.', 'nocturne' ); ?></li>
				<li><?php esc_html_e( 'WooCommerce → Settings → Shipping: set your zones and rates.', 'nocturne' ); ?></li>
				<li><?php esc_html_e( 'Settings → Reading: set a static front page, then build the home page with Elementor.', 'nocturne' ); ?></li>
				<li><?php esc_html_e( 'Settings → Permalinks → Save (this turns on pretty URLs).', 'nocturne' ); ?></li>
			</ol>
		</div>
	</div>
	<?php
}
