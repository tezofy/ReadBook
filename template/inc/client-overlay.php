<?php
/**
 * Nocturne — the client edition navigation overlay.
 *
 * The slide-in panel with the client's navigation, the quick contact
 * list and the vault (cart).
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="cl-overlay" data-overlay>
	<div class="cl-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Meniu', 'nocturne' ); ?>">
		<div class="cl-panel__head">
			<span class="cl-panel__title"><?php esc_html_e( 'NAVIGAȚIE', 'nocturne' ); ?></span>
			<button class="cl-panel__close" data-overlay-close aria-label="<?php esc_attr_e( 'Închide', 'nocturne' ); ?>">&times;</button>
		</div>

		<div class="cl-panel__body">
			<div>
				<p class="cl-panel__label"><?php esc_html_e( 'NAVIGAȚIE', 'nocturne' ); ?></p>
				<nav class="cl-menu">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Acasă', 'nocturne' ); ?> <span>HOME</span></a>
					<a href="<?php echo esc_url( home_url( '/serie/' ) ); ?>"><?php esc_html_e( 'Seria Imperiul Umbrelor', 'nocturne' ); ?> <span>CĂRȚI</span></a>
					<a href="<?php echo esc_url( home_url( '/serie/#standalone' ) ); ?>"><?php esc_html_e( 'Proiecte Viitoare', 'nocturne' ); ?> <span>STANDALONE</span></a>
					<a href="<?php echo esc_url( home_url( '/jurnal/' ) ); ?>"><?php esc_html_e( 'Blog / Jurnalul', 'nocturne' ); ?> <span>JURNAL</span></a>
				</nav>
			</div>

			<div>
				<p class="cl-panel__label"><?php esc_html_e( 'CONTACT RAPID', 'nocturne' ); ?></p>
				<div class="cl-contact-list">
					<a href="mailto:cristiana.ciocan@gmail.com">cristiana.ciocan@gmail.com</a>
					<a href="mailto:contact@cristiana-ciocan.ro">contact@cristiana-ciocan.ro</a>
					<a href="#">Instagram @christiana.ciocan</a>
					<a href="#">Threads @chris.inksins</a>
				</div>
			</div>

			<div>
				<p class="cl-panel__label"><?php esc_html_e( 'CĂRȚI • SEIFUL TĂU', 'nocturne' ); ?></p>
				<div data-vault-body></div>
			</div>
		</div>

		<div class="cl-vault-foot" data-vault-foot>
			<div class="cl-vault-total">
				<span><?php esc_html_e( 'TOTAL ESTIMAT', 'nocturne' ); ?></span>
				<b><span data-vault-total>0</span> LEI</b>
			</div>
			<button class="cl-btn"><?php esc_html_e( 'FINALIZEAZĂ COMANDA', 'nocturne' ); ?></button>
			<p class="cl-note"><?php esc_html_e( 'LIVRARE PRIN CURIER • PLATĂ RAMBURS / CARD', 'nocturne' ); ?></p>
		</div>
	</div>
</div>
