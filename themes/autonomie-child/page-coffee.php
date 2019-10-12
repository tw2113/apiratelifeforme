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

					$args = [
						'post_type'      => 'coffee_checkins',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
					];

					$coffee = new WP_Query( $args );

					while( $coffee->have_posts() ) {
						$coffee->the_post();

						the_content();

						echo '<hr/>';
					}
					?>
                </div><!-- .entry-content -->
			<?php get_template_part( 'templates/partials/entry-footer' ); ?>
        </article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // end of the loop. ?>

</main><!-- #content -->

<?php get_footer(); ?>
