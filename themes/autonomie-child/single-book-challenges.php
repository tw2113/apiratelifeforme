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
        <div class="entry-content e-content h-review" itemprop="description articleBody">
            <?php
            while ( have_posts() ) : the_post(); ?>
                <h2><?php the_title(); ?></h2>

                <h3 id="description">Read books for <?php the_title(); ?>:</h3>
                <div class="p-item">
                <?php
                $read_books  = get_post_meta( get_the_ID(), 'pbc_read_books', true );
				$target_goal = get_post_meta( get_the_ID(), 'pbc_total_goal', true );

                if ( is_array( $read_books ) && ! empty( $read_books ) ) {
                    $tmpl       = '<p>Total achieved: %s, total aimed for: %s</p><ol>%s</ol>';
                    $items_tmpl = '<li><a href="%s">%s</a></li>';
                    $items      = '';
                    foreach( $read_books as $book_id ) {
                        $items .= sprintf(
							$items_tmpl,
                            get_permalink( $book_id ),
                            get_the_title( $book_id )
                        );
                    }
					printf(
						$tmpl,
						count( $read_books ),
						$target_goal,
						$items
					);
                }
                ?>
                </div>

            <?php
            endwhile;
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
