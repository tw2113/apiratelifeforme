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
            while ( have_posts() ) : the_post(); ?>
                <h2><?php the_title(); ?></h2>

                <?php
                the_post_thumbnail( 'medium' );
                ?>
                <h3 id="description">Book description:</h3>
                <?php
                the_content();

				echo get_the_term_list( get_the_ID(), 'book_chest', '<p><strong>Chests:</strong> ', ', ', '</p>' );

                ?>
                <h3 id="status">My status:</h3>
                <?php
				$meta = get_post_meta( get_the_ID() );
                if ( (int) $meta['pbc_current_page'][0] < (int) $meta['pbc_total_pages'][0] ) {
					printf(
						'<p><strong>Current page:</strong> %s of %s (%s%% complete)</p>',
						$meta['pbc_current_page'][0],
						$meta['pbc_total_pages'][0],
						number_format($meta['pbc_current_page'][0] / $meta['pbc_total_pages'][0] * 100)
					);
				}
                $finished = ! empty( $meta['pbc_finished_date'][0] ) ? date( 'Y/m/d', $meta['pbc_finished_date'][0] ) : 'TBD';
				printf( '<p><strong>Reading duration:</strong> %s to %s</p>', date( 'Y/m/d', $meta['pbc_start_date'][0] ), $finished );

				printf(
                    '<p><strong>Rating:</strong> %s star</p>',
					str_replace( 'rating', '', $meta['pbc_rating'][0] )
                );

				$reviews = get_comments(
					[
						'post_id' => get_the_ID(),
						'type' => 'BookReview',
					]
                );

				if ( ! empty( $reviews ) ) {
				    echo '<h3 id="review">Review: </h3>';
				    foreach ( $reviews as $review ) {
				        echo wpautop( $review->comment_content );
                    }
                }
                ?>
                <h3 id="details">Book details:</h3>
                <?php
				printf( '<p><strong>Author:</strong> %s</p>', $meta['pbc_book_authors'][0] );
                printf( '<p><strong>ISBN13:</strong> %s</p>', $meta['pbc_book_isbn'][0] );

				echo get_the_term_list( get_the_ID(), 'genre', '<p><strong>Genres:</strong> ', ', ', '</p>' );
                ?>
            <?php
            endwhile;
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
