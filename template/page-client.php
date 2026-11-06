<?php
/**
 * Template Name: Client edition
 * Template Post Type: page, post
 *
 * The Christiana Ciocan design: the burgundy header with the gold
 * monogram crest, the client navigation overlay and the burgundy footer.
 * The page's own content is printed inside the client shell, so the
 * shortcodes work exactly as they do in the static preview.
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
			<?php
			while ( have_posts() ) :
				the_post();
				$nocturne_client_content = apply_filters( 'the_content', get_the_content() );
				$nocturne_client_content = do_shortcode( $nocturne_client_content );
				echo $nocturne_client_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endwhile;
			?>
		</div>
	</main>

	<?php require_once NOCTURNE_DIR . '/inc/client-footer.php'; ?>
</div>

<?php
get_footer();
