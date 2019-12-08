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
            <div class="entry-content e-content" itemprop="description articleBody">
				<?php

                the_content();

				?>
            </div><!-- .entry-content -->

			<?php comments_template( '', true ); ?>
        </article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // end of the loop. ?>

</main><!-- #content -->

<?php get_footer(); ?>
