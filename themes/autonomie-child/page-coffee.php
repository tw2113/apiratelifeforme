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

	<?php while ( have_posts() ) : the_post(); ?>

        <article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
			<?php get_template_part( 'templates/partials/entry-header' ); ?>
				<?php autonomie_the_post_thumbnail( '<div class="entry-media">', '</div>' ); ?>
                <div class="entry-content e-content" itemprop="description articleBody">
					<?php

                    the_content();

					$paged = 1;
					if ( get_query_var( 'paged' ) ) {
						$paged = get_query_var( 'paged' );
					} else if ( get_query_var( 'page' ) ) {
						// This will occur if on front page.
						$paged = get_query_var( 'page' );
					}

					$args = [
						'post_type'      => 'coffee_checkins',
						'post_status'    => 'publish',
						'posts_per_page' => 100,
						'paged'          => $paged,
					];

					$coffee = new WP_Query( $args );

					echo "<h2>Total checkins: {$coffee->found_posts}</h2>";

					while( $coffee->have_posts() ) {
						$coffee->the_post();

						the_content();

						echo '<hr/>';
					}

					printf( '<div>%s</div>', get_next_posts_link( 'Older checkins', $coffee->max_num_pages ) );
					printf( '<div>%s</div>', get_previous_posts_link( 'Newer checkins', $coffee->max_num_pages ) );

					?>
                </div><!-- .entry-content -->
			<?php get_template_part( 'templates/partials/entry-footer' ); ?>
        </article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // end of the loop. ?>

</main><!-- #content -->

<?php get_footer(); ?>
