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
                <h3 id="description">Read books for <?php the_title(); ?>:</h3>

                <?php
                $read_books  = get_post_meta( get_the_ID(), 'pbc_read_books', true );
				$target_goal = get_post_meta( get_the_ID(), 'pbc_total_goal', true );
				$tmpl        = '<p>Total achieved: %s, total aimed for: %s</p><div class="pirate-book-chest-wrapper">%s</div>';
				$items       = '';
				$current     = ( is_array( $read_books ) && ! empty( $read_books ) ) ? count( $read_books ) : '0';
                if ( is_array( $read_books ) && ! empty( $read_books ) ) {
                    $items_tmpl = '<div class="individual-book"><a href="%s">%s</a><h2><a href="%s">%s</a></h2></div>';
                    foreach( $read_books as $book_id ) {
                        $items .= sprintf(
							$items_tmpl,
                            get_permalink( $book_id ),
                            get_the_post_thumbnail( $book_id, 'medium' ),
							get_permalink( $book_id ),
							get_the_title( $book_id )
                        );
                    }
                }

				printf(
					$tmpl,
					$current,
					$target_goal,
					$items
				);
                ?>
            <?php
            endwhile;
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
