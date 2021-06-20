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
                <h3 id="description">Collection for <?php the_title(); ?>:</h3>

                <?php the_content(); ?>

                <?php
                $collection  = get_post_meta( get_the_ID(), 'pbc_collection', true );
                $tmpl        = '<div class="pirate-book-chest-wrapper">%s</div>';
                $items       = '';
                if ( is_array( $collection ) && ! empty( $collection ) ) {
                    $items_tmpl = '<div class="individual-book"><a href="%s">%s</a><h2><a href="%s">%s</a></h2></div>';
                    foreach( $collection as $book_id ) {
                        $thumb = ( has_post_thumbnail( $book_id ) ) ? get_the_post_thumbnail( $book_id, 'medium' ) : '<div class="book-placeholder">Coming once purchased</div>';
                        $items .= sprintf(
							$items_tmpl,
                            get_permalink( $book_id ),
                            $thumb,
							get_permalink( $book_id ),
							get_the_title( $book_id )
                        );
                    }
                }

				printf(
					$tmpl,
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
