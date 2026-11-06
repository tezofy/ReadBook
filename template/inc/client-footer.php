<?php
/**
 * Nocturne — the client edition footer.
 *
 * Deep burgundy, the gold monogram, and the client's own palette strip.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="cl-footer">
	<div class="cl-wrap">
		<div class="cl-footer__grid">
			<div>
				<span class="cl-footer__mark">
					<img src="<?php echo esc_url( nocturne_client_asset( 'assets/img/client/logo-ci-transparent.png' ) ); ?>" alt="<?php esc_attr_e( 'Christiana Ciocan', 'nocturne' ); ?>">
				</span>
				<p class="cl-footer__tag"><?php esc_html_e( 'Mă bucur că ești aici. Ia-ți o cafea, așază-te. Am să-ți arăt lumea în care iubirea doare frumos.', 'nocturne' ); ?></p>
				<div class="cl-footer__social">
					<a href="#" aria-label="Instagram">Instagram</a>
					<a href="#" aria-label="Threads">Threads</a>
				</div>
			</div>

			<div>
				<h4><?php esc_html_e( 'Cărți', 'nocturne' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/serie/' ) ); ?>"><?php esc_html_e( 'Seria Imperiul Umbrelor', 'nocturne' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/serie/#standalone' ) ); ?>"><?php esc_html_e( 'Standalone', 'nocturne' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/jurnal/' ) ); ?>"><?php esc_html_e( 'Jurnalul', 'nocturne' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Precomandări', 'nocturne' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Autoarea', 'nocturne' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/despre/' ) ); ?>"><?php esc_html_e( 'Despre mine', 'nocturne' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/jurnal/' ) ); ?>"><?php esc_html_e( 'Blog', 'nocturne' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'nocturne' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Proiecte viitoare', 'nocturne' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Contact', 'nocturne' ); ?></h4>
				<ul>
					<li><a href="mailto:cristiana.ciocan@gmail.com">cristiana.ciocan@gmail.com</a></li>
					<li><a href="mailto:contact@cristiana-ciocan.ro">contact@cristiana-ciocan.ro</a></li>
					<li><a href="#">+40 7XX XXX XXX</a></li>
					<li><a href="#">Instagram @christiana.ciocan</a></li>
				</ul>
			</div>
		</div>

		<div class="cl-footer__bottom">
			<p class="cl-footer__copy"><?php esc_html_e( '© 2026 CHRISTIANA CIOCAN — AUTOARE. Toate drepturile rezervate.', 'nocturne' ); ?></p>
			<nav class="cl-footer__legal">
				<a href="#"><?php esc_html_e( 'Termeni', 'nocturne' ); ?></a>
				<a href="#"><?php esc_html_e( 'Confidențialitate', 'nocturne' ); ?></a>
				<a href="#"><?php esc_html_e( 'Livrare & retur', 'nocturne' ); ?></a>
			</nav>
		</div>

		<div class="cl-footer__palette" aria-hidden="true">
			<span class="cl-footer__palette-group">
				<b><?php esc_html_e( 'BOHO BEJ', 'nocturne' ); ?></b>
				<i style="background:#F5F1E8"></i><i style="background:#EDE3D3"></i><i style="background:#D8C4A8"></i>
			</span>
			<span class="cl-footer__palette-group">
				<b><?php esc_html_e( 'VIȘINIU', 'nocturne' ); ?></b>
				<i style="background:#6E1A2B"></i><i style="background:#4A0E1C"></i><i style="background:#3D0E18"></i>
			</span>
			<span class="cl-footer__palette-group">
				<b><?php esc_html_e( 'NEGRU', 'nocturne' ); ?></b>
				<i style="background:#08080A"></i>
			</span>
		</div>
	</div>
</footer>
