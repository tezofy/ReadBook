<?php
/**
 * Nocturne — front page.
 *
 * The client's approved home page: the welcome panel, the author block,
 * the three gateways, the series rail and the quick contact form.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="cl-shell">
	<?php
	require_once NOCTURNE_DIR . '/inc/client-header.php';
	require_once NOCTURNE_DIR . '/inc/client-overlay.php';
	?>

	<main class="cl-main">
		<div class="cl-page">

			<?php /* ── WELCOME ─────────────────────────────────────────── */ ?>
			<section id="bun-venit" class="cl-welcome cl-wrap">
				<p class="cl-eyebrow"><?php esc_html_e( 'BINE AȚI VENIT ÎN IMPERIUL MEU', 'nocturne' ); ?></p>
				<h1>
					<?php esc_html_e( 'Aici, poveștile nu se termină cum te aștepți.', 'nocturne' ); ?>
					<em><?php esc_html_e( 'Încep acolo unde alții se opresc.', 'nocturne' ); ?></em>
				</h1>
				<div class="cl-rule" style="margin-bottom:30px"></div>
				<p class="cl-lede"><?php esc_html_e( 'Mă bucur că ești aici. Ia-ți o cafea, așază-te. Am să-ți arăt lumea în care iubirea doare frumos.', 'nocturne' ); ?></p>
				<div class="cl-dots-row"><span></span><span></span><span></span></div>
			</section>

			<?php /* ── AUTHOR ──────────────────────────────────────────── */ ?>
			<section id="despre" class="cl-section cl-wrap">
				<div class="cl-author">
					<div class="cl-author__pic cl-framed">
						<img src="<?php echo esc_url( nocturne_client_asset( 'assets/img/client/author-portrait.jpg' ) ); ?>"
						     alt="<?php esc_attr_e( 'Christiana Ciocan, autoare', 'nocturne' ); ?>">
						<div class="cl-author__stamp"><span><?php esc_html_e( 'FOTOGRAFIE • 2026', 'nocturne' ); ?></span></div>
					</div>

					<div class="cl-author__body">
						<div class="cl-author__head">
							<h2 class="cl-author__name"><?php esc_html_e( 'Christiana Ciocan', 'nocturne' ); ?></h2>
							<span class="cl-author__badge"><?php esc_html_e( 'AUTOARE 2026', 'nocturne' ); ?></span>
						</div>
						<div class="cl-rule" style="margin:0 0 26px;margin-inline:0"></div>

						<p><?php esc_html_e( 'Am 30 de ani și două vieți. Una se trezește dimineața cu o cană în mână și doi copii care mă strigă din cealaltă cameră. Le fac clătite, le leg școlarilor la curte și beau cafeaua rece, de obicei. Cealaltă viață se trezește noaptea, când casa tace, și scrie despre bărbați care nu știu să iubească frumos, dar învață. Despre femei care nu fug, dar tac. Și despre dragostea ca locul unde se decid lucruri pe care nu le poți da înapoi.', 'nocturne' ); ?></p>

						<p><?php esc_html_e( 'Scriu dark romance cu suflet mafia, pentru că acolo am găsit adevărul despre loialitate. Despre cum dragostea poate fi și armă, și scut. Despre bărbații care ar mor pentru femeia lor și femeile care se prefac că n-au nevoie de ei.', 'nocturne' ); ?></p>

						<p><?php esc_html_e( 'Imperiul Umbrelor nu e doar o serie. E locul unde mi-am pus toate fricile și toate curajele. Și te invit să intri. Dar să știi: odată ce intri, nu mai ieși la fel.', 'nocturne' ); ?></p>

						<p class="cl-sign">— <?php esc_html_e( 'Christiana Ciocan', 'nocturne' ); ?></p>
						<p style="font-family:var(--cl-ui);font-size:10px;letter-spacing:3px;color:var(--cl-bronze);margin-top:6px"><?php esc_html_e( 'SEMNEZ CU SÂNGE ȘI SPERANȚĂ', 'nocturne' ); ?></p>
					</div>
				</div>
			</section>

			<?php /* ── THE THREE GATEWAYS ─────────────────────────────────── */ ?>
			<?php echo do_shortcode( '[nocturne_client_gates]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<?php /* ── THE SERIES RAIL ────────────────────────────────────── */ ?>
			<?php echo do_shortcode( '[nocturne_client_series]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<?php /* ── FUTURE PROJECTS ───────────────────────────────────── */ ?>
			<section class="cl-section cl-wrap" style="padding-top:0">
				<div class="cl-future">
					<div class="cl-future__lock">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
					</div>
					<p class="cl-future__tag"><?php esc_html_e( 'MANUSCRIS ÎN LUCRU', 'nocturne' ); ?></p>
					<h3 class="cl-future__title"><?php esc_html_e( 'PROIECTE VIITOARE', 'nocturne' ); ?></h3>
					<p><?php esc_html_e( 'În curând vor fi afișate aici noile manuscrise din Imperiu. Lucrez la ceva ce o să vă țină treji noaptea. Rămâneți aproape.', 'nocturne' ); ?></p>
					<span class="cl-age"><b>18+</b><span><?php esc_html_e( 'CONFIDENȚIAL • ACCES RESTRICȚIONAT', 'nocturne' ); ?></span></span>
				</div>
			</section>

			<?php /* ── CONTACT ────────────────────────────────────────────── */ ?>
			<section id="contact" class="cl-section cl-wrap" style="padding-top:0">
				<div class="cl-contact">
					<div>
						<p class="cl-eyebrow"><?php esc_html_e( 'CONTACT RAPID', 'nocturne' ); ?></p>
						<h2 class="cl-h2"><?php esc_html_e( 'Scrie-mi', 'nocturne' ); ?></h2>
						<p class="cl-lede" style="margin-bottom:30px"><?php esc_html_e( 'Pentru comenzi, colaborări, autografe — scrie-mi. Răspund personal la fiecare mesaj.', 'nocturne' ); ?></p>

						<div class="cl-contact__grid">
							<div class="cl-contact__cell">
								<h4><?php esc_html_e( 'EMAIL', 'nocturne' ); ?></h4>
								<p><a href="mailto:cristiana.ciocan@gmail.com" style="color:inherit;text-decoration:none">cristiana.ciocan@gmail.com</a></p>
							</div>
							<div class="cl-contact__cell">
								<h4><?php esc_html_e( 'COMENZI', 'nocturne' ); ?></h4>
								<p><a href="mailto:contact@cristiana-ciocan.ro" style="color:inherit;text-decoration:none">contact@cristiana-ciocan.ro</a></p>
							</div>
							<div class="cl-contact__cell">
								<h4><?php esc_html_e( 'SOCIAL', 'nocturne' ); ?></h4>
								<p>Instagram @christiana.ciocan<br>Threads @chris.inksins</p>
							</div>
							<div class="cl-contact__cell">
								<h4><?php esc_html_e( 'TELEFON', 'nocturne' ); ?></h4>
								<p>+40 7XX XXX XXX (placeholder)</p>
							</div>
						</div>

						<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
							<div class="cl-contact__cell"><h4><?php esc_html_e( 'PLATĂ CARD / RAMBURS', 'nocturne' ); ?></h4><p style="font-size:.85rem"><?php esc_html_e( 'Sigur, prin curier.', 'nocturne' ); ?></p></div>
							<div class="cl-contact__cell"><h4><?php esc_html_e( 'LIVRARE CURIER 24H', 'nocturne' ); ?></h4><p style="font-size:.85rem"><?php esc_html_e( 'Livrare rapidă în toată țara.', 'nocturne' ); ?></p></div>
							<div class="cl-contact__cell"><h4><?php esc_html_e( 'RETUR 14 ZILE', 'nocturne' ); ?></h4><p style="font-size:.85rem"><?php esc_html_e( 'Dacă nu e pentru tine.', 'nocturne' ); ?></p></div>
						</div>
					</div>

					<form class="cl-contact__form" data-contact-form>
						<p class="cl-eyebrow" style="margin:0"><?php esc_html_e( 'TRIMITE UN MESAJ', 'nocturne' ); ?></p>
						<input type="text" name="name" placeholder="<?php esc_attr_e( 'Numele tău', 'nocturne' ); ?>" required>
						<input type="email" name="email" placeholder="<?php esc_attr_e( 'Emailul tău', 'nocturne' ); ?>" required>
						<input type="text" name="subject" placeholder="<?php esc_attr_e( 'Subiect', 'nocturne' ); ?>">
						<textarea name="message" placeholder="<?php esc_attr_e( 'Tell me what you need...', 'nocturne' ); ?>" required></textarea>
						<button class="cl-btn" type="submit"><?php esc_html_e( 'TRIMITE MESAJUL', 'nocturne' ); ?></button>
						<p class="cl-note" data-contact-status><?php esc_html_e( 'Prin trimitere ești de acord să fii contactată pe email pentru comenzi. Fără spam, doar cărți.', 'nocturne' ); ?></p>
					</form>
				</div>
			</section>

		</div>
	</main>

	<?php require_once NOCTURNE_DIR . '/inc/client-footer.php'; ?>
</div>

<?php
get_footer();
