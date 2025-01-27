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
                <?php edit_post_link( 'Edit book', '<p>', '</p>', null, 'btn btn-primary btn-edit-post-link' ); ?>
                <h2><?php the_title(); ?></h2>

                <?php
                the_post_thumbnail( 'book_cover' );
                ?>
                <h3 id="description">Description:</h3>
                <div class="p-item">
                <?php
                the_content();
                ?>
                </div>
                <?php
				echo get_the_term_list( get_the_ID(), 'book_chest', '<p><strong>Chests:</strong> ', ', ', '</p>' );

                ?>
                <h3 id="status">Status:</h3>
                <?php
                $statuses = get_the_terms( get_the_ID(), 'book_status' );
                $has_read = false;
                $reading  = false;

                if ( 'read' === $statuses[0]->slug ) {
                    $has_read = true;
                }

				if ( 'currently-reading' === $statuses[0]->slug ) {
					$reading = true;
				}

				$meta       = get_post_meta(get_the_ID());
				$current    = isset($meta['pbc_current_page'][0]) ? (int)$meta['pbc_current_page'][0] : 0;
				$total      = isset($meta['pbc_total_pages'][0]) ? (int)$meta['pbc_total_pages'][0] : 0;
				if ( $current > 0 ) {
					$percentage = number_format( $current / $total * 100 );
				} else $percentage = '0';

				if ( $has_read ) {
				    $current = $total;
                }

                if ( $reading && $current < $total ) {
					printf(
						'<div class="reading-status"><p><strong>Current page:</strong> %s of %s (%s%% complete)<span style="width: %s%%"></span></p></div>',
						$current,
						$total,
						$percentage,
						$percentage
					);
				}

                if ( ! empty( $meta['pbc_rereads'][0] ) ) {
                    $rereads = maybe_unserialize( $meta['pbc_rereads'][0] );

                    $rereads_started  = ! empty( $rereads[0]['pbc_reread_start_date'] ) ? date( 'Y/m/d', $rereads[0]['pbc_reread_start_date'] ) : 'TBD';
                    $rereads_finished = ! empty( $rereads[0]['pbc_reread_end_date'] ) ? date( 'Y/m/d', $rereads[0]['pbc_reread_end_date'] ) : 'TBD';
                    printf( '<p><strong>Re-reading duration:</strong> %s to %s</p>', $rereads_started, $rereads_finished );
                }
                $started  = ! empty( $meta['pbc_start_date'][0] ) ? date( 'Y/m/d', $meta['pbc_start_date'][0] ) : 'TBD';
                $finished = ! empty( $meta['pbc_finished_date'][0] ) ? date( 'Y/m/d', $meta['pbc_finished_date'][0] ) : 'TBD';
                printf( '<p><strong>Reading duration:</strong> %s to %s</p>', $started, $finished );

                $duration = '';
                if ( 'TBD' !== $started && 'TBD' !== $finished ){
                    $started_utc = strtotime( $started );
                    $finished_utc = strtotime( $finished );

	                $duration = $finished_utc - $started_utc;
                    $duration = round($duration / (60 * 60 * 24));
                }

                if ( $duration ) {
	                printf( '<p><strong>Time needed:</strong> %s days</p>', $duration );
                }

				$rating = str_replace( 'rating', '', $meta['pbc_rating'][0] );
				if ( '0' === $rating ) {
				    $rating = 'n/a';
                }
				printf(
                    '<p class="p-rating"><strong>Rating:</strong> %s star</p>',
					$rating
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
                <h3 id="details">Details:</h3>
                <?php
                $authors = ! empty( $meta['pbc_book_authors'][0] ) ? $meta['pbc_book_authors'][0] : '';
                $isbn    = ! empty( $meta['pbc_book_isbn'][0] ) ? $meta['pbc_book_isbn'][0] : '';
                if ( $authors ) {
                    printf( '<p><strong>Author:</strong> %s</p>', $authors );
                }
                if ( $isbn ) {
                    printf( '<p><strong>ISBN13:</strong> <a target="_blank" rel="nofollow" href="https://www.indiebound.org/book/%s">%s</a> via Indiebound</p>', $isbn, $isbn );
                }

				echo get_the_term_list( get_the_ID(), 'genre', '<p><strong>Genres:</strong> ', ', ', '</p>' );

				edit_post_link( 'Edit book', '<p>', '</p>', null, 'btn btn-primary btn-edit-post-link' );
            endwhile;

            comments_template('/reviews.php');
            ?>
        </div>
    </article>
</main><!-- #content -->

<?php autonomie_content_nav( 'nav-below' ); ?>

<?php get_footer(); ?>
