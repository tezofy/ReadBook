<?php
/**
 * Nocturne — Client Edition shortcodes.
 *
 * These render the client's approved design: the home page, the series
 * rail, the standalone titles and the journal grid. They read from the
 * catalogue in inc/client-data.php, so the theme and the static preview
 * always show the same books, prices and reviews.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

/**
 * The client catalogue: books, standalone titles, reviews and posts.
 *
 * @return array
 */
function nocturne_client_catalogue() {
	static $catalogue = null;

	if ( null !== $catalogue ) {
		return $catalogue;
	}

	$catalogue = array(
		'series'     => array(
			array(
				'id'    => 'speranta',
				'title' => 'SPERANȚA ARE NUMELE LUI',
				'price' => 75,
				'status' => 'DISPONIBIL',
				'class'  => 'in',
				'badge'  => '',
				'img'   => 'assets/img/client/cover-speranta.jpg',
				'desc'  => 'Vol. 1 Imperiul Umbrelor',
			),
			array(
				'id'    => 'pacatul',
				'title' => 'PĂCATUL CASEI VANNI: UMBRA LUI VESPER',
				'price' => 85,
				'status' => 'PRECOMANDĂ',
				'class'  => 'pre',
				'badge'  => 'PRECOMANDĂ • 16 FEB',
				'img'   => 'assets/img/client/cover-paculul.jpg',
				'desc'  => 'Vol. 2 - Lansare 16 februarie',
			),
			array(
				'id'    => 'mostenirea',
				'title' => 'MOȘTENIREA SINISTRĂ',
				'price' => 0,
				'status' => 'ÎN CURÂND',
				'class'  => 'soon',
				'badge'  => '',
				'img'   => 'assets/img/client/cover-mostenirea.jpg',
				'desc'  => 'Vol. 3 Imperiul Umbrelor',
			),
			array(
				'id'    => 'exilul',
				'title' => 'EXILUL SÂNGELUI',
				'price' => 0,
				'status' => 'ÎN CURÂND',
				'class'  => 'soon',
				'badge'  => '',
				'img'   => 'assets/img/client/cover-exilul.jpg',
				'desc'  => 'Vol. 4 Imperiul Umbrelor',
			),
		),
		'standalone' => array(
			array(
				'id'    => 'a94',
				'title' => 'PROIECTUL A-94 CODUL SÂNGELUI',
				'price' => 80,
				'img'   => 'assets/img/client/cover-a94.jpg',
			),
			array(
				'id'    => 'sprayed',
				'title' => 'SPERANȚA ARE NUMELE LUI EDIȚIE SPRAYED EDGES',
				'price' => 70,
				'img'   => 'assets/img/client/speranta-stack.jpg',
			),
		),
		'reviews'    => array(
			'speranta' => array(
				array( 'name' => 'Maria D.', 'stars' => 5, 'text' => 'Am plâns și am iubit fiecare pagină. Rian e tot ce nu știam că vreau.' ),
				array( 'name' => 'Andreea', 'stars' => 5, 'text' => 'Nu e romance dulce, e cuțit. Exact cum îmi place.' ),
				array( 'name' => 'Ioana P.', 'stars' => 5, 'text' => 'Speranța m-a ținut trează până la 3 dimineața. Nu am putut lăsa cartea.' ),
			),
			'pacatul' => array(
				array( 'name' => 'Elena M.', 'stars' => 5, 'text' => 'Vesper e periculos și perfect. Am nevoie de vol 2 acum, nu pot aștepta.' ),
				array( 'name' => 'Diana R.', 'stars' => 5, 'text' => 'Casa Vanni nu iartă. Eu nu pot uita ce am citit aici.' ),
				array( 'name' => 'Cristina V.', 'stars' => 4, 'text' => 'Dark, intens, cu suflet. Precomanda a meritat fiecare leu.' ),
			),
			'mostenirea' => array(
				array( 'name' => 'Cristina L.', 'stars' => 5, 'text' => 'Abia aștept. Dacă e ca primele două, o să doară frumos.' ),
				array( 'name' => 'Larisa', 'stars' => 4, 'text' => 'Te rog nu ne lăsa în suspans prea mult. Vreau continuarea.' ),
			),
			'exilul' => array(
				array( 'name' => 'Mihaela S.', 'stars' => 5, 'text' => 'Exilul sângelui sună ca o promisiune. Sunt gata să intru.' ),
				array( 'name' => 'Anca T.', 'stars' => 5, 'text' => 'Vreau să știu ce pierde ca să păstreze ce iubește. Sună sfâșietor.' ),
			),
			'a94' => array(
				array( 'name' => 'Teodora B.', 'stars' => 5, 'text' => 'Codul sângelui m-a prins de la prima pagină. Alt nivel de dark romance.' ),
				array( 'name' => 'Roxana', 'stars' => 5, 'text' => 'Dark, inteligent, cu suflet. A-94 e favorit standalone pentru mine.' ),
				array( 'name' => 'Mara I.', 'stars' => 4, 'text' => 'Proiectul ăsta e cuțit cu mânuși de mătase. Genial.' ),
			),
			'sprayed' => array(
				array( 'name' => 'Alexia D.', 'stars' => 5, 'text' => 'Ediția asta e artă. Marginile roșii ca o rană. Superbă.' ),
				array( 'name' => 'Sorina M.', 'stars' => 5, 'text' => 'Colecția mea merita asta. Calitate impecabilă, merită fiecare leu.' ),
				array( 'name' => 'Delia', 'stars' => 5, 'text' => 'Țin cartea și nu vreau să o deschid de frică să nu o murdăresc.' ),
			),
		),
		'posts'      => array(
			array(
				'tag'     => 'PRECOMANDĂ DESCHISĂ',
				'date'    => '16 FEBRUARIE • BLOG',
				'title'   => 'S-a deschis precomanda pentru Păcatul Casei Vanni',
				'excerpt' => 'Vesper se întoarce. Am deschis lista pentru primele 100 de exemplare semnate cu sprayed edges roșu sângeriu. Livrare din 20 februarie.',
				'img'     => 'assets/img/client/cover-paculul.jpg',
			),
			array(
				'tag'     => 'CULISE',
				'date'    => '28 IANUARIE • BLOG',
				'title'   => 'Cum a luat naștere Speranța Are Numele Lui',
				'excerpt' => 'A început cu o frază scrisă la 2 dimineața. Nu știam cine e el. Știam doar că ea îl așteaptă de 7 ani și nu mai are nimic de pierdut.',
				'img'     => 'assets/img/client/cover-speranta.jpg',
			),
			array(
				'tag'     => 'EDIȚIE',
				'date'    => '10 IANUARIE • BLOG',
				'title'   => 'Ce înseamnă ediția sprayed edges și de ce am ales-o',
				'excerpt' => 'Marginile pictate manual, roșu închis, ca o rană care nu se închide. Pentru că poveștile astea nu sunt curate. Sunt vii.',
				'img'     => 'assets/img/client/speranta-stack.jpg',
			),
		),
	);

	return $catalogue;
}

/**
 * The URL of a client asset that ships with the theme.
 *
 * @param string $path Relative path inside assets/img.
 * @return string
 */
function nocturne_client_asset( $path ) {
	return get_stylesheet_directory_uri() . '/' . ltrim( $path, '/' );
}

/**
 * Render the star row.
 *
 * @param int $count How many stars are lit.
 * @param string $size Font size.
 * @return string
 */
function nocturne_client_stars( $count, $size = '12px' ) {
	$out = '<span class="cl-stars" style="font-size:' . esc_attr( $size ) . '">';
	for ( $i = 0; $i < 5; $i++ ) {
		$out .= '<span class="' . ( $i < $count ? '' : 'is-off' ) . '">★</span>';
	}
	return $out . '</span>';
}

/**
 * The book card used in the series rail.
 *
 * @param array $book  Book data.
 * @param bool  $head  Whether to use a heading tag.
 * @return string
 */
function nocturne_client_book_card( $book, $head = true ) {
	$catalogue = nocturne_client_catalogue();
	$reviews   = isset( $catalogue['reviews'][ $book['id'] ] ) ? $catalogue['reviews'][ $book['id'] ] : array();

	$tag = $head ? 'h2' : 'h3';
	$price = $book['price'] > 0 ? $book['price'] . ' LEI' : '—';

	ob_start();
	?>
	<article class="cl-book">
		<div class="cl-book__cover">
			<span class="cl-book__status cl-book__status--<?php echo esc_attr( $book['class'] ); ?>"><?php echo esc_html( $book['status'] ); ?></span>
			<?php if ( ! empty( $book['badge'] ) ) : ?>
				<span class="cl-book__badge"><?php echo esc_html( $book['badge'] ); ?></span>
			<?php endif; ?>
			<img src="<?php echo esc_url( nocturne_client_asset( $book['img'] ) ); ?>" alt="<?php echo esc_attr( $book['title'] ); ?>" loading="lazy">
		</div>
		<div class="cl-book__body">
			<p class="cl-book__vol"><?php echo esc_html( $book['desc'] ); ?></p>
			<<?php echo esc_html( $tag ); ?> class="cl-book__title"><?php echo esc_html( $book['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<div class="cl-book__row">
				<span class="cl-book__price"><?php echo esc_html( $price ); ?></span>
				<?php if ( $book['price'] > 0 ) : ?>
					<button class="cl-add" data-add="<?php echo esc_attr( $book['id'] ); ?>">ADAUGĂ ÎN SEIF</button>
				<?php else : ?>
					<button class="cl-add" disabled>INDISPONIBIL</button>
				<?php endif; ?>
			</div>
			<?php if ( $reviews ) : ?>
				<div class="cl-reviews" data-open="false">
					<div class="cl-reviews__head">
						<span class="cl-reviews__title">CE SPUN CITITORII</span>
						<button class="cl-reviews__toggle" data-reviews-toggle>Vezi toate review-urile</button>
					</div>
					<?php foreach ( array_slice( $reviews, 0, 2 ) as $review ) : ?>
						<div class="cl-review">
							<?php echo nocturne_client_stars( $review['stars'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<p class="cl-review__quote">“<?php echo esc_html( $review['text'] ); ?>”</p>
							<p class="cl-review__by"><?php echo esc_html( $review['name'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * [nocturne_client_series] — the draggable book rail.
 *
 * @return string
 */
function nocturne_client_series_shortcode() {
	$catalogue = nocturne_client_catalogue();
	$count     = count( $catalogue['series'] );

	ob_start();
	?>
	<section class="cl-section cl-wrap">
		<div class="cl-rail-head">
			<div>
				<p class="cl-eyebrow" style="margin-bottom:10px">O SAGĂ MAFIA DARK ROMANCE</p>
				<h2 class="cl-h2" style="margin:0">SERIA IMPERIUL UMBRELOR</h2>
				<p class="cl-rail-head__count"><?php echo esc_html( $count ); ?> / <?php echo esc_html( $count ); ?> volume • TRAGE SAU FOLOSEȘTE SĂGEȚILE</p>
			</div>
			<div class="cl-rail-nav">
				<button class="cl-rail-btn" data-rail-prev aria-label="Carte anterioară">←</button>
				<button class="cl-rail-btn" data-rail-next aria-label="Carte următoare">→</button>
			</div>
		</div>

		<div class="cl-rail" data-rail>
			<div class="cl-rail__track" data-rail-track style="display:flex;gap:20px;overflow-x:auto;scroll-behavior:smooth;padding-bottom:20px;flex:1">
				<?php foreach ( $catalogue['series'] as $book ) : ?>
					<?php echo nocturne_client_book_card( $book ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="cl-rail-dots" data-rail-dots></div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_client_series', 'nocturne_client_series_shortcode' );

/**
 * [nocturne_client_standalone] — the standalone titles.
 *
 * @return string
 */
function nocturne_client_standalone_shortcode() {
	$catalogue = nocturne_client_catalogue();

	ob_start();
	?>
	<section id="standalone" class="cl-section cl-wrap" style="padding-top:0">
		<div class="cl-rail-head" style="margin-bottom:34px">
			<div>
				<p class="cl-eyebrow" style="margin-bottom:10px">POVEȘTI DE SINE STĂTĂTOARE</p>
				<h2 class="cl-h2" style="margin:0">STANDALONE</h2>
				<p class="cl-lede" style="margin-top:12px">Două lumi separate de Imperiu, dar cu același sânge: iubiri care nu cer voie.</p>
			</div>
		</div>

		<div class="cl-standalone">
			<?php foreach ( $catalogue['standalone'] as $book ) : ?>
				<?php $reviews = isset( $catalogue['reviews'][ $book['id'] ] ) ? $catalogue['reviews'][ $book['id'] ] : array(); ?>
				<article class="cl-standalone__card">
					<img src="<?php echo esc_url( nocturne_client_asset( $book['img'] ) ); ?>" alt="<?php echo esc_attr( $book['title'] ); ?>" loading="lazy">
					<div>
						<h3><?php echo esc_html( $book['title'] ); ?></h3>
						<span class="cl-book__price"><?php echo esc_html( $book['price'] . ' LEI' ); ?></span>
						<button class="cl-add" data-add="<?php echo esc_attr( $book['id'] ); ?>" style="margin-bottom:16px">ADAUGĂ ÎN SEIF</button>
						<?php if ( $reviews ) : ?>
							<div class="cl-reviews" data-open="false">
								<div class="cl-reviews__head">
									<span class="cl-reviews__title">CE SPUN CITITORII</span>
									<button class="cl-reviews__toggle" data-reviews-toggle>Vezi toate review-urile</button>
								</div>
								<?php foreach ( array_slice( $reviews, 0, 2 ) as $review ) : ?>
									<div class="cl-review">
										<?php echo nocturne_client_stars( $review['stars'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<p class="cl-review__quote">“<?php echo esc_html( $review['text'] ); ?>”</p>
										<p class="cl-review__by"><?php echo esc_html( $review['name'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_client_standalone', 'nocturne_client_standalone_shortcode' );

/**
 * [nocturne_client_journal] — the journal grid.
 *
 * @return string
 */
function nocturne_client_journal_shortcode() {
	$catalogue = nocturne_client_catalogue();

	ob_start();
	?>
	<section class="cl-section cl-wrap">
		<p class="cl-eyebrow">ANUNȚURI, PRECOMENZI, SECRETE DIN CULISE</p>
		<h1 class="cl-h2" style="font-size:clamp(2.4rem,5vw,3.6rem)">JURNALUL IMPERIULUI</h1>
		<p class="cl-lede" style="max-width:560px">Tot ce se întâmplă în spatele cărților, înainte să ajungă la tine.</p>

		<div class="cl-posts">
			<?php foreach ( $catalogue['posts'] as $post ) : ?>
				<article class="cl-post">
					<div class="cl-post__pic">
						<img src="<?php echo esc_url( nocturne_client_asset( $post['img'] ) ); ?>" alt="<?php echo esc_attr( $post['title'] ); ?>" loading="lazy">
					</div>
					<div class="cl-post__body">
						<div class="cl-post__meta">
							<span class="cl-post__tag"><?php echo esc_html( $post['tag'] ); ?></span>
							<span class="cl-post__date"><?php echo esc_html( $post['date'] ); ?></span>
						</div>
						<h2><?php echo esc_html( $post['title'] ); ?></h2>
						<p><?php echo esc_html( $post['excerpt'] ); ?></p>
						<a class="cl-read" href="<?php echo esc_url( home_url( '/serie/' ) ); ?>">CITEȘTE →</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_client_journal', 'nocturne_client_journal_shortcode' );

/**
 * [nocturne_client_gates] — the three gateways on the home page.
 *
 * @return string
 */
function nocturne_client_gates_shortcode() {
	$art = array(
		'assets/img/client/cover-paculul.jpg',
		'assets/img/client/cover-a94.jpg',
		'assets/img/client/speranta-stack.jpg',
	);

	ob_start();
	?>
	<section class="cl-section cl-wrap" style="padding-top:0">
		<div class="cl-gates">
			<a href="<?php echo esc_url( home_url( '/serie/' ) ); ?>" class="cl-gate">
				<img class="cl-gate__art" src="<?php echo esc_url( nocturne_client_asset( $art[0] ) ); ?>" alt="" aria-hidden="true">
				<p class="cl-gate__tag">DARK ROMANCE • MAFIA</p>
				<h3>INTRĂ ÎN IMPERIUL UMBRELOR</h3>
				<p>Patru volume. O familie. Prețuri care cresc pe măsură ce se închid porțile.</p>
				<span class="cl-gate__go">VEZI SERIA</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/serie/#standalone' ) ); ?>" class="cl-gate">
				<img class="cl-gate__art" src="<?php echo esc_url( nocturne_client_asset( $art[1] ) ); ?>" alt="" aria-hidden="true">
				<p class="cl-gate__tag">POVEȘTI UNICE</p>
				<h3>VEZI STANDALONE</h3>
				<p>Proiecte care nu aparțin Imperiului, dar vin din același sânge.</p>
				<span class="cl-gate__go">VEZI TITLURILE</span>
			</a>
			<a href="<?php echo esc_url( home_url( '/jurnal/' ) ); ?>" class="cl-gate">
				<img class="cl-gate__art" src="<?php echo esc_url( nocturne_client_asset( $art[2] ) ); ?>" alt="" aria-hidden="true">
				<p class="cl-gate__tag">ANUNȚURI & SECRETE</p>
				<h3>CITEȘTE JURNALUL</h3>
				<p>Precomenzi, culise și tot ce se întâmplă înainte să ajungă la cititor.</p>
				<span class="cl-gate__go">DESCHIDE JURNALUL</span>
			</a>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'nocturne_client_gates', 'nocturne_client_gates_shortcode' );
