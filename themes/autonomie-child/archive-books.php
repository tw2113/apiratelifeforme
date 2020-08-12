<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

get_header(); ?>

<main id="primary">
    <article>
        <div class="entry-content e-content h-entry" itemprop="description articleBody">
			<?php

			get_template_part( 'book-search' );

			$books_count = wp_count_posts( 'books' );
			printf( '<p>Total books in chests: <strong>%s</strong></p>', $books_count->publish );

			$statuses = get_terms(
				'book_status'
			);
			if ( ! is_wp_error( $statuses ) ) {
				$list = '<div class="read-status"><p>Filter by status: %s</p></div>';
				$items = [];
				foreach ($statuses as $status) {
					$items[] = sprintf(
						'<a href="%s">%s</a>',
						get_term_link( $status->term_id ),
						$status->name
					);
				}
				printf( $list, implode( ', ', $items ) );
			}
			?>

            <div class="pirate-book-chest-wrapper">
            <?php
            while ( have_posts() ) : the_post(); ?>
                <div class="individual-book">
                    <a href="<?php the_permalink(); ?>">
					<?php
					if ( has_post_thumbnail( get_the_ID() ) ) {
						the_post_thumbnail('medium');
					} else {
						?>
                        <div class="book-placeholder">Coming once purchased</div>
						<?php
					}
					?>
                    </a>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                </div>
                <?php
            endwhile;
            ?>
            </div>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
