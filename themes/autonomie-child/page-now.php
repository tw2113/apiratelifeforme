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

				<?php get_template_part( 'templates/content', 'single' ); ?>

				<?php
                if ( comments_open() ) {
					comments_template('', true);
				}
                ?>

			<?php endwhile; // end of the loop. ?>

            <?php
            $args = [
                'post_type'   => 'books',
                'post_status' => 'publish',
                'orderby'     => 'rand',
                'tax_query'   => [
                    [
						'taxonomy' => 'book_status',
						'field'    => 'slug',
						'terms'    => 'currently-reading',
                    ]
                ]
            ];
            $current_books = new WP_Query( $args );
            if ( $current_books->have_posts() ) {
                echo '<div class="entry-content">';
                echo '<h3>Currently Reading: </h3>';
                echo '<ul>';
                while ( $current_books->have_posts() ) {
                    $current_books->the_post();
                    printf(
                        '<li><a href="%s">%s</a></li>',
                        get_permalink(),
                        get_the_title()
                    );
                }
                wp_reset_postdata();
                echo '</ul></div>';
            }
            ?>
		</main><!-- #content -->

<?php get_footer(); ?>
