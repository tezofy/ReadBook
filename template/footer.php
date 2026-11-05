</main><!-- #content -->

<footer class="vl-footer">
	<div class="vl-container">
		<div class="vl-footer__grid">

			<div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vl-brand">
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

				<p class="vl-footer__about">
					<?php
					$vl_about = get_theme_mod( 'nocturne_footer_about', '' );
					echo esc_html( $vl_about ? $vl_about : __( 'Dark romance for readers who like their love story with teeth.', 'nocturne' ) );
					?>
				</p>

				<?php if ( has_nav_menu( 'social' ) ) : ?>
					<nav class="vl-socials" aria-label="<?php esc_attr_e( 'Social links', 'nocturne' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'social',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="vl-footer__col">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php else : ?>
				<div>
					<h4 class="vl-footer__title"><?php esc_html_e( 'Shop', 'nocturne' ); ?></h4>
					<ul class="vl-footer__list">
						<li><a href="<?php echo esc_url( home_url( '/books/' ) ); ?>"><?php esc_html_e( 'All books', 'nocturne' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/product-category/ebooks/' ) ); ?>"><?php esc_html_e( 'E-books', 'nocturne' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/product-category/paperbacks/' ) ); ?>"><?php esc_html_e( 'Paperbacks', 'nocturne' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/product-category/limited-editions/' ) ); ?>"><?php esc_html_e( 'Limited editions', 'nocturne' ); ?></a></li>
					</ul>
				</div>

				<div>
					<h4 class="vl-footer__title"><?php esc_html_e( 'Read', 'nocturne' ); ?></h4>
					<ul class="vl-footer__list">
						<?php
						$vl_books = get_posts(
							array(
								'post_type'      => 'product',
								'posts_per_page' => 4,
								'orderby'        => 'menu_order',
							)
						);
						foreach ( $vl_books as $vl_book ) :
							?>
							<li><a href="<?php echo esc_url( get_permalink( $vl_book ) ); ?>"><?php echo esc_html( get_the_title( $vl_book ) ); ?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo esc_url( home_url( '/journal/' ) ); ?>"><?php esc_html_e( 'The journal', 'nocturne' ); ?></a></li>
					</ul>
				</div>

				<div>
					<h4 class="vl-footer__title"><?php esc_html_e( 'Contact', 'nocturne' ); ?></h4>
					<ul class="vl-footer__list">
						<?php $vl_email = get_theme_mod( 'nocturne_email', 'hello@example.com' ); ?>
						<li><a href="mailto:<?php echo esc_attr( $vl_email ); ?>"><?php echo esc_html( $vl_email ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Rights & press', 'nocturne' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Stockists', 'nocturne' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Wholesale enquiries', 'nocturne' ); ?></a></li>
					</ul>
					<div style="margin-top:24px">
						<span class="vl-adult-badge"><b>18+</b> <?php esc_html_e( 'Adult content', 'nocturne' ); ?></span>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<div class="vl-footer__bar">
			<p style="margin:0">
				<?php
				printf(
					/* translators: 1: year, 2: site name */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'nocturne' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<nav class="vl-footer__legal" aria-label="<?php esc_attr_e( 'Legal', 'nocturne' ); ?>">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'nocturne' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'nocturne' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/shipping/' ) ); ?>"><?php esc_html_e( 'Shipping', 'nocturne' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/refunds/' ) ); ?>"><?php esc_html_e( 'Refunds', 'nocturne' ); ?></a>
			</nav>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
