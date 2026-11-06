<?php
/**
 * Nocturne — the client edition header.
 *
 * A deep burgundy bar with the gold monogram crest sitting above it,
 * the client's navigation and the vault (cart) pill.
 *
 * @package Nocturne
 */

defined( 'ABSPATH' ) || exit;

$nocturne_client_home = home_url( '/' );
$nocturne_client_carte = home_url( '/serie/' );
$nocturne_client_jurnal = home_url( '/jurnal/' );
?>
<header class="cl-header">
	<a href="<?php echo esc_url( $nocturne_client_home ); ?>" class="cl-brand" aria-label="<?php esc_attr_e( 'Christiana Ciocan — acasă', 'nocturne' ); ?>">
		<span class="cl-header__crest">
			<img src="<?php echo esc_url( nocturne_client_asset( 'assets/img/client/logo-ci-transparent.png' ) ); ?>" alt="">
		</span>
		<span class="cl-brand__name"><?php esc_html_e( 'CHRISTIANA CIOCAN', 'nocturne' ); ?></span>
	</a>

	<nav class="cl-nav" aria-label="<?php esc_attr_e( 'Navigație principală', 'nocturne' ); ?>">
		<a href="<?php echo esc_url( $nocturne_client_home ); ?>" class="is-active"><?php esc_html_e( 'Acasă', 'nocturne' ); ?></a>
		<a href="<?php echo esc_url( $nocturne_client_carte ); ?>"><?php esc_html_e( 'Cărți', 'nocturne' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/despre/' ) ); ?>"><?php esc_html_e( 'Despre mine', 'nocturne' ); ?></a>
		<a href="<?php echo esc_url( $nocturne_client_jurnal ); ?>"><?php esc_html_e( 'Jurnal', 'nocturne' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'nocturne' ); ?></a>
	</nav>

	<div class="cl-header__tools">
		<button class="cl-icon-btn cl-burger" data-overlay-open aria-label="<?php esc_attr_e( 'Deschide meniul', 'nocturne' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
				<path d="M3 7h18M3 12h18M3 17h18"/>
			</svg>
		</button>

		<button class="cl-vault" data-overlay-open aria-label="<?php esc_attr_e( 'Seiful tău', 'nocturne' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:15px;height:15px">
				<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 4v16M11 9h6"/>
			</svg>
			<span><?php esc_html_e( 'Seif', 'nocturne' ); ?></span>
			<span class="cl-vault__count" data-vault-count style="display:none">0</span>
		</button>
	</div>
</header>
