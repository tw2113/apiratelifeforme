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

                <?php the_content(); ?>

                <?php
                $challenge_year = substr( get_the_title(), 0, 4 );
                $current_year   = date( 'Y' );
                $status         = tw2113\pbc\get_reading_challenge_status();
                if ( $challenge_year !== $current_year ) {
	                $status = 'challenge concluded';
                }

                $read_books     = get_post_meta( get_the_ID(), 'pbc_read_books', true );
                $target_goal    = get_post_meta( get_the_ID(), 'pbc_total_goal', true );

                $tmpl           = '<p><strong>Total achieved:</strong> %s, <strong>total aimed for:</strong> %s, <strong>current status:</strong> %s</strong></p><p><strong>Total pages read:</strong> %s</p><div class="pirate-book-chest-wrapper">%s</div>';
                $items          = '';
                $current        = ( is_array( $read_books ) && ! empty( $read_books ) ) ? count( $read_books ) : '0';
                $total_pages    = [];
                if ( is_array( $read_books ) && ! empty( $read_books ) ) {
                    $items_tmpl = '<div class="individual-book"><a href="%s">%s</a><h2><a href="%s">%s</a></h2></div>';
                    foreach( $read_books as $book_id ) {
                        $total_pages[] = get_post_meta( $book_id, 'pbc_total_pages', true );
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
					$status,
					array_sum( $total_pages ),
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
